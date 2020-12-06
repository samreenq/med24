<?php

namespace App\Http\Resources\Api;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isLiked = 0;

        if (auth()->guard('api_doctor')->user())
        {
            $record = $this->likes->where('doctor_id', auth()->guard('api_doctor')->user()->id)
            ->where('id', $this->id)
            ->first();

            if ($record) 
            {
                $isLiked = 1;
            }
        }
        else if (auth()->guard('api_patient')->user())
        {   
            $record = $this->likes->where('patient_id', auth()->guard('api_patient')->user()->id)
            ->first();

            if ($record) 
            {
                $isLiked = 1;
            }
        }

        $data= [
            'id'=>$this->id,
            'review_id'=>$this->review_id,
            'reply'=>$this->reply,
            'date'=>Carbon::parse($this->created_at)->diffForHumans(),
            'total_likes'=>$this->likes->count(),
            'is_liked' => $isLiked
        ];
        if(isset($this->doctor)){
            $data['replier_name']=$this->doctor->first_name.' '.$this->doctor->last_name;
            $data['replier_image']= asset('public/uploads/images/'.$this->doctor->image);
        }elseif(isset($this->patient)){
            $data['replier_name']=$this->patient->first_name.' '.$this->patient->last_name;
            $data['replier_image']= asset('public/uploads/images/'.$this->patient->image);
        }elseif(isset($this->hospital)){
            $data['replier_image']= asset('public/uploads/images/'.$this->hospital->logo);
            $data['replier_name']=$this->hospital->name;
        }
        return  $data;
    }
}
