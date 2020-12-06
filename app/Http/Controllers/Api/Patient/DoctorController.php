<?php
namespace App\Http\Controllers\Api\Patient;
use App\Patient;
use App\Appointment;
use App\Doctor;
use App\DoctorTiming;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\Doctor\DoctorDetails;
use App\Http\Resources\Api\Doctor\DoctorInfo;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorController extends ApiController
{
    //

    public function index(Request $request){
        $doctors=Doctor::where('status',1);
                
                if(isset($request->keyword) && $request->keyword != null){
            $doctors->where('first_name', 'like', '%'.$request->keyword.'%');
                }

            $doctors->skip($request->offset)
                    ->limit($request->limit);
        $records = $doctors->get();

        $doctors=DoctorInfo::collection($records);
        return $this->apiDataResponse($doctors);
    }


    public function doctorDetails($id){
        $doctor = Doctor::with([
                            'languages',
                            'specialities',
                            'certifications',
                            'reviews.replies',
                            'doctors_insurances.insurance' => function ($query){
                                $query->where('status', 1);
                            },
                        ])
                        ->where('id',$id)
                        ->first();

        $doctor = (new DoctorDetails($doctor))->resolve();

        return $this->apiDataResponse($doctor);
    }


    public function getTimeSlots(Request $request){
        $rules=[
            'doctor_id'=>'required',
            'hospital_id'=>'required',
            'date'=>'required'
        ];
        $this->validate($request,$rules);
        $doctor=Doctor::find($request->doctor_id);
        $date=Carbon::parse($request->date);
        if($date->lt(Carbon::now()->format('Y-m-d'))){
            return $this->apiErrorResponse('Appointment Date Should Be Grater Than Or Equal To Today');
        }
        $time_table=$doctor->timeSlots($request->date,$request->hospital_id);
        return $this->apiDataResponse($time_table);
    }


    public function saveDoctor(Request $request){
        $doctor=Doctor::find($request->doctor_id);
        $doctor->patient_fav()->toggle(auth()->guard('api_patient')->user()->id);
        return $this->apiSuccessResponse('success');
    }


    public function filterDoctors(Request $request){
        $doctors=Doctor::where('status',1);
        
        if($request->fav == 1 && auth()->guard('api_patient')->user()){
            $doctors=$doctors->whereHas('patient_fav',function ($q){
                $q->where('patient_id', auth()->guard('api_patient')->user()->id);
            });
        }

        if($request->keyword != null){
            $doctors->where('first_name', 'like', '%'.$request->keyword.'%');
        }
        
        $doctors=$doctors->skip($request->offset)->limit($request->limit)->get();
        $doctors=DoctorInfo::collection($doctors);
        return $this->apiDataResponse($doctors);
    }
    public function visitedDoctors(Request $request){
        $doctors=Doctor::whereHas('appointments',function($q){
            $q->where('patient_id',Auth::user()->id);
        })->where('first_name','like',"%".$request->keyword."%")->skip($request->offset)->limit($request->limit)->get();
        $doctors=DoctorInfo::collection($doctors);
        return $this->apiDataResponse($doctors);

    }



    public function getAvailableDays(Request $request){
        $rules=[
            'doctor_id'=>'required',
            'hospital_id'=>'required',
        ];
        $this->validate($request,$rules);
        $days=DoctorTiming::select('day')->where('doctor_id',$request->doctor_id)->where('hospital_id',$request->hospital_id)
            ->distinct('day')->
            get()->pluck('day');
        $cancelled_appointment_dates=DB::table('cancelled_appointment_dates')
            ->select(['date'])
            ->where('doctor_id',$request->doctor_id)
            ->where('hospital_id',$request->hospital_id)
            ->where('date','>=',date('Y-m-d'))
            ->get()->pluck('date')->toArray()
        ;

        $data=[
            'days'=>$days,
            'un_available_on'=>$cancelled_appointment_dates
        ];
        return $this->apiDataResponse($data);
    }

}
