<?php
namespace App\Http\Controllers\Hospital\Appointments;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use DB;
use Auth;
use App\Http\Controllers\Controller;
use App\Appointment;
use App\Doctor;
use App\Patient;
use App\DoctorTiming;
use App\Hospital;
use App\FamilyMember;
use App\InsurancesPlans;

class IndexController extends Controller{
	public $get_name = 'appointment';
	
	function pageTitle($title){
		if(substr($title, -1) == 'y'){
			$title = substr($title, 0, -1) . 'ies';
		}elseif(substr($title, -2) == 'ss'){
			$title = $title . 'es';
		}else{
			$title = $title . 's';
		}

		return str_replace(['-', '_'], ' ', $title);
	}

	function index(){
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->pageTitle($this->get_name);

        $data['doctors'] = Doctor::whereHas('hospitals', function ($query){
			                    $query->where('hospital_id', Auth::guard('hospital')->user()->id);
			                })
        					->where('status', 1)
					        ->orderBy('created_at', 'desc')
		                    ->get();

	 	$data['patients'] = Patient::where('status', 1)
						    ->orderBy('created_at', 'desc')
					        ->get();

        $data['appointments'] = Appointment::with([
			'doctor',
			'hospital',
			'patient',
	  	])
        ->where('type', 'appointment')
		->where('hospital_id', Auth::guard('hospital')->user()->id)
		->orderBy('created_at', 'desc')
        ->get();

        return view('hospital.appointment.index', $data);
    }

