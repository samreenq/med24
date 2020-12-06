<?php

namespace App\Http\Resources\Api\Appointment;

use App\Http\Resources\Api\Doctor\DoctorInfo;
use App\Http\Resources\Api\Hospital\HospitalInfo;
use App\Http\Resources\Api\Patient\PatientProfile;
use App\Http\Resources\Api\Patient\FamilyMemberInfo;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'hospital_id'=>$this->hospital_id,
            'doctor_id'=>$this->doctor_id,
            'patient_id'=>$this->patient_id,
            'insurance_id' => $this->insurance_id,
            'familyMemberId' => $this->family_member_id,
            'appointment_date'=>Carbon::parse($this->appointment_date)->format('d-m-Y'),
            'appointment_time'=>Carbon::parse($this->appointment_time)->format('h:i A'),
            'extraDetails' => $this->extraDetails ?? "",
            'status'=>$this->status,
            'insurance' => $this->insurance->name ?? "",
            'patient'=>(new PatientProfile($this->whenLoaded('patient'))),
            'hospital'=>(new HospitalInfo($this->whenLoaded('hospital'))),
            'doctor'=>(new DoctorInfo($this->whenLoaded('doctor'))),
            'familyMember' => $this->familyMember ? $this->familyMember->first_name.' '.$this->familyMember->last_name : "",
        ];
    }
}
