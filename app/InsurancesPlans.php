<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Spatie\Translatable\HasTranslations;
use Auth;

class InsurancesPlans extends Model{
	use HasTranslations;
    
	protected $fillable = [
        'created_by', 
        'updated_by',
        'insurance_id',
        'name', 
        'status',
    ];

    protected static $logAttributes = [
        'created_by', 
        'updated_by',
        'insurance_id',
        'name', 
        'status',
    ];
    
	public $translatable = [
        'name', 
    ];

    public $validationRules = [
        'name' => 'required|string',
    ];

    function store($request){
		$validationRules = [
            'name' => 'required|string',
        ];

        if($validationRules){
            $validator = Validator::make($request->all(), $validationRules);

            if($validator->fails()){
                return $validator;
            }
        }

        if(!$this->id){
            $this->created_by = Auth::user()->id;
        }

        $this->updated_by = Auth::user()->id;
        $this->insurance_id = $request->insuranceId;
        $this->name = $request->name;
        $this->status = $request->status ? true : false;

        $this->save();

        return $this;
    }

    function insurance(){
        return $this->belongsTo(Insurance::class, 'insurance_id');
    }
}