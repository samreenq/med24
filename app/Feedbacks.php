<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Feedbacks extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'feedbacks';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'full_name', 'email', 'phone', 'message'
    ];

    protected static $logAttributes = [
        'user_id', 'full_name', 'email', 'phone', 'message'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Feedback has been {$eventName}";
    }

    public $validationRules = [
        'user_id' => 'exists:users,id',
        'full_name' => 'required|string',
        'email' => 'required|email',
        'phone' => 'required',
        'message' => 'required',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
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

        $data = $request->all();

        $this->fill($data);
        $this->save();

        return $this;
    }
}
