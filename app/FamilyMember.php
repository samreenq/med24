<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FamilyMember extends Model
{
    use HasTranslations;
    //
    protected $table='family_members';

    protected $guarded=[];
    public $translatable = ['first_name','last_name','gender'];


    public function store($request){
        if(isset($request->first_name)) {
            $this->first_name = $request->first_name;
        }if(isset($request->last_name)) {
            $this->last_name = $request->last_name;
        }if(isset($request->gender)) {
            $this->gender = $request->gender;
        }if(isset($request->relation)) {
            $this->relation = $request->relation;
        }if(isset($request->patient_id)) {
            $this->patient_id = $request->patient_id;
        }if(isset($request->relation)) {
            $this->relation = $request->relation;
        }if($request->hasFile('id_card_front')){
            $this->id_card_front=$this->uploadFile($request,'id_card_front');
        }if($request->hasFile('id_card_back')){
            $this->id_card_back=$this->uploadFile($request,'id_card_back');
        }if($request->hasFile('insurance_id_front')){
            $this->insurance_id_front=$this->uploadFile($request,'insurance_id_front');
        }if($request->hasFile('insurance_id_back')){
            $this->insurance_id_back=$this->uploadFile($request,'insurance_id_back');
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

}
