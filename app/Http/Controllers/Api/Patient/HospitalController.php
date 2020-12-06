<?php
namespace App\Http\Controllers\Api\Patient;
use App\Hospital;
use App\Patient;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\Hospital\HospitalDetails;
use App\Http\Resources\Api\Hospital\HospitalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HospitalController extends ApiController
{
        public function index(Request $request){
            $hospitals = Hospital::with([
                                    'city',
                                    'country',
                                ])
                                ->where('status', 1)
                                ->skip($request->offset)
                                ->take($request->limit);

                                if(isset($request->keyword) && $request->keyword != null){
                                    $hospitals->where('name', 'like', '%'.$request->keyword.'%');
                                }

                                if((isset($request->latitude) && isset($request->longitude)) && ($request->latitude != null && $request->longitude != null)){
                                    $hospitals->whereRaw('(6371 * acos(cos(radians('.$request->latitude.')) * cos(radians(latitude)) * cos(radians(longitude) - radians('.$request->longitude.')) + sin(radians('.$request->latitude.')) * sin(radians(latitude)))) <= 100');
                                }

            $records = $hospitals->get();

            $hospitals = HospitalInfo::collection($records);

            return $this->apiDataResponse($hospitals);
        }


        public function hospitalDetails($id){
            $hospital = Hospital::with([
                                    'specialities_hospitals' => function ($query){
                                        $query->where('specialities_hospitals.status', 1);
                                    },
                                    'certifications_hospitals' => function ($query){
                                        $query->where('certifications_hospitals.status', 1);
                                    },
                                    'awards_hospitals' => function ($query){
                                        $query->where('awards_hospitals.status', 1);
                                    },
                                    'hospital_insurances.insurance' => function ($query){
                                        $query->where('status', 1);
                                    },
                                    'city',
                                    'country',
                                    'patient_fav'
                                ])
                                ->where('id', $id)
                                ->first();

            $info = (new HospitalDetails($hospital))->resolve();

            return $this->apiDataResponse($info);
        }


        public function markHospital(Request $request){
            $hospital = Hospital::where('id', $request->hospital_id)->first();
            $hospital->patient_fav()->toggle(Auth::user()->id);
            return $this->apiSuccessResponse('success');
        }


        public function filterHospitals(Request $request){
            $hospitals=Hospital::where('status',1);
            //favourite hospitals
            if($request->fav == 1 && $request->header('authorization')){
                $user = Patient::where('api_token', explode('Bearer ', $request->header('authorization'))[1])->first();
                
                $hospitals=$hospitals->whereHas('patient_fav',function($q) use($user){
                    $q->where('patient_id', $user->id);
                });
            }

            if($request->keyword != null){
                $hospitals->where('name', 'like', '%'.$request->keyword.'%');
            }

            $hospitals=$hospitals->skip($request->offset)->take($request->limit)->get();
            $hospitals=HospitalInfo::collection($hospitals);
            
            if (count($hospitals) > 0)
            {
                return $this->apiResponse('success', 1, $hospitals, 200);
            }
            else
            {
                return $this->apiResponse('success', 1, [], 200);
            }
        }
}
