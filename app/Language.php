<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Language extends Model{
    protected $table = 'languages';

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'name', 
        'slug', 
        'status',
        'created_by', 
        'updated_by'
    ];

    function created_by(){
        return $this->belongsTo('App\User', 'created_by');
    }

    function updated_by(){
        return $this->belongsTo('App\User', 'updated_by');
    }

    function store($request){
    	$validationRules = [
            'name' => 'required|string|unique:languages,name,'.$this->id,
            //'status' => 'nullable|in:1',
        ];

        if($validationRules){
            $validator = Validator::make($request->all(), $validationRules);

            if($validator->fails()){
                return $validator;
            }
        }

        $data = $request->all();

        $data['created_by'] = \Auth::user()->id;
        $data['updated_by'] = \Auth::user()->id;

        $this->fill($data);

        $this->save();

        return $this;
    }
}