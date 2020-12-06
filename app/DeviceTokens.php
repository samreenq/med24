<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class DeviceTokens extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'device_tokens';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'device_token', 'device_type'
    ];

    protected static $logAttributes = [
        'user_id', 'device_token', 'device_type'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Device Token has been {$eventName}";
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getUserToken($user_id = null)
    {
        $token = Self::query();

        if($user_id) {
            $token = $token->where('user_id', $user_id);
        }

        return $token->first();
    }

    public static function getTokens($device_type = null)
    {
        $tokens = Self::query();

        if($device_type) {
            $tokens = $tokens->where('device_type', $device_type);
        }

        return $tokens->get();
    }
}
