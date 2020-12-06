<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class GymClasses extends Model
{
    use LogsActivity;

    protected $table = 'gym_classes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gym_id', 'class_type', 'day', 'start_time', 'end_time', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'gym_id', 'class_type', 'day', 'start_time', 'end_time', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Gym Classes has been {$eventName}";
    }

    public $validationRules = [
        'gym_id' => 'exists:gym,id',
        'class_type' => 'required|string',
        'day' => 'required|string',
        'start_time' => 'required',
        'end_time' => 'required',
    ];

    public function gym()
    {
        return $this->belongsTo('App\Gym');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function store($request)
    {
        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        $request->request->add(['created_by' => \Auth::user()->id]);
        $request->request->add(['updated_by' => \Auth::user()->id]);

        $data = $request->all();

        $this->fill($data);
        $this->save();

        return $this;
    }
}
