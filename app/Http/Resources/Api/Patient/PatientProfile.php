<?php

namespace App\Http\Resources\Api\Patient;

use Illuminate\Http\Resources\Json\JsonResource;

class PatientProfile extends JsonResource
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

        return [
            'id'=>$this->id,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'image'=>asset('public/uploads/images/'.$this->image),
            'gender'=>$this->gender,
            'id_card_front'=>isset($this->id_card_front)?asset('/public/uploads/images/'.$this->id_card_front):null,
            'id_card_back'=>$this->id_card_back?asset('/public/uploads/images/'.$this->id_card_back):null,
            'insurance_id_front'=>$this->insurance_id_front?asset('/public/uploads/images/'.$this->insurance_id_front):null,
            'insurance_id_back'=>$this->insurance_id_back?asset('/public/uploads/images/'.$this->insurance_id_back):null,
            'phone_no'=>$this->phone_no,
            'phone_code'=>$this->phone_code,
            'insuranceId' => $this->insurance_id,
            'insuranceName' => $this->insurance->name ?? "",
            'insurancePackageId' => $this->insurance_package_id,
            'insurancePackageName' => $this->insurance_package->name ?? "",
            'otp' => $this->otp,
            'totalScrore' => $totalScrore
        ];
    }
}
