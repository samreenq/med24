<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DoctorTiming extends Model
{
    //


    public function doctor(){
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }

    public function hospital(){
        return $this->belongsTo(Hospital::class,'hospital_id','id');
    }

    public function appointments(){
        return $this->hasMany(Appointment::class,'doctor_timing_id','id');
    }


}
