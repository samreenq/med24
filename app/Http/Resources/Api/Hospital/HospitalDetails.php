<?php
namespace App\Http\Resources\Api\Hospital;
use App\Http\Resources\Api\Doctor\DoctorInfo;
use App\Http\Resources\Api\ReviewResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Patient;

class HospitalDetails extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        $certifications = [];
        $awards = [];
        $specialities = [];
        $insurances = [];

        if(count($this->certifications_hospitals)){
            foreach($this->certifications_hospitals as $certification){
                $certifications[] = [
                    'id' => $certification->id,
                    'name' => $certification->name,
                ];
            }
        }

        if(count($this->awards_hospitals)){
            foreach($this->awards_hospitals as $award){
                $awards[] = [
                    'id' => $award->id,
                    'name' => $award->name,
                ];
            }
        }

        if(count($this->specialities_hospitals)){
            foreach($this->specialities_hospitals as $speciality){
                $specialities[] = [
                    'id' => $speciality->id,
                    'name' => $speciality->name,
                ];
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
        
        $isFavourite = 0;

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

        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo' => asset('public/uploads/images/'.$this->logo),
            'address' => $this->address,
            'city' => $this->city->name ?? "",
            'country' => $this->country->name ?? "",
            'latitude'=>$this->latitude,
            'longitude'=>$this->longitude,
            'phone_no'=>$this->phone,
            'rating' => $this->averageRating,
            'opening_time' => $this->opening_time ? date('g:i A', strtotime($this->opening_time)) : '',
            'closing_time' => $this->closing_time ? date('g:i A', strtotime($this->closing_time)) : '',
            'affiliated_doctors' => $this->whenLoaded('doctors', DoctorInfo::collection($this->doctors)),
            'reviews' => $this->whenLoaded('reviews', ReviewResource::collection($this->reviews)),
            'certifications' => $certifications,
            'awards' => $awards,
            'specialities' => $specialities,
            'insurances' => $insurances,
            'is_favourite' => $isFavourite,
            'description' => $this->description,
            'isRegistered' => ucfirst($this->isRegistered),
            'isReviewed' => auth()->guard('api_patient')->user() && $this->reviews->where('hospital_id', $this->id)->where('patient_id', auth()->guard('api_patient')->user()->id)->first() ? true : false,
            'isAppointed' => auth()->guard('api_patient')->user() && $this->appointments->where('hospital_id', $this->id)->where('patient_id', auth()->guard('api_patient')->user()->id)->where('status', 'completed')->first() ? true : false
        ];
    }
}