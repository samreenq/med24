<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
use Spatie\Translatable\HasTranslations;

class Hospital extends Authenticatable
{
    use LogsActivity;

    use HasRoles;

    use Notifiable;

    use SoftDeletes;

    use HasApiTokens;

    use HasTranslations;

    protected $dates = ['deleted_at'];

    protected $appends = [
        'AverageRating',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'status', 'logo', 'image', 'token', 'created_by', 'updated_by', 'address', 'description', 'country_id', 'city_id', 'insurance_id'
    ];


    public $translatable = ['name','address','description','address'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
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

    public function doctors(){
        return $this->belongsToMany(Doctor::class,'doctor_hospitals','hospital_id','doctor_id');
    }

    public function reviews(){
        return $this->hasMany(Review::class,'hospital_id','id');
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

    public static function uploadImage($request, $name)
    {
        if($request->hasFile($name)) {
            $originalImage= $request->file($name);
            $thumbnailImage = Image::make($originalImage);
            $originalPath = public_path().'/uploads/';
            $file_name = time().$originalImage->getClientOriginalName();
            $thumbnailImage->fit(500, 500);
            $thumbnailImage->save($originalPath.$file_name);
            return $file_name;
        }
    }

    public function store($request){
        $request->validate([
            'email' => 'required|email|unique:hospitals,email,'.$this->id,
            'phone' => 'required|unique:hospitals,phone,'.$this->id,
            'image' => 'nullable|mimes:jpeg,jpg,png|max:1000',
            'logo' => 'nullable|mimes:jpeg,jpg,png|max:1000',
        ]);

        if(isset($request->name)) {
           $this->name = $request->name;
        }if(isset($request->email)) {
            $this->email = $request->email;
        }if(isset($request->password)){
            $this->password=bcrypt($request->password);
        }if(isset($request->city_id)){
            $this->city_id=$request->city_id;
        }if(isset($request->country_id)){
            $this->country_id=$request->country_id;
        }if(isset($request->phone)){
            $this->phone=$request->phone;
        }if(isset($request->address)){
            $this->address=$request->address;
        }if(isset($request->description)){
            $this->description=$request->description;
        }if(isset($request->status)){
            $this->status=$request->status;
        }

        if(isset($request->isFeatured)){
            $this->isFeatured = $request->isFeatured;
        }

        if (isset($request->isRegistered))
        {
            $this->isRegistered = $request->isRegistered;
        }

        if(isset($request->openingTime)){
            $this->opening_time = date('H:i', strtotime($request->openingTime));
        }

        if(isset($request->closingTime)){
            $this->closing_time = date('H:i', strtotime($request->closingTime));
        }

        if(isset($request->latitude)){
            $this->latitude = $request->latitude;
        }

        if(isset($request->longitude)){
            $this->longitude = $request->longitude;
        }

        if($request->hasFile('image'))
        {
            if($this->image!='hospital.png'){
                $this->deleteImage($this->image);
            }
            $this->image=$this->uploadFile($request, 'image');
        }

        if($request->hasFile('logo'))
        {
            if($this->logo!='logo.png'){
                $this->deleteImage($this->logo);
            }
            $this->logo=$this->uploadFile($request, 'logo');
        }

        $this->save();

        if(isset($request->specialities_hospitals)){
            $specialityes_hospitals=SpecialityHospital::whereIn('id',$request->specialities_hospitals)->get()->pluck('id')->toArray();
            if($specialityes_hospitals){
                $this->specialities_hospitals()->sync($specialityes_hospitals);
            }
        }

        if(isset($request->certifications_hospitals)){
            $certifications_hospitals=CertificationHospital::whereIn('id',$request->certifications_hospitals)->get()->pluck('id')->toArray();
            if($certifications_hospitals){
                $this->certifications_hospitals()->sync($certifications_hospitals);
            }
        }

        if(isset($request->awards_hospitals)){
            $awards_hospitals=AwardHospital::whereIn('id',$request->awards_hospitals)->get()->pluck('id')->toArray();

            if($awards_hospitals){
                $this->awards_hospitals()->sync($awards_hospitals);
            }
        }

        if(isset($request->hospital_insurances)){
            $hospital_insurances = InsurancesPlans::whereIn('id', $request->hospital_insurances)->get()->pluck('id')->toArray();

            if($hospital_insurances){
                $this->hospital_insurances()->sync($hospital_insurances);
            }
        }

        return $this;
    }

    public  function deleteImage($image_name)
    {
        if($image_name == null)
            return false;
        $path = public_path('uploads/hospitals/'.$image_name);
        if(file_exists($path))
        {
            unlink($path);
            return true;
        }
        return false;
    }

    public  function uploadFile($request,$name)
    {
        $uploadedImage = $request->file($name);
        $imageName = time().'-'.rand() . '.' . $uploadedImage->getClientOriginalExtension();
        $hospitalsinationPath = public_path('/uploads/hospitals/');
        $uploadedImage->move($hospitalsinationPath, $imageName);
        return $imageName;
    }

    public function specialities_hospitals(){
        return $this->belongsToMany(SpecialityHospital::class,'hospital_specialities','hospital_id','speciality_hospital_id');
    }

    public function certifications_hospitals(){
        return $this->belongsToMany(CertificationHospital::class,'hospital_certifications','hospital_id','certification_hospital_id');
    }

    public function awards_hospitals(){
        return $this->belongsToMany(AwardHospital::class,'hospital_awards','hospital_id','award_hospital_id');

    }

    public function hospital_insurances(){
        return $this->belongsToMany(InsurancesPlans::class, 'hospital_insurances', 'hospital_id', 'insurance_id');

    }

    public function patient_fav(){
        return $this->belongsToMany(Patient::class,'patient_favourites');
    }


    public function appointments(){
        return $this->hasMany(Appointment::class,'hospital_id','id');
    }

    function getAverageRatingAttribute(){
        $averageRating = $this->reviews()->average('rating') ? : 0;

        return round($averageRating, 2);
    }

    public function getFeatured()
    {
       $hospitals = $this->with('specialities_hospitals','reviews')
        ->where('isFeatured',1)
            ->where('status',1)
            ->offset(0)->limit(4)
            ->get();

        $return = array();

       if($hospitals){
           $records = $hospitals->toArray();
           foreach($records as $row) {

               $profile = translateArr($row);
               $profile['image'] = asset('public/uploads/images/'.$profile['image']);

               if(count($profile['specialities_hospitals'])>0){
                   $spec = translateArray($profile['specialities_hospitals']);

                   foreach ($spec as $sp){
                       $profile['specialist'] = $sp['name'];
                       break;
                   }
                   // $profile['specialist'] = implode(',',$profile['specialist']);
               }

               $return[] = $profile;
           }

       }
       return $return;

    }
    public function getByID($id)
    {
        $hospital =  $this->with('specialities_hospitals',
            'reviews','reviews.patient','reviews.hospital','reviews.doctor',
            'certifications_hospitals',
            'awards_hospitals',
            'hospital_insurances.insurance',
            'patient_fav','doctors')
            ->where('id',$id)
            ->first();

        return $hospital;
    }

    public function getDetail($id,$hospital = false,$user_id = false)
    {
        if(!$hospital){
            $hospital = $this->getByID($id);
        }

        $hospital = $hospital->toArray();

        $profile = translateArr($hospital);
        $profile['image'] = asset('public/uploads/images/'.$profile['image']);

        //$profile['specialist'] = array();
        if(isset($profile['specialities_hospitals']) && count($profile['specialities_hospitals'])>0){
            $profile['specialities_hospitals'] = translateArray($profile['specialities_hospitals']);
            // $profile['specialist'] = implode(',',$profile['specialist']);
        }

        // $profile['certifications'] = array();
        if(isset($profile['certifications_hospitals']) && count($profile['certifications_hospitals'])>0) {
            $profile['certifications_hospitals'] = translateArray($profile['certifications_hospitals']);
        }

        if(isset($profile['awards_hospitals']) && count($profile['awards_hospitals'])>0) {
            $profile['awards_hospitals'] = translateArray($profile['awards_hospitals']);
        }


        if(isset($profile['reviews']) && count($profile['reviews'])>0) {
            $profile['reviews'] = translateArray($profile['reviews']);
            $i=0;

            $reply_model = new Reply();
            $like_model = new Like();

            foreach ($profile['reviews'] as $review){
               // echo '<pre>'; print_r($review['patient']); exit;
                if(isset($review['patient']))
                $profile['reviews'][$i]['patient'] = translateArr($review['patient']);
                if(isset($review['doctor']))
                $profile['reviews'][$i]['doctor'] = translateArr($review['doctor']);
                if(isset($review['hospital']))
                $profile['reviews'][$i]['hospital'] = translateArr($review['hospital']);

                if($user_id){
                    $profile['reviews'][$i]['is_like'] =  $like_model->where('review_id', $review['id'])
                        ->where('review_reply_id',0)
                        ->where('patient_id', $user_id)
                        ->count();
                }

                //get Replies
                $replies = $reply_model->with('patient','doctor','hospital')
                    ->where('review_id',$review['id'])
                    ->get();

                $reply_array = array();
                if(isset($replies) && count($replies)>0) {
                    foreach ($replies->toArray() as $reply){
                        $reply_arr = translateArr($reply);
                        if(isset($reply['patient'])){
                            $reply_arr['patient'] = translateArr($reply['patient']);
                        }
                        if(isset($reply['doctor'])){
                            $reply_arr['doctor'] = translateArr($reply['doctor']);
                        }
                        if(isset($reply['hospital'])){
                            $reply_arr['hospital'] = translateArr($reply['hospital']);
                        }

                        if($user_id){
                            $reply_arr['is_like'] = $like_model->where('review_reply_id', $reply['id'])
                                ->where('patient_id',$user_id)
                                ->count();
                        }

                        $reply_array[] = $reply_arr;
                    }
                }
                $profile['reviews'][$i]['replies'] = $reply_array;
                $i++;
            }
        }

        if(isset($profile['doctors']) && count($profile['doctors'])>0) {

            $profile['doctors'] = translateArray($profile['doctors']);

            $doctor_model = new Doctor();

                if(count($profile['doctors'])>0){
                    $j= 0;
                    foreach($profile['doctors'] as $aff_doctor){

                        if(Auth::guard('user')->check()) {
                            $patient_id = Auth::guard('user')->user()->id;
                            $is_doctor_fav = PatientFavourites::where('patient_id', $patient_id)->where('doctor_id', $aff_doctor['id'])->count();
                            $profile['doctors'][$j]['is_fav'] = ($is_doctor_fav > 0) ? 1 : 0;
                        }
                        $profile['doctors'][$j]['speciality'] = $doctor_model->getSpeciality($aff_doctor['id']);
                        $j++;
                    }


            }
        }

        return $profile;
    }

    public function getSpeciality($id)
    {
        $doctor_speciality = DB::table('hospital_specialities')
            ->join('specialities', 'hospital_specialities.speciality_hospital_id', '=', 'specialities.id')
            ->select('hospital_specialities.speciality_hospital_id', 'specialities.name')
            ->where('hospital_specialities.hospital_id',$id)
            ->offset(0)->limit(1)
            ->first();
        if(isset($doctor_speciality)){
            $sp = json_decode($doctor_speciality->name);
            return ucfirst(strtolower($sp->en));
        }
    }

    public function getAll($user_id = false,$request = false,$ids = false)
    {
        $query =  $this->with('specialities_hospitals','patient_fav');

        $query->select('hospitals.*');

        if(isset($request->speciality_id)){

            $query->leftJoin('hospital_specialities',
                'hospital_specialities.hospital_id','=','hospitals.id');

                $query->leftJoin('specialities',
                    'specialities.id','=','hospital_specialities.speciality_hospital_id')
                ->where('hospital_specialities.speciality_hospital_id',$request->speciality_id);

            $query->select('hospitals.*','specialities.name as special');
        }

        if(isset($request->latitude) && isset($request->longitude)){
           // $query->where('latitude',$request->latitude);
           // $query->where('longitude',$request->longitude);

            $latitude = $request->latitude;
            $longitude = $request->longitude;

            $query->selectRaw("ASIN(SQRT( POWER(SIN(($latitude-ABS(hospitals.latitude)) * PI()/180 / 2),2) + COS($latitude* PI()/180 ) * COS( ABS(hospitals.latitude) *  PI()/180) * POWER(SIN(($longitude - hospitals.longitude) *  PI()/180 / 2), 2) ))
                AS distance")
            ->Having('distance', '<' ,10);
        }

        /*if(isset($request->appointment_date) && isset($request->appointment_time)){
            $date = $request->appointment_date;
            $time = $request->appointment_time;

            $query->whereNotIn('hospitals.id',function($query) use ($date,$time) {
                $query->select('hospital_id')->from('doctor_appointments')
                ->where('appointment_date',$date)
                ->where('appointment_time',$time);
            });
        }*/

        //die($query->toSql());

        if($ids){
            $query->whereIn('hospitals.id',$ids);
        }

        $hospitals = $query->paginate(10);

     //echo '<pre>'; print_r($hospitals); exit;
        $records = $pagination = array();
        if(count($hospitals)>0){

            $hospitals_arr = $hospitals->toArray();
            unset($hospitals_arr['data']);
            $pagination = $hospitals_arr;

            foreach ($hospitals as $hospital){

                $row = $this->getDetail($hospital->id,$hospital);
                //echo '<pre>'; print_r($row); exit;
                if(!isset($hospital->special)) {
                    $row['speciality'] = isset($row['specialities_hospitals'][0]['name']) ? $row['specialities_hospitals'][0]['name'] : '';
                }else{
                        $special = json_decode($hospital->special,true);
                        $row['speciality'] = $special['en'];
                    }
                    // $row['hospital'] = isset($row['hospitals'][0]['name']) ? $row['hospitals'][0]['name'] : '';
                $row['is_fav'] = $this->checkFav($user_id,$hospital->id);
                $records[] = $row;
            }
        }

        return array(
            'hospitals' => $records,
            'pagination' => $pagination
        );
    }

    /**
     * @param $user_id
     * @param $hospital_id
     * @return int
     */
    public function checkFav($user_id,$hospital_id)
    {
        $is_fav = 0;
        if (isset($user_id)) {
            $patient_fav = PatientFavourites::where('patient_id', $user_id)
                ->where('hospital_id', '>', 0)->get();

            if (count($patient_fav) > 0) {
                foreach ($patient_fav as $fav) {

                    if ($hospital_id == $fav->hospital_id) {
                        $is_fav = 1;
                        break;
                    }
                }

            }
        }
        return $is_fav;
    }


}
