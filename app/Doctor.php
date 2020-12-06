<?php
namespace App;
use App\Http\Resources\Api\Doctor\DoctorProfile;
use App\Notifications\PasswordReset;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Spatie\Translatable\HasTranslations;
use Validator;
use App\Notifications\sendOTP;

class Doctor extends Authenticatable
{
    use HasTranslations;
    use Notifiable;

    use SoftDeletes;
    use HasApiTokens;

    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'country_code', 'token_sent_on', 'phone', 'gender', 'password', 'dob', 'area', 'status', 'image', 'token', 'created_by', 'updated_by', 'medical_license', 'country_id', 'city_id', 'hospital_id', 'speciality_id'
    ];



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


    public $translatable = ['first_name','last_name','about','biography','summary','care_philosophy'];

    public function hospitals(){
        return $this->belongsToMany(Hospital::class,'doctor_hospitals','doctor_id','hospital_id');
    }

    public function specialities(){
        return $this->belongsToMany(Speciality::class,'doctor_specialities','doctor_id','speciality_id');
    }

    public function appointments(){
        return $this->hasMany(Appointment::class,'doctor_id','id');
    }

    public function certifications(){
        return $this->belongsToMany(Certification::class,'doctor_certifications','doctor_id','certification_id');
    }

    public function languages(){
        return $this->belongsToMany(Language::class,'doctor_languages','doctor_id','language_id');
    }

    public function reviews(){
        return $this->hasMany(Review::class,'doctor_id','id');
    }

    public function getEmailForPasswordReset(){
        return $this->email;
    }

    public function sendPasswordResetNotification($token){
        $doctor=Doctor::where('email',$this->getEmailForPasswordReset())->first();
        $doctor->notify(new PasswordReset($token,$doctor,'doctor'));
    }

    public function patient_fav(){
        return $this->belongsToMany(Patient::class, 'patient_favourites');
    }

