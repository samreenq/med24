<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Privacy extends Model{
    use LogsActivity;
    
    protected $table = 'privacy';

    protected $fillable = [
        'created_by', 
        'updated_by',
        'content',
        'type',
    ];

    protected static $logAttributes = [
        'created_by', 
        'updated_by',
        'content',
        'type',
    ];

    public function created_by(){
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by(){
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function store($request){
        $validationRules = [
            'content' => 'required',
            'type' => 'required|in:doctor,patient',
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