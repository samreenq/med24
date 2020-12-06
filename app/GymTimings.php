<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class GymTimings extends Model
{
    use LogsActivity;

    protected $table = 'gym_timings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gym_id', 'day', 'open_time', 'close_time', 'is_24hour', 'type', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'gym_id', 'day', 'open_time', 'close_time', 'is_24hour', 'type', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Gym Timings has been {$eventName}";
    }

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
}
