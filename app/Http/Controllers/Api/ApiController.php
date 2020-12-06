<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\City;
use App\Country;
use App\Hospital;
use App\Language; 
use App\SpecialityHospital; 
use App\CertificationHospital; 
use App\AwardHospital;
use App\Insurance;
use App\InsurancesPlans;
use App\Speciality;
use App\Http\Resources\Api\Hospital\HospitalInfo;
use App\Http\Traits\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller{
    use ApiResponser;

    public function hospitals(){
        $hospitals=Hospital::where('status',1)->get();
        $hospitals=HospitalInfo::collection($hospitals);
        return $this->apiDataResponse($hospitals);
    }

    public function specialities(){
        $specialities=Speciality::where('status',1)->get()->map(function($q){
            return[
                'id'=>$q->id,
                'name'=>$q->name
            ];
        });
        return $this->apiDataResponse($specialities);
    }

    public function countries(){
        $countries=Country::select('id','name','code','phone_code')->orderBy('name','ASC')->get();
        return $this->apiDataResponse($countries);
    }


    public function languages(){
        $languages=Language::select('id','name','code')->orderBy('name','ASC')->get();
        return $this->apiDataResponse($languages);
    }

    function cities(Request $request){
        $country = Country::where('id', $request->id)
                          ->first();

        $status = 0;
        $message = "Invalid country id";
        $data = [];

        if($country){
            $cities = City::select('id','name')->where('country_id', $country->code)->get();
            
            $status = 1;
            $message = "Cities found";
            $data = $cities;
        }
        
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ]);
    }

    function hospitalSpecialties(Request $request){
        $specialties = SpecialityHospital::select('id', 'name')
                        ->where('status', 1)
                        ->orderBy('name','ASC')
                        ->get();

        return $this->apiDataResponse($specialties);
    }

    function hospitalCertifications(Request $request){
        $certficates = CertificationHospital::select('id', 'name')
                        ->where('status', 1)
                        ->orderBy('name','ASC')
                        ->get();

        return $this->apiDataResponse($certficates);
    }

    function hospitalAwards(Request $request){
        $awards = AwardHospital::select('id', 'name')
                        ->where('status', 1)
                        ->orderBy('name','ASC')
                        ->get();

        return $this->apiDataResponse($awards);
    }

    function hospitalInsurances(Request $request){
        $insurances = Insurance::select('id', 'name', 'description')
                        ->where([
                            'status' => 1,
                        ])
                        ->orderBy('name','ASC')
                        ->get();

        $records = [];

        if(count($insurances) > 0){
            foreach($insurances as $key => $value){
                $records[] = array(
                    'id' => $value->id,
                    'name' => $value->name
                );    
            }
        }

        return $this->apiDataResponse($records);
    }

    function insurancePlans(Request $request){
        $insurancePlans = InsurancesPlans::select('id', 'name')
                        ->where([
                            'insurance_id' => $request->insuranceId,
                            'status' => 1,
                        ])
                        ->orderBy('name', 'asc')
                        ->get();

        $filteredInsurancesPlans = [];

        if(count($insurancePlans)){
            foreach($insurancePlans as $insurancePlan){
                $filteredInsurancesPlans[] = [
                    'id' => $insurancePlan->id,
                    'name' => $insurancePlan->name,
                ];
            }
        }

        return $this->apiDataResponse($filteredInsurancesPlans);
    }
}