<?php

namespace App\Http\Resources\Api\Doctor;

use App\Http\Resources\Api\Appointment\AppointmentResource;
use App\Http\Resources\Api\ReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorDashboard extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data= (new DoctorProfile($this))->resolve();
        return [
            'profile'=>$data,
            'appointments'=>[
                'current'=>AppointmentResource::collection($this->appointments->where('status',1)),
                'past_appointments'=>AppointmentResource::collection($this->appointments)->resource->chunk(4),
            ],
            'overview'=>[
                'about'=>$this->about,
                'languages'=>$this->languages,
                'biography'=>$this->biography,
                'summary'=>$this->summary,
                'care_philosophy'=>$this->care_philosophy,
                'specialities'=>Specialities::collection($this->specialities),
                'certifications'=>Certifications::collection($this->certifications),
            ],
            'locations'=>[],
            'reviews'=>ReviewResource::collection($this->reviews),
            'insurance'=>[],
            'affiliated_hospitals'=>[],
        ];
    }
}
