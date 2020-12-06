<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;

class Milestones extends Model
{
    use LogsActivity;

    protected $table = 'milestones';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'no_of_sessions', 'discount', 'discount_unit', 'expires_in_days', 'count', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'name', 'no_of_sessions', 'discount', 'discount_unit', 'expires_in_days', 'count', 'created_by', 'updated_by'
    ];

    public $validationRules = [
        'name' => 'required|string|unique:milestones',
        'no_of_sessions' => 'required|integer',
        'discount' => 'required|integer',
        'discount_unit' => 'required|string',
        'expires_in_days' => 'required|integer',
        'count' => 'required|integer'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Milestone has been {$eventName}";
    }

    public function store($request)
    {
        if(isset($this->id)) {
            $this->validationRules['name'] = 'required|string|unique:milestones,name,'.$this->id;
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
}
