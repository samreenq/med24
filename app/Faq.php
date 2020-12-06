<?php

namespace App;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Faq extends Model
{
    use LogsActivity;

    protected $fillable = [
        'question', 'answer', 'status', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'question', 'answer', 'status', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Faq has been {$eventName}";
    }

    public $validationRules = [
        'question' => 'required',
        'answer' => 'required'
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

        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        $data = $request->all();

        $data['status'] = $request->has('status') ? 1 : 0;
        $data['created_by'] = \Auth::user()->id;
        $data['updated_by'] = \Auth::user()->id;

        $this->fill($data);
        $this->save();

        return $this;
    }
}
