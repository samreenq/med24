<?php
namespace App\Http\Controllers\Api\Patient;
use App\FamilyMember;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\FamilyMemberInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Relation;

class FamilyMemberController extends ApiController
{
    public function index(){
        $family_members=FamilyMember::where('patient_id',Auth::user()->id)->get();
        $family_members=FamilyMemberInfo::collection($family_members);
        
        if (count($family_members) > 0)
        {
            return $this->apiResponse('success', 1, $family_members, 200);
        }
        else
        {
            return $this->apiResponse('success', 1, [], 200);
        }
    }

    public function saveFamilyMember(Request $request){
        $relation = Relation::where('name', $request->relation)
            ->first();

        if (!$relation)
        {
            return $this->apiErrorResponse('Invalid relation');
        }

        $family_member = FamilyMember::where('id',$request->id)
            ->where('patient_id',Auth::user()->id)
            ->first();

        if (!$family_member)
        {
            $family_member = new FamilyMember();
        }

        $request->patient_id = Auth::user()->id;
        $family_member->store($request);

        if ($family_member instanceof FamilyMember)
        {
            $family_member = (new FamilyMemberInfo($family_member))->resolve();
            
            return $this->apiDataResponse($family_member);
        }

        return $this->apiErrorResponse('Something went wrong');
    }

    public function deleteFamilyMember(Request $request){
        $family_member=FamilyMember::destroy($request->id);

        return $this->apiSuccessResponse('success');
    }

    public function relations(){
        $records = Relation::where('status', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        $data['records'] = [];

        if (count($records) > 0) 
        {
            foreach ($records as $key => $value) 
            {
                $data['records'][] = [
                    'id' => $value->id,
                    'name' => $value->name
                ];
            }
        }

        return $this->apiDataResponse($data);
    }
}