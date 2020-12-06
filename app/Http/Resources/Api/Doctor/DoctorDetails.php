<?php

namespace App\Http\Resources\Api\Doctor;

use App\Http\Resources\Api\Appointment\AppointmentResource;
use App\Http\Resources\Api\Hospital\HospitalInfo;
use App\Http\Resources\Api\ReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    /*
     * ->map(function ($query){
						            $query->setRelation('latestThreeAppliedCandidates', $query->latestThreeAppliedCandidates->take(3));

						            return $query;
					           });
     * */
    public function toArray($request){
        $insurances = [];

        $data = (new DoctorProfile($this))->resolve();

        if(count($this->doctors_insurances)){
            foreach($this->doctors_insurances as $insurance){
                $insurances[$insurance->insurance->id] = [
                    'id' => $insurance->insurance->id,
                    'name' => $insurance->insurance->name,
                    'email' => $insurance->insurance->email ?? "",
                    'description' => $insurance->insurance->description ?? "",
                    'profilePhoto' => $insurance->insurance->profilePhoto ? asset('public/uploads/'.$insurance->insurance->profilePhoto) : "",
                    'coverPhoto' => $insurance->insurance->coverPhoto ? asset('public/uploads/'.$insurance->insurance->coverPhoto) : "",
                ];
            }
        }

        return [
            'profile'=>$data,
            'overview'=>[
                'about'=>$this->about,
                'languages'=>$this->languages,
                'biography'=>$this->biography,
                'summary'=>$this->summary,
                'care_philosophy'=>$this->care_philosophy,
                'specialities'=>Specialities::collection($this->specialities),
                'certifications'=>Certifications::collection($this->certifications),
                'awards'=>[['id'=>1,'name'=>'Award One'],['id'=>2,'name'=>'Award Two']],
                'total_experience' => $this->total_experience,
                'experience_type' => $this->experience_type
            ],
            'accepted_insurances'=>[],
            'locations'=>[],
            'reviews'=>ReviewResource::collection($this->reviews->take(5)),
            'insurance' => $insurances,
            'affiliated_hospitals'=>HospitalInfo::collection($this->hospitals),
            'isReviewed' => auth()->guard('api_patient')->user() && $this->reviews->where('doctor_id', $this->id)->where('patient_id', auth()->guard('api_patient')->user()->id)->first() ? true : false,
            'isAppointed' => auth()->guard('api_patient')->user() && $this->appointments->where('doctor_id', $this->id)->where('patient_id', auth()->guard('api_patient')->user()->id)->where('status', 'completed')->first() ? true : false
        ];
    }
}
