<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Api\ApiController;
use App\MedicalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\MedicalInfo As PatientMedicalInfo;
class MedicalInfoController extends ApiController
{
    public function index(Request $request){
        $medical_info=MedicalInfo::where('patient_id',Auth::user()->id);
        
        if(isset($request->type)){
            $medical_info->where('type', $request->type);
        }

        $medical_info=PatientMedicalInfo::collection($medical_info->get())->resolve();
        
        if (count($medical_info) > 0)
        {
            return $this->apiResponse('success', 1, $medical_info, 200);
        }
        else
        {
            return $this->apiResponse('success', 1, [], 200);
        }
    }


    public function saveMedicalInfo(Request $request){
        $this->validate($request,[
                'title'=>'nullable|max:50'
            ]
        );
        $medical_info=MedicalInfo::find($request->id);
        if(!$medical_info){
            $medical_info=new MedicalInfo();
        }
        $request->patient_id=Auth::user()->id;
        $medical_info->store($request);
        if($medical_info instanceof  MedicalInfo){
            return $this->apiSuccessResponse('success');
        }
        return $this->apiErrorResponse('failed');
    }

    function delete(Request $request){
        $medicalInfo = MedicalInfo::destroy($request->id);

        return $this->apiSuccessResponse('success');
    }
}