    function getDoctorInsurances(Request $request){
        $rules = [
            'doctorId' => 'required',
        ];

        $this->validate($request, $rules);

        $doctorInsurances = Doctor::whereHas('hospitals', function ($query){
				                    $query->where('hospital_id', Auth::guard('hospital')->user()->id);
				                })
                             	->where([
                                    'id' => $request->doctorId,
                                    'status' => 1,
                             	])
                             	->first();

        $data = [];
        $message = "Something wen't wrong";
        $status = 0;

        if(count($doctorInsurances->doctors_insurances) > 0){
            $data = $doctorInsurances->doctors_insurances;
            $message = "Success";
            $status = 1;
        }

        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], 200);
    }

    function getPatientFamilyMembers(Request $request){
    	$rules = [
            'patientId' => 'required',
        ];

        $this->validate($request, $rules);

    	$familyMembers = Patient::with('patientFamilyMembers')
								 ->where([
								 	'id' => $request->patientId,
							 		'status' => 1,
					 		   	 ])
								 ->first();

	 	$data = [];
	 	$message = "Something wen't wrong";
	 	$status = 0;

	 	if(count($familyMembers->patientFamilyMembers) > 0){
            $filteredFamilyMembers = [];

            foreach($familyMembers->patientFamilyMembers as $familyMember){
                $filteredFamilyMembers[] = [
                    'id' => $familyMember->id,
                    'name' => $familyMember->first_name.' '.$familyMember->last_name,
                ];
            }

	 		$data = $filteredFamilyMembers;
	 		$message = "Success";
	 		$status = 1;
	 	}

	 	return response()->json([
			'message' => $message,
			'data' => $data,
			'status' => $status,
		], 200);
    }

    function getAvailableDays(Request $request){
        $rules = [
            'doctorId' => 'required',
        ];

        $this->validate($request, $rules);

        $totalDays = DoctorTiming::select('day')
        					->where([
        						'doctor_id' => $request->doctorId,
        						'hospital_id' => Auth::guard('hospital')->user()->id,
        					])
            				->distinct('day')
            				->get()
            				->pluck('day');

        $cancelledDates = DB::table('cancelled_appointment_dates')
				            ->select('date')
				            ->where([
				            	'doctor_id' => $request->doctorId,
        						'hospital_id' => Auth::guard('hospital')->user()->id,
				            ])
				            ->where('date', '>=', date('Y-m-d'))
				            ->get()
				            ->pluck('date');

    	$data = [];
        $message = "No available days found";
        $status = 0;

        if(count($totalDays) > 0){
	        $availableDays = [];
	        $cancelledDays = [];

	        foreach($totalDays as $day){
	        	$availableDays[date('w', strtotime($day))] = date('w', strtotime($day));	
	        }

	        if(count($cancelledDates) > 0){
	        	foreach($cancelledDates as $cancelledDate){
		        	$cancelledDays[date('w', strtotime($cancelledDate))] = date('w', strtotime($cancelledDate));
		        }
	        }	

	        $data = array(
	            'availableDays' => $availableDays,
	            'cancelledDays' => $cancelledDays,
	        );
            $message = "Available days found";
	        $status = 1;
        }

        return response()->json([
			'data' => $data,
            'message' => $message,
			'status' => $status,
		], 200);
    }

    function getAvailableTimeSlots(Request $request){
        $rules = [
            'doctorId' => 'required',
            'appointmentDate' => 'required|date_format:d-m-Y|after_or_equal:'.date('d-m-Y'),
        ];

        $this->validate($request, $rules);

        $doctor = Doctor::find($request->doctorId);

        if(!$doctor){
        	return response()->json([
				'data' => [],
				'message' => 'Invalid doctor id.',
				'status' => 0,
			], 200);
        }

        $availableTimeSlots = $doctor->timeSlots(date('Y-m-d', strtotime($request->appointmentDate)), Auth::guard('hospital')->user()->id);

        return response()->json([
			'data' => $availableTimeSlots,
			'message' => 'Available time slots',
			'status' => 1,
		], 200);
    }

    function saveAppointment(Request $request){
    	$rules = [
            'doctor' => 'required',
            'patient' => 'required',
            'familyMember' => 'required',
            'appointmentDate' => 'required|date_format:d-m-Y',
            'appointmentTime' => 'required',
        ];

        $this->validate($request, $rules);

        $doctor = Doctor::find($request->doctor);

        if(!$doctor){
        	return response()->json([
				'data' => [],
				'message' => 'Invalid doctor id.',
				'status' => 0,
			], 200);
        }

        $patient = Patient::find($request->patient);

        if(!$patient){
        	return response()->json([
				'data' => [],
				'message' => 'Invalid patient id.',
				'status' => 0,
			], 200);
        }

        if($request->insurance != 0){
            $insurance = InsurancesPlans::find($request->insurance);

            if(!$insurance){
                return response()->json([
                    'data' => [],
                    'message' => 'Invalid insurance id.',
                    'status' => 0,
                ], 200);
            }
        }

        if($request->familyMember != 0){
            $familyMember = FamilyMember::find($request->familyMember);

            if(!$patient){
                return response()->json([
                    'data' => [],
                    'message' => 'Invalid family member id.',
                    'status' => 0,
                ], 200);
            }
        }

        if($request->recordId){
            $appointment = Appointment::where([
                                'id' => $request->recordId,
                                'hospital_id' => Auth::guard('hospital')->user()->id
                            ])
                            ->first();

            if(!$appointment){
                return response()->json([
                    'data' => [],
                    'message' => 'Invalid appointment id.',
                    'status' => 0,
                ], 200);
            }
        }

        $appointment = Appointment::find($request->recordId);

        if(!$appointment){
            $appointment = new Appointment();
        }

        $appointment->doctor_id = $request->doctor;
        $appointment->hospital_id = Auth::guard('hospital')->user()->id;
        $appointment->patient_id = $request->patient;
        $appointment->family_member_id = $request->familyMember;
        $appointment->insurance_id = $request->insurance;
        $appointment->appointment_date = date('Y-m-d', strtotime($request->appointmentDate));
        $appointment->appointment_time = date('H:i', strtotime($request->appointmentTime));
        $appointment->on_waiting = $request->onWaiting ? true : false;
        $appointment->extraDetails = $request->extraDetails;
        $appointment->status = 'scheduled';

        $message = 'Appoint has been schedule successfully.';
        $status = 1;

        if(!$appointment->save()){
        	$message = 'Something wen\'t wrong !!';
        	$status = 0;
        }

        return response()->json([
			'message' => $message,
			'status' => $status,
		], 200);
    }

    function updateStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|in:pending,cancelled,rejected,completed,scheduled'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }        

        $appointment = Appointment::where([
                            'id' => $request->id,
                            'hospital_id' => Auth::guard('hospital')->user()->id
                        ])
                        ->first();

        if(!$appointment){
            return redirect()->back()->with('error', 'Invalid appointment id');
        }

        $appointment->status = $request->status;

        if(!$appointment->save()){
            return redirect()->back()->with('error', 'Something wen\'t wrong');   
        }

        return redirect()->back()->with('success', 'Record has been updated successfully');
    }

    function edit(Request $request){
        $appointment = Appointment::where([
                            'id' => $request->recordId,
                            'hospital_id' => Auth::guard('hospital')->user()->id
                        ])
                        ->first();

        $status = 1;
        $message = 'Record found';
        $data = $appointment;

        if(!$appointment){
            $status = 0;
            $message = 'Record not found';
            $data = [];
        }

        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ], 200);
    }

    public function viewCallbackRequest()
    {
        $data['menu_active'] = 'callbackRequest';
        $data['title'] = 'Manage Callback Request';

        $data['records'] = Appointment::with([
            'doctor',
            'hospital',
            'patient'
        ])
        ->where('type', 'call_back_request')
        ->where('hospital_id', Auth::guard('hospital')->user()->id)
        ->orderBy('created_at', 'desc')
        ->get();

        return view('hospital.appointment.viewCallbackRequest', $data);
    }
}