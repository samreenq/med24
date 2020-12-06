<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class UserCards extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'user_cards';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'token', 'customer_email', 'customer_password', 'card_number'
    ];

    protected static $logAttributes = [
        'user_id', 'token', 'customer_email', 'customer_password', 'card_number'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "User Cards has been {$eventName}";
    }

    public $validationRules = [
        'user_id' => 'required|exists:users,id',
        'token' => 'required|string',
        'customer_email' => 'required|string',
        'customer_password' => 'required|string',
        'card_number' => 'required|numeric',
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

    public function store($data = array())
    {

        if( $this->validationRules ) {
            $validator = Validator::make($data, $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        $this->fill($data);
        $this->save();

        return $this;
    }
}
