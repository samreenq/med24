<?php

namespace App\Http\Resources\Api;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OffersResource extends JsonResource
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
            'name'=>$this->name,
            'short_description'=>$this->short_description,
            'description'=>$this->description,
            'discount'=>$this->discount . $this->discount_unit,
            'thumb'=>asset('public/uploads/images/'.$this->thumb),
            'banner'=>asset('public/uploads/images/'.$this->banner),
            'speciality_id'=>$this->speciality_id,
            'speciality'=> $this->specialities ? $this->specialities->name : "",
            'code'=>$this->code
        ];
    }
}