//    public function patient_fav_doctors(){
//        return $this->belongsToMany(Patient::class,'patient_favourites','patient_id','doctor_id');
//    }
//
//    public function patient_fav_hospitals(){
//        return $this->belongsToMany(Patient::class,'patient_favourites','patient_id','hospital_id');
//    }


    public function store($request){
        if(isset($request->first_name)) {
           $this->first_name = $request->first_name;
       }if(isset($request->last_name)) {
            $this->last_name = $request->last_name;
       }if(!isset($this->id) || (isset($request->email) && $this->email!=$request->email)){
            $this->email=$request->email;
        }if(isset($request->password)){
            $this->password=bcrypt($request->password);
        }if(isset($request->gender)){
            $this->gender=$request->gender;
        }if(isset($request->about)){
            $this->about=$request->about;
        }if(isset($request->phone)){
            $this->phone=$request->phone;
        }if(isset($request->country_id)){
            $this->country_id=$request->country_id;
        }if(isset($request->city)){
            $this->city_id=$request->city;
        }if(isset($request->date_of_birth)){
            $this->date_of_birth=$request->date_of_birth;
        }if(isset($request->biography)){
            $this->biography=$request->biography;
        }if(isset($request->summary)){
            $this->summary=$request->summary;
        }if(isset($request->care_philosophy)){
            $this->care_philosophy=$request->care_philosophy;
        }if(isset($request->medical_license)){
            $this->medical_license=$request->medical_license;
        }

        if(isset($request->startingFee)){
            $this->startingFee = $request->startingFee;
        }

        if(isset($request->status)){
            $this->status=$request->status;
        }

        if(isset($request->device_type)){
            $this->device_type=$request->device_type;
        } if(isset($request->device_token)){
            $this->device_token=$request->device_token;
        }if(isset($request->timezone)){
            $this->timezone=$request->timezone;
        }if(isset($request->provider_type)){
            $this->provider_type=$request->provider_type;
        }if(isset($request->provider_id)){
            $this->provider_id=$request->provider_id;
        }
        $this->ip_address=$_SERVER['REMOTE_ADDR'];
        if($request->hasFile('image')){
            if($this->image!='doctor.png'){
                $this->deleteImage($this->image);
            }
            $this->image=$this->uploadFile($request);
        }

        $this->isFeatured = false;
        $this->total_experience = $request->total_experience;
        $this->experience_type = $request->experience_type;

        if(isset($request->isFeatured)){
            $this->isFeatured = true;
        }

        if(isset($request->requestType) && $request->requestType == 'api'){
            $this->otp = rand(1001, 9999);
        }

        $this->save();

        if(isset($request->requestType) && $request->requestType == 'api'){
            //$this->notify(new sendOTP($this));
        }

        if(isset($request->specialities)){
            $specialities=Speciality::whereIn('id',$request->specialities)->get()->pluck('id')->toArray();
            if($specialities){
                $this->specialities()->sync($specialities);
            }
        }
        if(isset($request->hospitals) && $request->doctorId == null){
            $hospitals = Hospital::whereIn('id',$request->hospitals)->get()->pluck('id')->toArray();

            if($hospitals){
                $this->hospitals()->sync($hospitals);
            }
        } if(isset($request->languages)){
            $languages=Language::whereIn('id',$request->languages)->get()->pluck('id')->toArray();
            if($languages){
                $this->languages()->sync($languages);
            }
        }
        if(isset($request->certifications)){
            $certifications=Certification::whereIn('id',$request->certifications)->get()->pluck('id')->toArray();
            if($certifications){
                $this->certifications()->sync($certifications);
            }
        }

        if(isset($request->doctors_insurances)){
            $doctors_insurances = InsurancesPlans::whereIn('id', $request->doctors_insurances)->get()->pluck('id')->toArray();

            if($doctors_insurances){
                $this->doctors_insurances()->sync($doctors_insurances);
            }
        }

        return $this;
    }

    public function getTimeSlotsWithAppointments($date,$hospital_id,$doctor_id){
        $day=Carbon::parse($date)->format('l');
        //todo: check if doctor is available or unavailable on specified date
        $time_slot=DoctorTiming::where('hospital_id',$hospital_id)->where('day',$day)->where('doctor_id',$doctor_id)->get();
        if(!$time_slot->count()){
            return ['time_slots'=>[],'booked_appointments'=>[]];
        }
        $doctor_slots=[
            'time_slots'=>[]
        ];
        foreach ($time_slot as $slot){
            $interval = DateInterval::createFromDateString($slot->appointment_interval.' minutes');
            $begin = Carbon::createFromFormat('H:i:s',$slot->from);
            $end = Carbon::createFromFormat('H:i:s',$slot->to);
            $end->add($interval);
            $periods = iterator_to_array(new DatePeriod($begin, $interval, $end));
            $start = array_shift($periods);
            foreach ($periods as $time) {
                $doctor_slots['time_slots'][]= Carbon::parse($start)->format('h:i:s');
                $start = $time;
            }
        }
        $doctor_slots['booked_appointments']=Appointment::where('hospital_id',$hospital_id)
            ->where('doctor_id',$doctor_id)
            ->whereDate('appointment_date',$date)
            ->whereIn('appointment_time',$doctor_slots['time_slots'])
            ->get()->pluck('appointment_time')->toArray();
        return $doctor_slots;
    }

    public function timeSlots($date,$hospital_id){
        $time_table=[];
        $slots=$this->getTimeSlotsWithAppointments($date,$hospital_id,$this->id);
        $doctor_slots=$slots['time_slots'];
        $appointments=$slots['booked_appointments'];
        foreach ($doctor_slots as $doctor_slot){
            $data['time']= Carbon::createFromFormat('H:i:s',$doctor_slot)->format('H:i A');
            $data['status']='available';
            foreach ($appointments as $appointment){
                if($appointment==$doctor_slot){
                    $data['status']='booked';
                }
            }
            $time_table[]=$data;
        }
        return $time_table;
    }

    public function isSlotAvailable($date,$hospital_id){
        $slots=$this->getTimeSlotsWithAppointments($date,$hospital_id,$this->id);
        $doctor_slots=$slots['time_slots'];
        $appointments=$slots['booked_appointments'];
    }

    public  function deleteImage($image_name){
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

    public  function uploadFile($request,$dir='images'){
        $uploadedImage = $request->file('image');
        $imageName = time() . '.' . $uploadedImage->getClientOriginalExtension();
        $destinationPath = public_path('/uploads/'.$dir);
        $uploadedImage->move($destinationPath, $imageName);
        return $imageName;
    }

    public function doctors_insurances(){
        return $this->belongsToMany(InsurancesPlans::class, 'doctors_insurances', 'doctor_id', 'id');
    }

    function doctorTimeSlots(){
        return $this->hasMany('App\DoctorTiming');
    }

    public function getFeaturedDoctors()
    {
        $doctors_profile = array();

       $doctors =  $this->with('specialities','reviews')
           ->where('isFeatured',1)
           ->offset(0)->limit(4)
            //->where('account_status','active')
            ->get();

           if($doctors){
               $records = $doctors->toArray();
               foreach($records as $doctor) {

                   $doc_profile = translateArr($doctor);
                   $doc_profile['image'] = asset('public/uploads/images/'.$doc_profile['image']);

                   if(count($doc_profile['specialities'])>0){
                       $spec = translateArray($doc_profile['specialities']);

                       foreach ($spec as $sp){
                           $doc_profile['specialist'] = $sp['name'];
                           break;
                       }
                      // $doc_profile['specialist'] = implode(',',$doc_profile['specialist']);
                   }
                   $doc_profile['avg_rating'] = '';
                   if(count($doc_profile['reviews'])>0){
                       $doc_profile['avg_rating'] = avgRating($doc_profile['reviews']);
                   }

                   $doctors_profile[] = $doc_profile;
               }
           }
           return $doctors_profile;
    }

    public function getDocDetail($id)
    {
       return $doctor =  $this->with('specialities','reviews','certifications',
            'doctors_insurances.insurance','hospitals','hospitals.specialities_hospitals','patient_fav')
            ->where('id',$id)
            ->first();
    }

    public function getDetail($id,$doctor = false)
    {
        if(!$doctor){
            $doctor = $this->getDocDetail($id);
        }
        $doctor = $doctor->toArray();

        $doc_profile = translateArr($doctor);
        $doc_profile['image'] = asset('public/uploads/images/'.$doc_profile['image']);

        //$doc_profile['specialist'] = array();
        if(isset($doc_profile['specialities']) && count($doc_profile['specialities'])>0){
            $doc_profile['specialities'] = translateArray($doc_profile['specialities']);
            $doc_profile['speciality'] = $doc_profile['specialities'][0]['name'];
            // $doc_profile['specialist'] = implode(',',$doc_profile['specialist']);
        }

       // $doc_profile['certifications'] = array();
        if(isset($doc_profile['certifications']) && count($doc_profile['certifications'])>0) {
            $doc_profile['certifications'] = translateArray($doc_profile['certifications']);
        }

        $doc_profile['avg_rating'] = '';
        if(isset($doc_profile['reviews']) && count($doc_profile['reviews'])>0){
            $doc_profile['avg_rating'] = avgRating($doc_profile['reviews']);
        }


        if(isset($doc_profile['hospitals']) && count($doc_profile['hospitals'])>0){
            $doc_profile['hospitals'] = translateArray($doc_profile['hospitals']);
        }


        return $doc_profile;
    }

    public function getSpeciality($doctor_id)
    {
        $doctor_speciality = DB::table('doctor_specialities')
            ->join('specialities', 'doctor_specialities.speciality_id', '=', 'specialities.id')
            ->select('doctor_specialities.speciality_id', 'specialities.name')
            ->where('doctor_specialities.doctor_id',$doctor_id)
            ->offset(0)->limit(1)
            ->first();

        if(isset($doctor_speciality)){
            $sp = json_decode($doctor_speciality->name);
            return ucfirst(strtolower($sp->en));
        }
    }

    public function getAll($user_id = false,$request = false,$ids = false)
    {
        $query =  $this->with('specialities',
            'hospitals','patient_fav');

        $query->select('doctors.*');

        if(isset($request->speciality_id)){

            $query->leftJoin('doctor_specialities',
                'doctor_specialities.doctor_id','=','doctors.id');

            $query->leftJoin('specialities',
                'specialities.id','=','doctor_specialities.speciality_id')
                ->where('doctor_specialities.speciality_id',$request->speciality_id);

            $query->select('doctors.*','specialities.name as special');
        }

        if(isset($request->appointment_date) && isset($request->appointment_time)){
            $date = date('Y-m-d',strtotime($request->appointment_date));
            $time = date('H:i:s',strtotime($request->appointment_time));

            $query->whereNotIn('doctors.id',function($query) use ($date,$time) {
                $query->select('doctor_id')->from('doctor_appointments')
                ->where('appointment_date',$date)
                ->where('appointment_time',$time);
            });
        }

        if($ids){
            $query->whereIn('doctors.id',$ids);
        }
        //die($select);

        $doctors = $query
            //->orderBy('id','DESC')
            ->paginate(10);

        //echo '<pre>'; print_r($doctor_arr); exit;
        $records = $pagination = array();

        if(count($doctors)>0){

            $doctor_arr = $doctors->toArray();
            unset($doctor_arr['data']);
            $pagination = $doctor_arr;

            foreach ($doctors as $doctor){

                //echo '<pre>'; print_r($doctor); exit;
                $row = $this->getDetail($doctor->id,$doctor);
                //
                if(!isset($doctor->special)){
                    $row['speciality'] = isset($row['specialities'][0]['name']) ? $row['specialities'][0]['name'] : '';
                }
                else{
                    $special = json_decode($doctor->special,true);
                    $row['speciality'] = $special['en'];
                }

                $row['hospital'] = isset($row['hospitals'][0]['name']) ? $row['hospitals'][0]['name'] : '';
                $row['is_fav'] = $this->checkDoctorFav($user_id,$doctor->id);
                $records[] = $row;
            }
        }

       // $pagination['data'] = $records;
        //echo '<pre>'; print_r($records); exit;
        return array(
            'doctors' => $records,
            'pagination' => $pagination
        );

    }

    public function checkDoctorFav($user_id,$doctor_id)
    {
        $is_fav = 0;

        if (isset($user_id)) {
            $patient_fav = PatientFavourites::where('patient_id', $user_id)
                ->where('doctor_id', '>', 0)->get();

            if (count($patient_fav) > 0) {
                foreach ($patient_fav as $fav) {

                    if ($doctor_id == $fav->doctor_id) {
                        $is_fav = 1;
                        break;
                    }

                }

            }
        }
        return $is_fav;
    }

}
