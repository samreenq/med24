<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Amenities extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'status', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'name', 'slug', 'status', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Amenity has been {$eventName}";
    }

    public $validationRules = [
        'name' => 'required|string|unique:amenities',
        'slug' => 'required|string|unique:amenities'
    ];

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
            $this->validationRules['name'] = 'required|string|unique:amenities,name,'.$this->id;
            $this->validationRules['slug'] = 'required|string|unique:amenities,slug,'.$this->id;
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

    public function deleteAmenity()
    {
        $amenity = Self::find($this->id);
        $delete = $amenity->delete();
        return 1;
    }

    public static function pluck_data()
    {
        $amenities = Self::where('status', 1)->pluck('name', 'id');
        return $amenities;
    }
}
