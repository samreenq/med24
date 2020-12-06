<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Offers;
use App\Vouchers;
use App\GymTimings;
use Image;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Gym extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'gym';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'slug', 'phone', 'city_id', 'country_id', 'parent_id', 'owner_id', 'branch_manager_id', 'price', 'additional_price', 'latitude', 'longitude', 'logo', 'banner', 'location', 'town', 'description', 'checkin_token', 'checkout_token', 'status', 'is_featured', 'is_membership', 'commission', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'name', 'slug', 'phone', 'city_id', 'country_id', 'parent_id', 'owner_id', 'branch_manager_id', 'price', 'additional_price', 'latitude', 'longitude', 'logo', 'banner', 'location', 'town', 'description', 'checkin_token', 'checkout_token', 'status', 'is_featured', 'is_membership', 'commission', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Gym has been {$eventName}";
    }

    public function amenities()
    {
        return $this->belongsToMany('App\Amenities', 'gym_amenities');
    }

    public $validationRules = [
        'name' => 'required|string|unique:city',
        'slug' => 'required|string|unique:city',
        'country_id' => 'required|exists:country,id',
        'city_id' => 'required|exists:city,id',
        'owner_id' => 'required|exists:users,id',
        'branch_manager_id' => 'required|exists:users,id',
//        'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//        'banner' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'logo' => 'required|base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=200,min_height=200',
        'banner' => 'required|base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=1200,min_height=850',
        'price' => 'required|integer',
        'additional_price' => 'required|integer',
        'phone' => 'required|string',
        'town' => 'required|string',
        'location' => 'required|string',
        'latitude' => 'required|string',
        'longitude' => 'required|string',
    ];

    public function branches()
    {
        return $this->hasMany('App\Gym', 'parent_id', 'id');
    }

    public function vendors()
    {
        return $this->belongsToMany('App\User', 'user_gyms');
    }

    public function ratings()
    {
        return $this->belongsToMany('App\User', 'user_ratings')->withPivot('ratings')->withTimestamps();
    }

    public function favourites()
    {
        return $this->belongsToMany('App\User', 'user_favourites')->withTimestamps();
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function timings()
    {
        return $this->hasMany('App\GymTimings');
    }

    public function classes()
    {
        return $this->hasMany('App\GymClasses');
    }

    public function images()
    {
        return $this->hasMany('App\GymImages');
    }

    public function offers()
    {
        return $this->belongsToMany('App\Offers', 'offer_gyms', 'gym_id', 'offer_id');
    }

//    public function offers()
//    {
//        return $this->hasMany('App\Offers', 'gym_id');
//    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updated_by');
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

    // private function uploadImage($request, $file_name)
    // {
    //     $path = public_path('uploads');
    //     if (!file_exists($path))
    //         mkdir($path);

    //     if (!$request->hasFile($file_name))
    //         return "";

    //     $image_file = $request->file($file_name);
    //     $image_name = uniqid() . $image_file->getClientOriginalName();
    //     $image_file->move(public_path('uploads'), $image_name);

    //     return $image_name;
    // }

    private function uploadImage($request, $file_name, $width, $height)
    {
        if($request->hasFile($file_name)) {
            $originalImage= $request->file($file_name);
            $thumbnailImage = Image::make($originalImage);
            $originalPath = public_path().'/uploads/';
            $image_name = time().$originalImage->getClientOriginalName();
            $thumbnailImage->fit($width, $height);
            $thumbnailImage->save($originalPath.$image_name);
            return $image_name;
        }
    }

    private static function uploadbase64Image($request, $file_name)
    {
        if($file_name == 'logo') {
            $file_name = $request->logo;
        } else {
            $file_name = $request->banner;
        }
        if (preg_match('/^data:image\/(\w+);base64,/', $file_name, $type)) {
            $encoded_base64_image = substr($file_name, strpos($file_name, ',') + 1);
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

    public function deleteTokenImage($image_name)
    {
        if($image_name == null)
            return false;

        $path = public_path('uploads/gym'."/$image_name");
        if(file_exists($path))
        {
            unlink($path);
            return true;
        }
        return false;
    }

    public function regenerateToken($request)
    {
        if($request->has('checkin_token')) {
            $this->deleteTokenImage($this->checkin_token . '.png');
            $checkin_token = (string) Str::uuid();
            $data['checkin_token'] = $checkin_token;
            \QrCode::format('png')->size(500)->generate($data['checkin_token'], public_path('uploads/gym/').$checkin_token.'.png');
        } elseif ($request->has('checkout_token')) {
            $this->deleteTokenImage($this->checkout_token . '.png');
            $checkout_token = (string) Str::uuid();
            $data['checkout_token'] = $checkout_token;
            \QrCode::format('png')->size(500)->generate($data['checkout_token'], public_path('uploads/gym/').$checkout_token.'.png');
        }

        if(isset($data) && $data) {
            $this->fill($data);
            $this->save();
            return true;
        }
        return false;
    }

    public function store($request)
    {
        if(isset($this->id)) {
            $this->validationRules['name'] = 'required|string|unique:city,name,'.$this->id;
            $this->validationRules['slug'] = 'required|string|unique:city,slug,'.$this->id;
            $this->validationRules['banner'] = 'base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=1200,min_height=850';
            $this->validationRules['logo'] = 'base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=200,min_height=200';
        }

        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        $logo = '';
        $banner = '';

        $data = $request->all();

        if($request->logo) {
            if($this->id) {
                $logo = $this->deleteImage($this->logo);
            }
            $logo = $this->uploadbase64Image($request, 'logo');
            $data['logo'] = $logo;
        } else {
            $data['logo'] = $this->logo;
        }

        if($request->banner) {
        if($this->id) {
            $banner = $this->deleteImage($this->banner);
        }
        $banner = $this->uploadbase64Image($request, 'banner');
        $data['banner'] = $banner;
    } else {
        $data['banner'] = $this->banner;
    }

        if(!$request->has('owner_id')) {
            $data['owner_id'] = \Auth::user()->id;
        }

        if(!$request->has('branch_manager_id')) {
            $data['branch_manager_id'] = \Auth::user()->id;
        }

        $data['created_by'] = \Auth::user()->id;
        $data['updated_by'] = \Auth::user()->id;

        if(!$this->id) {
            $checkin_token = (string) Str::uuid();
            $checkout_token = (string) Str::uuid();
            $data['checkin_token'] = $checkin_token;
            $data['checkout_token'] = $checkout_token;
            \QrCode::format('png')->size(500)->generate($data['checkin_token'], public_path('uploads/gym/').$checkin_token.'.png');
            \QrCode::format('png')->size(500)->generate($data['checkout_token'], public_path('uploads/gym/').$checkout_token.'.png');
        }

        if(!isset($data['is_featured'])) {
            $data['is_featured'] = 0;
        }

        if(!isset($data['is_membership'])) {
            $data['is_membership'] = 0;
        }

        $this->fill($data);
        $this->save();

        $amenities = $this->amenities()->detach();

        foreach($data['amenities'] as $amenity) {
            $amenities = $this->amenities()->attach($amenity);
        }

        foreach($data['day'] as $key => $day) {
            if(isset($data['is_24hour'][$key])) {
                $data['open_time'][$key] = '0:00:00';
                $data['close_time'][$key] = '0:00:00';
            }
            if(isset($data['open_time'][$key]) && isset($data['close_time'][$key])) {
                $timing = GymTimings::where('gym_id', $this->id)->where('day', $day)->first();

                if(!$timing) {
                    $timing = new GymTimings;
                    $timing->gym_id = $this->id;
                }

                $timing->day = $day;
                $timing->open_time = $data['open_time'][$key];
                $timing->close_time = $data['close_time'][$key];
                $timing->is_24hour = (isset($data['is_24hour'][$key])) ? 1 : 0;
                $timing->type = $data['type'][$key];
                $timing->created_by = \Auth::user()->id;
                $timing->updated_by = \Auth::user()->id;

                $timing->save();
            }
        }

        return $this;
    }

    // API Functions

    public static function pluck_data()
    {
        if(\Auth::user()->can('admin')) {
            $gym = Self::where('status', 1)->pluck('name', 'id');
        } else {
            $gym = Self::where('owner_id', \Auth::user()->id)->where('status', 1)->pluck('name', 'id');
        }
        return $gym;
    }

    public static function nearby($lat, $long, $distance)
    {
        $location = Gym::with('ratings', 'vendors', 'amenities', 'offers')
        ->select('*', DB::raw(sprintf(
            '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
            $lat,
            $long
        )))
        ->having('distance', '<', $distance)
        ->orderBy('distance', 'asc')
        ->where('status', 1)
        ->get();

        return $location;
    }

    public static function getDistance($lat, $long, $gym_id)
    {
        $location = Gym::with('ratings')
        ->select('*', DB::raw(sprintf(
            '(6371 * acos(cos(radians(%1$.7f)) * cos(radians(latitude)) * cos(radians(longitude) - radians(%2$.7f)) + sin(radians(%1$.7f)) * sin(radians(latitude)))) AS distance',
            $lat,
            $long
        )))
        ->where('id', $gym_id)
        ->where('status', 1)
        ->first();

        if($location) {
            return $location->distance;
        } else {
            return false;
        }
    }

    public static function get_all_listings($limit = null, $paginate = null, $featured = 0, $lat = 0, $long = 0)
    {
        $gym = Gym::with('ratings', 'vendors', 'amenities', 'offers', 'classes', 'timings', 'country', 'city')->orderBy('id', 'desc')->where('status', 1);

        if($featured) {
            $gym = $gym->where('is_featured', 1);
        }

        if($limit) {
            $gym = $gym->limit($limit);
        }

        if($paginate) {
            $gym = $gym->paginate($paginate);
        } else {
            $gym = $gym->get();
        }

        return $gym;
    }

    public static function search_listings($query)
    {
        $gym = Gym::with('ratings', 'vendors', 'amenities', 'offers', 'classes', 'timings', 'country', 'city')->where('name', 'like', '%' . $query . '%')->orderBy('id', 'desc')->where('status', 1)->get();

        return $gym;
    }

    public static function locations()
    {
        $gym = Gym::with('ratings', 'vendors', 'amenities', 'offers', 'classes', 'timings', 'country', 'city')->orderBy('id', 'desc')->where('status', 1)->get();

        return $gym;
    }

    public static function get_all_recommended($city_name = null, $country_name = null)
    {
        $gym = Gym::with('city', 'country')->where('is_featured', 1)->orderBy('created_at', 'desc')->where('status', 1);

        if($city_name) {

            $city = \App\City::where('name', $city_name)->first();

            $country = \App\Country::where('name', $country_name)->first();

            if($city && $country && $city->country_id == $country->id) {
                $gym = $gym->where('city_id', $city->id);

                if($country) {
                    $gym = $gym->where('country_id', $country->id);
                }
            }
        }

        $gym = $gym->get();

        if(!$gym) {
            $gym = Gym::with('city', 'country')->orderBy('created_at', 'desc')->where('is_featured', '1')->get();
        }

        return $gym;
    }

    public static function detail($gym_id)
    {
        $gym = Gym::with('vendors', 'amenities', 'offers', 'classes', 'timings', 'country', 'city', 'favourites', 'images')->orderBy('id', 'desc')->where('status', '1')->find($gym_id);

        if($gym) {
            $ratings = DB::table('user_ratings')->where('gym_id', $gym_id)->avg('ratings');

            $gym['ratings'] = $ratings;
        }

        return $gym;
    }

    public function get_price($code = '', $user_id = '')
    {
        $offers = Offers::getOffers($this->id);
        $voucher = Vouchers::check_voucher($code, $this->id, $user_id);

        if(count($offers) > 0) {
            $discount = $offers[0]->get_discount($this->price);

            // $price = round($this->price - $discount);
            $price = $this->price - $discount;
        } elseif($voucher) {
            $discount = $voucher->get_discount($this->price);
            // $price = round($this->price - $discount);
            $price = $this->price - $discount;
        } else {
            $price = $this->price;
        }

        return $this->get_currency($price);
    }

    public function get_additional_price($code = '', $user_id = '')
    {
        $offers = Offers::getOffers($this->id);
        $voucher = Vouchers::check_voucher($code, $this->id, $user_id);

        if(count($offers) > 0) {
            $discount = $offers[0]->get_discount($this->additional_price);

            // $additional_price = round($this->additional_price - $discount);
            $additional_price = $this->additional_price - $discount;
        } elseif($voucher) {
            $discount = $voucher->get_discount($this->additional_price);
            // $additional_price = round($this->additional_price - $discount);
            $additional_price = $this->additional_price - $discount;
        } else {
            $additional_price = $this->additional_price;
        }

        return $this->get_currency($additional_price);
    }

    public function get_status()
    {
        $gym_id = $this->id;
        $gym = self::with(['timings' => function($q){
            $q->where('day', date('l'));
        }])->find($gym_id);
        
        $status = 'Closed';

        if($gym && count($gym->timings) > 0 && $gym->timings[0]->is_24hour) {
            return $status = 'Open';
        }

        if(count($gym->timings) <= 0) {
            return $status = 'Closed';
        }

        $current = \Carbon::parse(date('H:i:s'));
        $open = \Carbon::parse($gym->timings[0]->open_time);
        $close = \Carbon::parse($gym->timings[0]->close_time);

        if($gym->timings[0]->open_time <= date('H:i:s') && $gym->timings[0]->close_time >= date('H:i:s')) {
            $duration_close = $current->diffInSeconds($close);
            $diff_close = gmdate($duration_close);
            if($diff_close <= 3600) {
                $status = 'Closing Soon';
            } else {
                $status = 'Open';
            }
        } elseif($gym->timings[0]->open_time >= date('H:i:s') && $gym->timings[0]->close_time >= date('H:i:s')) {
            $duration_open = $current->diffInSeconds($open);
            $diff_open = gmdate($duration_open);
            if($diff_open <= 3600) {
                $status = 'Opening Soon';
            } else {
                $status = 'Closed';
            }
        }

        return $status;

    }

    public function get_currency($price)
    {
        $setting = Settings::where('name', 'currency')->first();

        if($setting) {
            $currency = $setting->value;
        } else {
            $currency = "AED";
        }

        return $currency . ' ' . $price;
    }

    public static function total_gyms_registered()
    {
        return $query = Self::where('status', 1)->where('parent_id', null)->count();
    }
}
