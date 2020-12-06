<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Translatable\HasTranslations;

class Reply extends Model
{
    use HasTranslations;
    protected $table='review_replies';
    public $translatable = ['reply'];

    protected $fillable = [
        'review_id','reply','patient_id','doctor_id','hospital_id','status'
    ];

    //
    public function likes(){
        return $this->hasMany(Like::class,'review_reply_id','id');
    }
    public function doctor(){
        return $this->belongsTo(Doctor::class,'doctor_id','id');
    }
    public function review(){
        return $this->belongsTo(Review::class,'review_id','id');
    }
    public function patient(){
        return $this->belongsTo(Patient::class,'patient_id','id');
    }
    public function hospital(){
        return $this->belongsTo(Hospital::class,'hospital_id','id');
    }

    public function doctorLikes(){
        return $this->belongsToMany(Doctor::class,'review_reply_likes','review_reply_id','doctor_id');
    }
    public function patientLikes(){
        return $this->belongsToMany(Patient::class,'review_reply_likes','review_reply_id','patient_id');
    }



    public function scopeLikedByMe(){
        if(Auth::user() instanceof Doctor){
            return $this->doctorLikes()->find(Auth::user()->id)?1:0;
        }elseif(Auth::user() instanceof Patient){
            return $this->patientLikes()->find(Auth::user()->id)?1:0;
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
        } if(isset($request->review_id)){
            $this->review_id=$request->review_id;
        }
        $this->reply=$request->reply;
        $this->status=isset($request->status)?$request->status:1;
        $this->save();
    }
}
