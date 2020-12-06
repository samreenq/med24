<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    protected $table='review_reply_likes';
    //
    protected $fillable = [
        'review_id','review_reply_id','patient_id','doctor_id','hospital_id'
        ];


    public function likeMe(){

    }
}
