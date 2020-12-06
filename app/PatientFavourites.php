<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PatientFavourites extends Model
{
    //
    protected $table = 'patient_favourites';

    protected $fillable = [
        'patient_id','doctor_id','hospital_id','created_at','updated_at' ];

    public function store($request)
    {
        if(isset($request->patient_id)){
            $this->patient_id = $request->patient_id;
        }
        if(isset($request->doctor_id)){
            $this->doctor_id = $request->doctor_id;
        }
        if(isset($request->hospital_id)){
            $this->hospital_id = $request->hospital_id;
        }
        $this->save();
        return $this;
    }

}
