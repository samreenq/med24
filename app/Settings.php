<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Settings extends Model
{
    use LogsActivity;

    protected $table = 'settings';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'name', 'value', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Settings has been {$eventName}";
    }

    public static function get_value($name)
    {
    	$setting = Self::where('name', $name)->first();

    	if($setting) {
    		return $setting->value;
    	} else {
    		return '';
    	}
    }
}
