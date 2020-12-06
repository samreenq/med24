<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Translatable\HasTranslations;

class Review extends Model
{
    use HasTranslations;

    public $translatable = ['review'];

    public function doctor(){
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }

    public function patient(){
        return $this->belongsTo(Patient::class,'patient_id','id');
    }

    public function hospital(){
        return $this->belongsTo(Hospital::class,'hospital_id','id');
    }
    public function likes(){
        return $this->hasMany(Like::class,'review_id','id');
    }

    public function doctorLikes(){
        return $this->belongsToMany(Doctor::class,'review_reply_likes');
    }

    public function patient_likes(){
        return $this->belongsToMany(Patient::class,'review_reply_likes');
    }
    public function hospitalLikes(){
        return $this->belongsToMany(Hospital::class,'review_reply_likes');
    }

    public function replies(){
        return $this->hasMany(Reply::class, 'review_id');
    }

    public function scopeLikedByMe(){
        if (auth()->guard('api_doctor')->user())
        {
           return $this->doctorLikes()->find(auth()->guard('api_doctor')->user()->id) ? 1 : 0;
        }
        else if (auth()->guard('api_patient')->user())
        {   
            return $this->patient_likes()->find(auth()->guard('api_patient')->user()->id) ? 1 : 0;
        }

        return 0;
    }

    public function store($request){
        if(isset($request->doctor_id)){
            $this->doctor_id=$request->doctor_id;
        } if(isset($request->patient_id)){
            $this->patient_id=$request->patient_id;
        } if(isset($request->hospital_id)){
            $this->hospital_id=$request->hospital_id;
        } if(isset($request->doctor_id)){
            $this->doctor_id=$request->doctor_id;
        }
        $this->review=$request->review;
        $this->rating=$request->rating;
        $this->added_by=$request->added_by;
        $this->status=isset($request->status)?$request->status:1;
        $this->save();
        return $this;
    }
}