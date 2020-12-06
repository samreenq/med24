<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Country extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'country';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'code', 'phone_code', 'slug', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'name', 'code', 'phone_code', 'slug', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Country has been {$eventName}";
    }

    public $validationRules = [
        'name' => 'required|string|unique:country',
        'slug' => 'required|string|unique:country'
    ];

    public function cities()
    {
        return $this->hasMany('App\City', 'country_id', 'id');
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
        if(isset($this->id)) {
            $this->validationRules['name'] = 'required|string|unique:country,name,'.$this->id;
            $this->validationRules['slug'] = 'required|string|unique:country,slug,'.$this->id;
        }

        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        $data = $request->all();

        $data['created_by'] = \Auth::user()->id;
        $data['updated_by'] = \Auth::user()->id;

        $this->fill($data);
        $this->save();

        return $this;
    }

    public static function pluck_data()
    {
        $countries = Self::pluck('name', 'id');
        return $countries;
    }
}
