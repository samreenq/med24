<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //
    protected $table='doctor_appointments';

    public function patient(){
        return $this->belongsTo(Patient::class,'patient_id','id');
    }

    public function hospital(){
        return $this->belongsTo(Hospital::class,'hospital_id','id');
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }

    function familyMember(){
        return $this->belongsTo(FamilyMember::class, 'family_member_id');
    }

    function insurance(){
        return $this->belongsTo(Insurance::class, 'insurance_id');
    }

    public function store($request){
        if(isset($request->doctor_id)){
            $this->doctor_id=$request->doctor_id;
        }

        if(isset($request->patient_id)){
            $this->patient_id=$request->patient_id;
        }

        if(isset($request->family_member_id)){
            $this->family_member_id=$request->family_member_id;
        }

        if(isset($request->hospital_id)){
            $this->hospital_id=$request->hospital_id;
        }

        if(isset($request->appointment_date)){
            $this->appointment_date=$request->appointment_date;
        }

        if(isset($request->appointment_time)){
            $this->appointment_time= date('H:i:s', strtotime($request->appointment_time));
        }

        if(isset($request->appointment_date_2)){
            $this->appointment_date_2 = $request->appointment_date_2;
        }

        if(isset($request->appointment_time_2)){
            $this->appointment_time_2 = date('H:i:s', strtotime($request->appointment_time_2));
        }

        if (isset($request->type)) 
        {
            $this->type = $request->type;
        }

        if(isset($request->insurance_id)){
            $this->insurance_id=$request->insurance_id;
        }

        if(isset($request->extraDetails)){
            $this->extraDetails = $request->extraDetails;
        }

        if(isset($request->status)){
            $this->status=$request->status;
        }

        if(isset($request->on_waiting)){
            $this->on_waiting=$request->on_waiting;
        }

        $this->save();
        return $this;
    }
}
