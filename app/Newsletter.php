<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;

class Newsletter extends Model
{
    use LogsActivity;

    protected $fillable = [
        'email'
    ];

    protected static $logAttributes = [
        'email'
    ];

    public function store($request)
    {
        $validator = Validator::make($request->all(), ['email' => 'required|email|unique:newsletters']);

        if ($validator->fails()) {
            // dd($validator);
            return $validator;
        }


        $data = $request->all();

        $this->fill($data);
        $this->save();

        return $this;
    }
}
