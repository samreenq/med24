<?php
namespace App;
use App\Notifications\PasswordReset;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Translatable\HasTranslations;
use App\Notifications\sendOTP;

class Patient extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use HasTranslations;
    public $translatable = ['first_name','last_name','gender'];
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }
    public function sendPasswordResetNotification($token)
    {
        $patient=Patient::where('email',$this->getEmailForPasswordReset())->first();
        $patient->notify(new PasswordReset($token,$patient,'patient'));
    }
    public function store($request){

       // print_r($request->all()); exit;
        if(isset($request->first_name)) {
            $this->first_name = $request->first_name;
        }if(isset($request->last_name)) {
            $this->last_name = $request->last_name;
        }if(!isset($this->id) || (isset($request->email) && $this->email!=$request->email)){
            $this->email=$request->email;
        }if(isset($request->password)){
            $this->password=bcrypt($request->password);
        }if(isset($request->phone_no)){
            $this->phone_no=$request->phone_no;
        }if(isset($request->phone_code)){
            $this->phone_code=$request->phone_code;
        }if(isset($request->gender)){
            $this->gender=$request->gender;
        }if(isset($request->provider_type)){
            $this->provider_type=$request->provider_type;
        }if(isset($request->provider_id)){
            $this->provider_id=$request->provider_id;
        }if(isset($request->device_type)){
            $this->device_type=$request->device_type;
        } if(isset($request->device_token)){
            $this->device_type=$request->device_token;
        }if(isset($request->timezone)){
            $this->timezone=$request->timezone;
        }if($request->hasFile('id_card_front')){
            $this->id_card_front=$this->uploadFile($request,'id_card_front');
        }if($request->hasFile('id_card_back')){
            $this->id_card_back=$this->uploadFile($request,'id_card_back');
        }if($request->hasFile('insurance_card_front')){
            $this->insurance_id_front=$this->uploadFile($request,'insurance_card_front');
        }if($request->hasFile('insurance_card_back')){
            $this->insurance_id_back=$this->uploadFile($request,'insurance_card_back');
        }if($request->hasFile('image')){
            $this->image=$this->uploadFile($request,'image');
        }

        if(isset($request->status)){
            $this->status = $request->status;
        }

        if($request->insuranceId){
            $this->insurance_id = $request->insuranceId;
        }

        if($request->insurancePackageId){
            $this->insurance_package_id = $request->insurancePackageId;
        }

       // if(isset($request->requestType) && $request->requestType == 'api'){
            $this->otp = rand(1001,9999);
       // }
        if(isset($request->temp_pass)){
            $this->temp_pass = encrypt($request->password);
        }

       // echo '<pre>'; print_r($this); exit;
        $this->save();

        if(isset($request->requestType) && $request->requestType == 'api'){
            //$this->notify(new sendOTP($this));
        }

        return $this;
    }

    function insurance(){
        return $this->belongsTo('App\Insurance', 'insurance_id');
    }

    public function insurance_package()
    {
        return $this->belongsTo('App\InsurancesPlans', 'insurance_package_id');
    }

    public  function uploadFile($request,$image,$dir='images')
    {
        $uploadedImage = $request->file($image);
        $imageName = uniqid().'_'.time() . '.' . $uploadedImage->getClientOriginalExtension();
        $destinationPath = public_path('/uploads/'.$dir);
        $uploadedImage->move($destinationPath, $imageName);
        return $imageName;
    }

    function patientFamilyMembers(){
        return $this->hasMany('App\FamilyMember', 'patient_id');
    }

    public function medicalInfo()
    {
        return $this->hasMany('App\MedicalInfo', 'patient_id');
    }

    public function patientInfo($info)
    {
       $info = $info->toArray();
       $info['first_name'] = $info['first_name']['en'];
       $info['last_name'] = $info['last_name']['en'];
       $info['gender'] = $info['gender']['en'];
       return $info;
    }
}
