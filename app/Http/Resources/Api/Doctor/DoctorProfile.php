<?php
namespace App\Http\Resources\Api\Doctor;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use App\Patient;

class DoctorProfile extends JsonResource
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

        return[
            'id'=>$this->id,
            'first_name'=>$this->first_name,
            'last_name'=>$this->last_name,
            'email'=>$this->email,
            'starting_fee' => $this->startingFee,
            'is_favourite' => $isFavourite,
            'image'=>asset('public/uploads/images/'.$this->image),
            'rating'=> round($this->reviews->avg('rating'), 2),
            'about'=>$this->about,
            'care_philosophy'=>$this->care_philosophy,
            'summary'=>$this->summary,
            'biography'=>$this->biography,
            'specialities'=>Specialities::collection($this->specialities),
            'languages'=>$this->languages->map(function($q){
                return [
                    'id'=>$q->id,
                    'name'=>$q->name,
                ];
            }),
            'certifications'=>$this->certifications->map(function($q){
                return [
                    'id'=>$q->id,
                    'name'=>$q->name,
                ];
            }),
        ];
    }
}
