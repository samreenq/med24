<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;
use Auth;

class Relation extends Model
{
	use LogsActivity;
	use SoftDeletes;
	
	protected static $logAttributes = [
        'created_by', 
        'updated_by', 
        'name', 
        'status'
    ];

    public function store($request)
    {
    	$request->validate([
			'name' => 'required|max:30',
			'status' => 'nullable|in:on'
		]);

    	$record = $this->find($request->id);

		if (!$record)
		{
			$record = new $this;
			$record->created_by = Auth::user()->id;
		}

		$record->updated_by = Auth::user()->id;
		$record->name = ucfirst($request->name);
		$record->status = isset($request->status) ? 1 : 0;

		return $record->save();
    }
}