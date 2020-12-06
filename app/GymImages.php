<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Image;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class GymImages extends Model
{
    use LogsActivity;

    protected $table = 'gym_images';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gym_id', 'image', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'gym_id', 'image', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Gym Images has been {$eventName}";
    }

    public $validationRules = [
        'gym_id' => 'exists:gym,id',
        'image' => 'required|base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=1200,min_height=850',
    ];

    public function gym()
    {
        return $this->belongsTo('App\Gym');
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
        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }


        $request->request->add(['created_by' => \Auth::user()->id]);
        $request->request->add(['updated_by' => \Auth::user()->id]);

        $data = $request->all();

        $data['image'] = $this->uploadbase64Image($request);
        $this->fill($data);
        $this->save();

        return $this;
    }

    public function deleteImage()
    {
        $image = Self::find($this->id);
        $delete = $image->delete();
        $image->deleteFile($image->image);
        return 1;
    }

    private static function deleteFile($image_name)
    {
        if($image_name == null)
            return false;

        $path = public_path('uploads'."/$image_name");
        if(file_exists($path))
        {
            unlink($path);
            return true;
        }
        return false;
    }

    private static function uploadbase64Image($request)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $request->image, $type)) {
            $encoded_base64_image = substr($request->image, strpos($request->image, ',') + 1);
            $type = strtolower($type[1]);

            $decoded_image = base64_decode($encoded_base64_image);

            $resized_image = Image::make($decoded_image);
            $path = public_path('uploads');
            if (!file_exists($path))
                mkdir($path);

            $image_name = uniqid().'.'.'png';
            \File::put(public_path('uploads') . '/' . $image_name,(string) $resized_image->encode());

            return $image_name;
        }
    }
}
