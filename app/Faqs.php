<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Faqs extends Model{
    use LogsActivity;
    
    protected $table = 'faq';

    protected $fillable = [
        'created_by', 
        'updated_by',
        'faq',
        'type',
    ];

    protected static $logAttributes = [
        'created_by', 
        'updated_by',
        'faq',
        'type',
    ];

    public function created_by(){
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by(){
        return $this->belongsTo('App\User', 'updated_by');
    }
}