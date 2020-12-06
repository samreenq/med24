<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    //

    protected $table='options';


    public function store($request){
        if(isset($request->page)){
            $this->page=$request->page;
        }if(isset($request->option_name)){
            $this->option_name=$request->option_name;
        }if(isset($request->option_value)){
            $this->option_value=$request->option_value;
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

    public function updateValue($option_name,$option_value)
    {
        $this->where('option_name',$option_name)
            ->update(['option_value'=> $option_value]);

        return true;

    }
}
