<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class UserAttributes extends Model
{
    use LogsActivity;

    protected $table = 'user_attributes';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'key', 'value', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'user_id', 'key', 'value', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "User Attributes has been {$eventName}";
    }

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
}
