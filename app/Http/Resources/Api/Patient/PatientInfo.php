<?php

namespace App\Http\Resources\Api\Patient;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientInfo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $totalScrore = 0;

        if ($this->first_name && $this->last_name && $this->email && $this->phone_code && $this->phone_no) 
        {
            $totalScrore += 25;
        }

        if (isset($this->medicalInfo) && count($this->medicalInfo->where('type', 'medical_condition')) > 0 && count($this->medicalInfo->where('type', 'health_record')) > 0)
        {
            $totalScrore += 25;
        }

        if ($this->insurance_id)
        {
            $totalScrore += 25;
        }

        if ($this->id_card_front && $this->id_card_back)
        {
            $totalScrore += 25;
        }

        return[
            'id'=>$this->id,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'image'=>asset('public/uploads/images/'.$this->image),
            'phone_no'=>$this->phone_no,
            'phone_code'=>$this->phone_code,
            'gender'=>$this->gender,
            'otp' => $this->otp,
            'insuranceId' => $this->insurance_id,
            'insuranceName' => $this->insurance->name ?? "",
            'insurancePackageId' => $this->insurance_package_id,
            'insurancePackageName' => $this->insurance_package->name ?? "",
            'otp' => $this->otp,
            'totalScrore' => $totalScrore
        ];
    }
}