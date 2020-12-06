<?php

namespace App\Http\Resources\Api;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data=  [
            'id'=>$this->id,
            'review'=>$this->review,
            'rating'=>$this->rating,
            'total_likes'=>$this->likes->count(),
            'is_liked'=>$this->likedByMe(),
            'date'=>Carbon::parse($this->created_at)->diffForHumans(),
        ];
        if($this->added_by=='Doctor'){
            $data['reviewer_name']=$this->doctor->first_name.' '.$this->doctor->last_name;
            $data['reviewer_image']= asset('public/uploads/images/'.$this->doctor->image);
            if($this->hospital_id){
                $data['reviewed_name']=$this->hospital->name;
                $data['reviewed_image']= asset('public/uploads/images/'.$this->hospital->logo);
            }elseif ($this->patient_id){
                $data['reviewed_name']=$this->patient->first_name.' '.$this->patient->last_name;
                $data['reviewed_image']= asset('public/uploads/images/'.$this->patient->image);
            }
        }elseif($this->added_by=='Patient' && isset($this->patient)){
            $data['reviewer_name']=$this->patient->first_name.' '.$this->patient->last_name;
            $data['reviewer_image']= asset('public/uploads/images/'.$this->patient->image);

            if($this->hospital_id && isset($this->hospital)){
                $data['reviewed_name']=$this->hospital->name;
                $data['reviewed_image']= asset('public/uploads/images/'.$this->hospital->logo);
            }elseif ($this->doctor_id){
                $data['reviewed_name']=$this->doctor->first_name.' '.$this->doctor->last_name;
                $data['reviewed_image']= asset('public/uploads/images/'.$this->doctor->image);
            }
        }elseif($this->added_by=='Hospital'){
            $data['reviewer_name']=$this->hospital->name;
            $data['reviewer_image']= asset('public/uploads/images/'.$this->hospital->logo);
            if($this->doctor_id){
                $data['reviewed_name']=$this->doctor->first_name.' '.$this->doctor->last_name;
                $data['reviewed_image']= asset('public/uploads/images/'.$this->doctor->image);
            }elseif ($this->patient_id){
                $data['reviewed_name']=$this->patient->first_name.' '.$this->patient->last_name;
                $data['reviewed_image']= asset('public/uploads/images/'.$this->patient->image);

            }
        }
        $data['replies']=ReplyResource::collection($this->whenLoaded('replies'));
        return  $data;
    }
}
