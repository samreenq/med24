<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Image;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use LogsActivity;

    use HasRoles;

    use Notifiable;

    use SoftDeletes;

    use HasApiTokens;

    protected $dates = ['deleted_at'];

    protected $guard = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'country_code', 'token_sent_on', 'phone', 'gender', 'password', 'dob', 'area', 'status', 'provider_id', 'social_provider', 'image', 'token','temp_pass', 'created_by', 'updated_by', 'country_id', 'city_id', 'last_reminder'
    ];

    protected static $logAttributes = [
        'first_name', 'last_name', 'email', 'phone', 'gender', 'password', 'dob', 'area', 'status', 'provider_id', 'social_provider', 'image', 'token', 'created_by', 'updated_by', 'country_id', 'city_id', 'last_reminder'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        return "User has been {$eventName}";
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $validationRules = [
        'first_name' => 'required|string',
        'last_name' => 'required|string',
        'gender' => 'required|string',
//        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'image' => 'base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=150,min_height=200',
        'password' => 'required|string',
        'user_type' => 'required|string',
        'country_id' => 'exists:country,id',
        'city_id' => 'exists:city,id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function gyms()
    {
        return $this->belongsToMany('App\Gym', 'user_gyms');
    }

    public function attributes()
    {
        return $this->hasMany('App\UserAttributes');
    }

    public function cards()
    {
        return $this->hasMany('App\UserCards');
    }

    public function favourites()
    {
        return $this->belongsToMany('App\Gym', 'user_favourites')->withTimestamps();
    }

    public function medical()
    {
        return $this->hasMany('App\UserMedical');
    }

    public function feedbacks()
    {
        return $this->hasMany('App\Feedbacks');
    }

    public function sessions()
    {
        return $this->hasMany('App\UserSessions');
    }

    public function city()
    {
        return $this->belongsTo('App\City');
    }

    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    public function devices()
    {
        return $this->hasMany('App\DeviceTokens');
    }

    public function store($request)
    {
        $mail = 1;
        if(isset($this->id)) {
            $this->validationRules['email'] = 'required|string|email|unique:users,email,'.$this->id;
            $this->validationRules['phone'] = 'required|numeric|unique:users,phone,'.$this->id;
            $this->validationRules['password'] = '';
			// $this->validationRules['image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
            $this->validationRules['image'] = 'base64image|base64mimes:jpeg,png,jpg,gif,svg|base64max:2048|base64dimensions:min_width=150,min_height=200';
            $mail = 0;
        }

        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $this->validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        $data = $request->all();

        if($request->image_type == "base64") {
            if($request->image) {
                if ($this->id) {
                    $this->deleteImage($this->image);
                }
                $data['image'] = $this->uploadbase64Image($request);
            } else {
                $data['image'] = $this->image;
            }
        } elseif($request->hasFile('image')) {
            if($this->id) {
                $this->deleteImage($this->image);
            }
			// dd($request->all());
            $imageName = $this->uploadImage($request);
            $data['image'] = $imageName;
        }

        $data['status'] = (isset($data['status']) && $data['status']) ? 1 : 0;

        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $this->password;
        }

        if($this->id) {
            $data['updated_by'] = \Auth::user()->id;
        } else {
            $data['created_by'] = \Auth::user()->id;
            $data['updated_by'] = \Auth::user()->id;
        }

        $this->fill($data);
        $this->save();

        $this->syncRoles((isset($data['roles'])) ? $data['roles'] : []);
        if($request->user_type == 'admin') {
            if(isset($data['permissions'])) {
                $data['permissions'][] = $request->user_type;
            } else {
                $data['permissions'] = $request->user_type;
            }
        } elseif($request->user_type == 'gym owner') {
            if(isset($data['permissions_gym'])) {
                $data['permissions'] = $data['permissions_gym'];
                $data['permissions'][] = $request->user_type;
            } else {
                $data['permissions'] = $request->user_type;
            }
        } elseif($request->user_type == 'branch manager') {
            $data['permissions'] = [$request->user_type, 'view sessions', 'scanout', 'view gyms', 'edit gym', 'view gym classes', 'create gym class', 'edit gym class', 'delete gym class'];
        } else {
            $data['permissions'] = [$request->user_type];
        }

        $this->syncPermissions((isset($data['permissions'])) ? $data['permissions'] : []);

        if($mail == 1) {
            $data['password'] = $request->password;
            Mail::send('emails.mail', $data, function($message) use ($data) {
                $message->to($data['email'], $data['first_name'])->subject('New User Signup | Train60');
                $message->from('admin@train60.com','Train60');
            });
        }

        return $this;
    }

    public function deleteUser()
    {
        $user = Self::find($this->id);
        $delete = $user->delete();
        $user->deleteImage($user->image);
        return 1;
    }

    public function updateStatus($status)
    {
        $user = Self::find($this->id);
        $user->status = $status;
        $user->save();
        return 1;
    }

    private static function deleteImage($image_name)
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

    // private static function uploadImage($request)
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

    public static function uploadImage($request)
    {
        if($request->hasFile('image')) {
            $originalImage= $request->file('image');
            $thumbnailImage = Image::make($originalImage);
            $originalPath = public_path().'/uploads/';
            $file_name = time().$originalImage->getClientOriginalName();
            $thumbnailImage->fit(500, 500);
            // $thumbnailImage->insert(public_path().'/assets/media/logos/my-logo.png', 'center', 10, 10)->save($originalPath.$file_name);
            $thumbnailImage->save($originalPath.$file_name);
            return $file_name;
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

    public function updateProfile($request)
    {
        if(isset($this->id)) {
            $validationRules['country_id'] = 'exists:country,id';
            $validationRules['city_id'] = 'exists:city,id';
        }

        if( $this->validationRules ) {
            $validator = Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return $validator;
            }
        }

        $data = $request->all();

        if($request->image_type == "base64") {
            if($this->id) {
                $this->deleteImage($this->image);
            }
            $data['image'] = $this->uploadbase64Image($request);
            // $data['image'] = $imageName;
        } else {
            if($request->hasFile('image')) {
                if($this->id) {
                    $this->deleteImage($this->image);
                }
                $data['image'] = $this->uploadImage($request);
                // $data['image'] = $imageName;
            }
        }

        $this->fill($data);
        $this->save();

        return $this;
    }

    public function is_complete()
    {
        $data = 0;

        if($this->first_name && $this->email && $this->phone && $this->gender && $this->image) {
            $data = 1;
        }

        return $data;
    }

    public static function pluck_data()
    {
        $data = [];
        $users = Self::where('status', 1)->pluck('first_name', 'id');
        foreach($users as $key => $row) {
            $data[$key] = $row . ' (' . $key . ')';
        }
        return $data;
    }

//    public function getMonthlySessionsCount($months)
//    {
//        $start_date = date('Y-m-1 00:00:00', strtotime($data['month_display'][$i]));
//        $end_date = date('Y-m-t 23:59:59', strtotime($data['month_display'][$i]));
//        $data['monthly_sessions_count'][] = $data['user']->sessions->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->count();
//        $gym_ids = $data['user']->sessions->unique('gym_id')->take(4);
//        $total_count = 0;
//        foreach($gym_ids as $row) {
//            $data['top_gyms']['gym_name'][] = $row->gym->name;
//            $data['top_gyms']['counts'][] = $data['user']->sessions->where('gym_id', $row->gym_id)->count();
//            $data['top_gyms']['amount'][] = $data['user']->sessions->where('gym_id', $row->gym_id)->sum('total_amount');
//            $total_count += $data['user']->sessions->where('gym_id', $row->gym_id)->count();
//        }
//        $data['top_gyms']['colours'] = ['success', 'danger', 'brand', 'warning'];
//        $data['top_gyms']['total_count'] = $total_count;
//    }

    public static function active_customers($user_id)
    {
        return $query = Self::permission('user')->where('status', 1)->count();
    }

    public static function getUserName($id)
    {
        $query = Self::find($id);
        if($query) {
            return $query->first_name . ' ' . $query->last_name;
        } else {
            return $id;
        }
    }
}
