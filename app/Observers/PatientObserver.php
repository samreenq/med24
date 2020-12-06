<?php

namespace App\Observers;

use App\Doctor;
use App\Patient;
use App\Notifications\sendOTP;

class PatientObserver
{

    public function creating(Patient $patient){
        if($patient->provider_id==null){
            $patient->otp=rand(1001,9999);
            $patient->status=0;
            $patient->verified_at=null;
            //$patient->notify(new sendOTP($patient));
        }
    }


    public function updated(Patient $patient){
        if($patient->isDirty('email')){
            $original_email= $patient->getOriginal('email');
            if($original_email!=$patient->email){
                $patient->otp=rand(1001,9999);
                $patient->status=0;
                $patient->verified_at=null;
                //$patient->notify(new sendOTP($patient));
            }
        }
    }
}
