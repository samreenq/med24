<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FamilyMemberInfo extends JsonResource
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
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'relation'=>$this->relation,
            'id_card_front'=>isset($this->id_card_front)?asset('/public/uploads/images/'.$this->id_card_front):null,
            'id_card_back'=>$this->id_card_back?asset('/public/uploads/images/'.$this->id_card_back):null,
            'insurance_id_front'=>$this->insurance_id_front?asset('/public/uploads/images/'.$this->insurance_id_front):null,
            'insurance_id_back'=>$this->insurance_id_back?asset('/public/uploads/images/'.$this->insurance_id_back):null,
        ];
    }
}
