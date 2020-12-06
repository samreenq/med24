<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class UserMedical extends Model
{
    use LogsActivity;

    protected $table = 'user_medical';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'medical_conditions', 'medical_notes', 'allergies_reactions', 'medications', 'blood_type', 'weight', 'height'
    ];

    protected static $logAttributes = [
        'user_id', 'medical_conditions', 'medical_notes', 'allergies_reactions', 'medications', 'blood_type', 'weight', 'height'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "User Medical has been {$eventName}";
    }

    public $validationRules = [
        'user_id' => 'required|exists:users,id',
        'medical_conditions' => 'required|string',
        'medical_notes' => 'required|string',
        'allergies_reactions' => 'required|string',
        'medications' => 'required|string',
        'blood_type' => 'required|string',
        'weight' => 'required|string',
        'height' => 'required|string'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->dob)->age;
    }

    public function store($data = array())
    {
        if( $this->validationRules ) {
            $validator = Validator::make($data, $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        // $data['dob'] = date('Y-m-d', strtotime($data['dob']));

        $this->fill($data);
        $this->save();

        return $this;
    }

    public function is_complete()
    {
        $data = 1;
        $info = $this->fillable;
        foreach($info as $row) {
            if(!$this->$row) {
                $data = 0;
            }
        }
        return $data;
    }
}
