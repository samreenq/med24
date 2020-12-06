<?php
namespace App\Http\Resources\Api\Appointment;
use App\Http\Resources\Api\Doctor\DoctorInfo;
use App\Http\Resources\Api\Hospital\HospitalInfo;
use App\Http\Resources\Api\Patient\PatientProfile;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentCallRequest extends JsonResource
{
    public function toArray($request)
    {
        return [
            'patient' => (new PatientProfile($this->whenLoaded('patient'))),
            'hospital' => (new HospitalInfo($this->whenLoaded('hospital'))),
            'doctor' => (new DoctorInfo($this->whenLoaded('doctor'))),
            'appointment_date' => Carbon::parse($this->appointment_date)->format('d-m-Y'),
            'appointment_time' => Carbon::parse($this->appointment_time)->format('h:i A'),
            'appointment_date_2' => Carbon::parse($this->appointment_date_2)->format('d-m-Y'),
            'appointment_time_2' => Carbon::parse($this->appointment_time_2)->format('h:i A')
        ];
    }
}
