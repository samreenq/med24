<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Spatie\Translatable\HasTranslations;

class Speciality extends Model
{
    use HasTranslations;

	protected $fillable = [
        'name', 'status','image','created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'name', 'status', 'created_by', 'updated_by'
    ];

	public $translatable = ['name','address','description'];

    public $validationRules = [
        'name' => 'required|string|unique:specialities'
    ];

    public function store($request)
    {
		if(isset($this->id)) {
            $this->validationRules['name'] = 'required|string|unique:specialities,name,'.$this->id;
        }

        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        $data = $request->all();
        if($request->hasFile('image')){
            $data['image'] =$this->uploadFile($request,'image');
        }

		//dd($data);

        $data['status'] = $data['status'];
        $data['created_by'] = \Auth::user()->id;
        $data['updated_by'] = \Auth::user()->id;

       // echo '<pre>'; print_r($data); exit;
        $this->fill($data);
        $this->save();

        return $this;
    }

    public  function uploadFile($request,$image,$dir='images')
    {
        $uploadedImage = $request->file($image);
        $imageName = uniqid().'_'.time() . '.' . $uploadedImage->getClientOriginalExtension();
        $destinationPath = public_path('/uploads/'.$dir);
        $uploadedImage->move($destinationPath, $imageName);
        return $imageName;
    }
}
