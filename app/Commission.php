<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Commission extends Model
{
    use LogsActivity;

    protected $fillable = [
        'gym_id', 'session_id', 'total_amount', 'commission_percent', 'commission_amount'
    ];

    protected static $logAttributes = [
        'gym_id', 'session_id', 'total_amount', 'commission_percent', 'commission_amount'
    ];

    public function session()
    {
        return $this->belongsTo('App\UserSessions', 'session_id');
    }

    public function gym()
    {
        return $this->belongsTo('App\Gym', 'gym_id');
    }
}
