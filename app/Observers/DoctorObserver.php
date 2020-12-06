<?php

namespace App\Observers;

use App\Doctor;
use App\Notifications\sendOTP;

class DoctorObserver
{
    //

    public function creating(Doctor $doctor){
        $doctor->otp=rand(1001,9999);
        $doctor->status=0;
        $doctor->verified_at=null;
        //$doctor->notify(new sendOTP($doctor));
    }
    public function updated(Doctor $doctor){
        if($doctor->isDirty('email')){
          $original_email= $doctor->getOriginal('email');
          if($original_email!=$doctor->email){
              $doctor->otp=rand(1001,9999);
              $doctor->status=0;
              $doctor->verified_at=null;
              //$doctor->notify(new sendOTP($doctor));
          }
        }
    }


}
