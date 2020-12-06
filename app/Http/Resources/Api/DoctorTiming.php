<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\Doctor\DoctorInfo;
use App\Http\Resources\Api\Hospital\HospitalInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorTiming extends JsonResource
{
    private  $current_status;
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */


    /**
     * @return array
     */
    public function __construct($resource, $current_status=0)
    {
        parent::__construct($resource);
        $this->resource = $resource;
        $this->current_status = $current_status;
    }

    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'from'=>$this->from,
            'to'=>$this->to,
            'day'=>$this->day,
            'doctor_id'=>$this->doctor_id,
            'hospital_id'=>$this->hospital_id,
            'doctor'=>(new DoctorInfo($this->whenLoaded('doctor',$this->doctor))),
            'hospital'=>(new HospitalInfo($this->whenLoaded('hospital',$this->hospital))),
//            'sta'=>$this->current_status
        ];
    }
}
