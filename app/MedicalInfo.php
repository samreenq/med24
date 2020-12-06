<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalInfo extends Model
{
    //

    protected $table='patient_medical_info';


    public function store($request){
        if(isset($request->title)){
            $this->title=$request->title;
        }if(isset($request->description)){
            $this->description=$request->description;
        }if(isset($request->type)){
            $this->type=$request->type;
        }if(isset($request->patient_id)){
            $this->patient_id=$request->patient_id;
        }if($request->hasFile('attachment')){
            $this->attachment=$this->uploadFile($request,'attachment');
        }
        $this->save();
        return $this;
    }


    public  function uploadFile($request,$image,$dir='images')
    {
        $uploadedImage = $request->file($image);
        $imageName = time() . '.' . $uploadedImage->getClientOriginalExtension();
        $destinationPath = public_path('/uploads/'.$dir);
        $uploadedImage->move($destinationPath, $imageName);
        return $imageName;
    }

    function patient(){
        return $this->belongsTo('App\Patient', 'patient_id');
    }
}
