<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class GymAmenities extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'gym';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gym_id', 'amenities'
    ];

    protected static $logAttributes = [
        'gym_id', 'amenities'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Gym Amenities has been {$eventName}";
    }

    public function gym()
    {
        return $this->belongsTo('App\City');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }
}
