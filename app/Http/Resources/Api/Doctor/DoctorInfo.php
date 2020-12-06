<?php
namespace App\Http\Resources\Api\Doctor;
use App\Http\Resources\Api\Hospital\HospitalInfo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Patient;

class DoctorInfo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isFavourite = 0;

        if (Auth::guard('api_patient')->user()) 
        {
            $isFavourite = count($this->patient_fav->where('id', Auth::guard('api_patient')->user()->id)) > 0 ? 1 : 0;
        }

        return[
            'id'=>$this->id,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'starting_fee' => $this->startingFee,
            'is_favourite' => $isFavourite,
            'image'=>asset('public/uploads/images/'.$this->image),
            'rating'=> round($this->reviews->avg('rating'), 2),
            //'rating'=> $this->reviews->average('rating') ? round($this->reviews->average('rating')) : 0,
            'specialities'=>Specialities::collection($this->specialities),
            'certifications'=>Certifications::collection($this->certifications),
            'affiliated_hospitals'=>HospitalInfo::collection($this->hospitals)
        ];
    }
}
