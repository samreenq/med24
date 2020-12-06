<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Validator;
use Image;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Banners extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'banners';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $validationRules = [
        'module_type' => 'required|string',
        'start_date_time' => 'required',
        'end_date_time' => 'required',
//        'banner_img' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'banner_img' => 'required|base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=750,min_height=452',
    ];

    protected $fillable = [
        'module_id', 'module_type', 'banner_img', 'sequence', 'start_date_time', 'end_date_time', 'status', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'module_id', 'module_type', 'banner_img', 'sequence', 'start_date_time', 'end_date_time', 'status', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Banner has been {$eventName}";
    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public static function getBanners($module_id = null, $module_type = null)
    {
        $banners = Banners::orderBy('sequence', 'asc')->where('status', 1)->where('start_date_time', '<=', date('Y-m-d H:i:s'))->where('end_date_time', '>=', date('Y-m-d H:i:s'));

        if($module_type) {
            $banners = $banners->where('module_type', $module_type);
        }

        if($module_id) {
            $banners = $banners->where('module_id', $module_id);
        }

        return $banners->get();
    }

    public function getModuleName()
    {
        $module_name = '';

        if($this->module_type == 'gym') {
            $gym = Gym::find($this->module_id);
            if($gym) {
                $module_name = $gym->name;
            }
        } elseif($this->module_type == 'offers') {
            $offer = Offers::find($this->module_id);
            if($offer) {
                $module_name = $offer->name;
            }
        }

        return $module_name;
    }

    public function store($request)
    {
        if($this->id) {
//            $this->validationRules['banner_img'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            $this->validationRules['banner_img'] = 'base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=750,min_height=452';
        }
        if($request->module_type == 'gym') {
            $this->validationRules['gym_id'] = 'exists:gym,id';
        } elseif($request->module_type == 'offers') {
            $this->validationRules['offer_id'] = 'exists:offers,id';
        }
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

        if($request->banner_img) {
            if ($this->id) {
                $this->deleteImage($this->banner_img);
            }
            $data['banner_img'] = $this->uploadbase64Image($request);
        } else {
            $data['banner_img'] = $this->banner_img;
        }

//        if($request->banner_img) {
//            $imageName = $this->uploadImage($request);
//            $data['banner_img'] = $imageName;
//        }
        if($data['module_type'] == 'gym') {
            $data['module_id'] = $data['gym_id'];
        } elseif($data['module_type'] == 'offers') {
            $data['module_id'] = $data['offer_id'];
        } else {
            $data['module_id'] = 0;
        }
        $data['sequence'] = 0;
        $this->fill($data);
        $this->save();

        return $this;
    }

    public function deleteBanner()
    {
        $banner = Self::find($this->id);
        $delete = $banner->delete();
        $banner->deleteImage($banner->banner_img);
        return 1;
    }

    private function deleteImage($image_name)
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

    // private function uploadImage($request)
    // {
    //     $path = public_path('uploads');
    //     if (!file_exists($path))
    //         mkdir($path);

    //     if (!$request->hasFile('banner_img'))
    //         return "";

    //     $image_file = $request->file('banner_img');
    //     $image_name = uniqid() . $image_file->getClientOriginalName();
    //     $image_file->move(public_path('uploads'), $image_name);

    //     return $image_name;
    // }

    private function uploadImage($request)
    {
        if($request->hasFile('banner_img')) {
            $originalImage= $request->file('banner_img');
            $thumbnailImage = Image::make($originalImage);
            $originalPath = public_path().'/uploads/';
            $image_name = time().$originalImage->getClientOriginalName();
            $thumbnailImage->fit(750, 452);
            $thumbnailImage->save($originalPath.$image_name);
            return $image_name;
        }
    }

    private static function uploadbase64Image($request)
    {
        if (preg_match('/^data:image\/(\w+);base64,/', $request->banner_img, $type)) {
            $encoded_base64_image = substr($request->banner_img, strpos($request->banner_img, ',') + 1);
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
