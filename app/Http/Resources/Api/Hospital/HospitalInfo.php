<?php
namespace App\Http\Resources\Api\Hospital;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Patient;

class HospitalInfo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $isFavourite = 0;
        $insurances = [];

        if($request->header('authorization')){
            $user = Patient::where('api_token', explode('Bearer ', $request->header('authorization'))[1])->first();

            if($user && count($this->patient_fav) > 0){
                foreach($this->patient_fav as $favouritePatient){
                    if($user->id == $favouritePatient->id){
                        $isFavourite = 1;
                    }
                }
            }
        }

        if(count($this->hospital_insurances)){
            foreach($this->hospital_insurances as $insurance){
                $insurances[$insurance->insurance->id] = [
                    'id' => $insurance->insurance->id,
                    'name' => $insurance->insurance->name,
                    'email' => $insurance->insurance->email ?? "",
                    'description' => $insurance->insurance->description ?? "",
                    'profilePhoto' => $insurance->insurance->profilePhoto ? asset('public/uploads/'.$insurance->insurance->profilePhoto) : "",
                    'coverPhoto' => $insurance->insurance->coverPhoto ? asset('public/uploads/'.$insurance->insurance->coverPhoto) : "",
                ];
            }
        }

        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email' => $this->email,
            'logo'=>asset('public/uploads/images/'.$this->logo),
            'opening_time' => $this->opening_time ? date('g:i A', strtotime($this->opening_time)) : '',
            'closing_time' => $this->closing_time ? date('g:i A', strtotime($this->closing_time)) : '',
            'is_favourite' => $isFavourite,
            'address'=>$this->address,
            'city' => $this->city->name ?? "",
            'country' => $this->country->name ?? "",
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'phone_no'=>$this->phone,
            'averageRating' => $this->averageRating,
            'insurances' => $insurances,
            'isRegistered' => ucfirst($this->isRegistered)
        ];
    }
}
