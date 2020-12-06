<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Image;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Offers extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'offers';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $validationRules = [
        'name' => 'required|string|unique:offers',
        'slug' => 'required|string|unique:offers',
        'gym_id.*' => 'exists:gym,id',
        'discount' => 'required|integer',
        'discount_unit' => 'required',
        'start_datetime' => 'required',
        'end_datetime' => 'required',
//        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'image' => 'base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=900,min_height=600',
        'status' => 'required',
    ];

    protected $fillable = [
        'name', 'slug', 'discount', 'discount_unit', 'start_datetime', 'end_datetime', 'image', 'description', 'status', 'created_by', 'updated_by'
    ];

    protected static $logAttributes = [
        'name', 'slug', 'discount', 'discount_unit', 'start_datetime', 'end_datetime', 'image', 'description', 'status', 'created_by', 'updated_by'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "Offer has been {$eventName}";
    }

    public function hospital()
    {
        return $this->belongsToMany('App\Hospital', 'offer_hospitals', 'offer_id', 'hospital_id');
    }

    public function doctor()
    {
        return $this->belongsToMany('App\Doctor', 'offer_doctors', 'offer_id', 'doctor_id');
    }

    public function pharmacy()
    {
        return $this->belongsToMany('App\Pharmacy', 'offer_pharmacies', 'offer_id', 'pharmacy_id');
    }

    public function specialities()
    {
        return $this->belongsTo('App\Speciality', 'speciality_id');
    }

//    public function gym()
//    {
//        return $this->belongsTo('App\Gym');
//    }

    public function created_by()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updated_by()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public static function getOffer($id)
    {
        $offer = Offers::with('gym')->orderBy('id', 'desc')->where('start_datetime', '<=', date('Y-m-d H:i:s'))->where('end_datetime', '>=', date('Y-m-d H:i:s'))->where('status', 1)->find($id);

        return $offer;
    }

    public static function getOffers($gym_id = null)
    {
        $offers = Offers::with('gym')->orderBy('id', 'desc')->where('start_datetime', '<=', date('Y-m-d H:i:s'))->where('end_datetime', '>=', date('Y-m-d H:i:s'))->where('status', 1);

        if($gym_id) {
            $offers = $offers->whereHas('gym', function ($q) use($gym_id) {
                $q->where('gym_id', $gym_id);
            });
        } else {
            $offers = $offers->where('gym_id', 0);
        }

        return $offers->get();
    }

    public static function getAllOffers($gym_id = null)
    {
        $offers = Offers::with('gym')->orderBy('id', 'desc')->where('start_datetime', '<=', date('Y-m-d H:i:s'))->where('end_datetime', '>=', date('Y-m-d H:i:s'))->where('status', 1);

        if($gym_id) {
            $offers = $offers->whereHas('gym', function ($q) use($gym_id) {
                $q->where('gym_id', $gym_id);
            });
        }

        return $offers->get();
    }

    public function get_discount($price)
    {
        $discount = $this->discount;
        $discount_unit = $this->discount_unit;
        if($discount_unit == '%') {
            $discount = $price / 100 * $discount;
        }

        return $discount;
    }

    public function store($request)
    {
        if(isset($this->id)) {
            $this->validationRules['name'] = 'required|string|unique:offers,name,'.$this->id;
            $this->validationRules['slug'] = 'required|string|unique:offers,slug,'.$this->id;
//            $this->validationRules['image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            $this->validationRules['image'] = 'base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=900,min_height=600';
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

        if($request->image) {
            if ($this->id) {
                $this->deleteImage($this->image);
            }
            $data['image'] = $this->uploadbase64Image($request);
        } else {
            $data['image'] = $this->image;
        }

        $this->fill($data);
        $this->save();

        if(isset($data['hospital_id']) && $data['hospital_id']) {
            $this->hospital()->sync($data['hospital_id']);
        } else {
            $this->hospital()->sync([]);
        }

        if(isset($data['doctor_id']) && $data['doctor_id']) {
            $this->doctor()->sync($data['doctor_id']);
        } else {
            $this->doctor()->sync([]);
        }

        if(isset($data['pharmacy_id']) && $data['pharmacy_id']) {
            $this->pharmacy()->sync($data['pharmacy_id']);
        } else {
            $this->pharmacy()->sync([]);
        }

        return $this;
    }

    public function deleteOffer()
    {
        $offer = Self::find($this->id);
        $delete = $offer->delete();
        $offer->deleteImage($offer->image);
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

    //     if (!$request->hasFile('image'))
    //         return "";

    //     $image_file = $request->file('image');
    //     $image_name = uniqid() . $image_file->getClientOriginalName();
    //     $image_file->move(public_path('uploads'), $image_name);

    //     return $image_name;
    // }

    private function uploadImage($request)
    {
        if($request->hasFile('image')) {
            $originalImage= $request->file('image');
            $thumbnailImage = Image::make($originalImage);
            $originalPath = public_path().'/uploads/';
            $image_name = time().$originalImage->getClientOriginalName();
            $thumbnailImage->fit(1200, 800);
            $thumbnailImage->save($originalPath.$image_name);
            return $image_name;
        }
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

    public static function pluck_data()
    {
        $offers = Self::where('status', 1)->pluck('name', 'id');
        return $offers;
    }

    public static function total_active_offers()
    {
        return $query = Self::where('status', 1)->where('start_datetime', '<=', date('Y-m-d H:i:s'))->where('end_datetime', '>=', date('Y-m-d H:i:s'))->count();
    }

    public function getFeatured($limit = false)
    {
        $offers = $this->with('specialities')
            ->where('status',1)
            ->where('is_featured',1);
           //->offset(0)->limit(4)

       if($limit){
           $offers   = $offers->offset(0)->limit($limit);
       }

      return  $offers->get();


    }

    public function getOffersCategory()
    {
        /*$query = "SELECT specialities.* FROM offers
            LEFT JOIN specialities ON offers.speciality_id = specialities.id
            WHERE offers.is_featured = 1 AND offers.status = 1
            GROUP BY specialities.id LIMIT 0,5";

        $row = \DB::select($query);
        return isset($row[0]) ? $row : [];*/

        $rows = DB::table('offers')
            ->join('specialities', 'offers.speciality_id', '=', 'specialities.id')
            ->select( 'specialities.*')
             // ->where('offers.is_featured',1)
              ->where('offers.status',1)
            ->groupBy('specialities.id')
            ->offset(0)->limit(5)
            ->get();

        $results = array();
        if(count($rows)>0){
            foreach ($rows as $row){
                $result = $row;
                $name = json_decode($row->name);
                $result->name = ucfirst(strtolower($name->en));

                if(!empty($row->image)){
                    $result->image = asset('public/uploads/images/'.$row->image);
                }else{
                    $result->image = 'images/cate2.png';
                }

                $results[] = $result;

            }
        }
        return $results;
    }

    public function getLatest()
    {
        $offers = $this->where('status',1)
            ->orderBy('id','desc')
        ->offset(0)->limit(6);

        return  $offers->get();
    }

    public function getAll()
    {
       $offers = $this->where('status',1)
            ->orderBy('id','desc')
            ->get();

       return $offers;
    }
}
