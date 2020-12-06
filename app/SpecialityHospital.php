<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Spatie\Translatable\HasTranslations;

class SpecialityHospital extends Model
{
    use HasTranslations;
    
	protected $table = 'specialities_hospitals';
    
	protected $fillable = [
        'name', 'status', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'name', 'status', 'created_by', 'updated_by'
    ];
    
	public $translatable = ['name','address','description'];

    public $validationRules = [
        'name' => 'required|string|unique:specialities_hospitals'
    ];

    public function store($request)
    {
		if(isset($this->id)) {
            $this->validationRules['name'] = 'required|string|unique:specialities_hospitals,name,'.$this->id;
        }

        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        $data = $request->all();
		
		//dd($data);

        $data['status'] = $data['status'];
        $data['created_by'] = \Auth::user()->id;
        $data['updated_by'] = \Auth::user()->id;

        $this->fill($data);
        $this->save();

        return $this;
    }
}
