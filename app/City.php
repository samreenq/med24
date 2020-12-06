<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class City extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'cities';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'country_id', 'name', 'slug', 'created_by', 'updated_by'
    ];

    /*public $validationRules = [
        'name' => 'required|string|unique:cities',
        'slug' => 'required|string|unique:cities',
        'country_id' => 'required|exists:country,code'
    ];*/

    public function country()
    {
        return $this->belongsTo('App\Country');
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
        $validationRules = [
            'name' => 'required|string|unique:cities,name,'.$request->cityId,
            'slug' => 'required|string|unique:cities,slug,'.$request->cityId,
            'country_id' => 'required|exists:country,code',
        ];

        if( $validationRules ) {
            $validator = Validator::make($request->all(), $validationRules);

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

    public static function pluck_data($country_id = '')
    {
        if($country_id) {
            $cities = Self::where('country_id', $country_id)->pluck('name', 'id');
        } else {
            $cities = Self::pluck('name', 'id');
        }

        return $cities;
    }
}
