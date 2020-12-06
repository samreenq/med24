<?php
namespace App\Http\Controllers\Api\Doctor;
use App\Doctor;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Doctor\LoginRequest;
use App\Http\Requests\Api\Doctor\SignupRequest;
use App\Http\Requests\Api\Doctor\UpdateProfileRequest;
use App\Http\Resources\Api\Doctor\DoctorInfo;
use App\Http\Resources\Api\Doctor\DoctorProfile;
use App\Notifications\ProfileClaimed;
use App\Notifications\sendOTP;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mail;
use Twilio\Rest\Client;

class AuthController extends ApiController
{

    public function login(LoginRequest $request){
        $doctor = Doctor::where('email', $request->email)->first();
        if($doctor){
            $verify_password= Hash::check($request->password, $doctor->password);
            if($verify_password){
                if($doctor->status!==1 && !$doctor->verified_at){
                    $doctor->otp=rand(1001,9999);
                    $doctor->save();
                    
                    try {
                        /*$client = new Client(env('TWILIO_ACCOUNT_ID'), env('TWILIO_AUTH_TOKEN'));

                        $client->messages->create(
                            $doctor->phone_code.$doctor->phone,
                            [
                                'from' => env('TWILIO_FROM_PHONE_NO'),
                                'body' => 'Hi '.$doctor->first_name.', Your verification otp code is '.$doctor->otp,
                            ]
                        );*/
                        
                        Mail::send('emailTemplates.signUp', [
                            'record' => $doctor
                        ], function ($message) use ($doctor){
                            $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                                    ->to($doctor->email, ($doctor->first_name ?? '').' '.($doctor->last_name ?? ''))
                                    ->subject('Sign Up');
                        });
                    } catch (Exception $e) {
                        return $this->apiErrorResponse('Account Not Verified! OTP Has Been Sent To Email',['is_verified'=>false]);
                    }

                    return $this->apiErrorResponse('Account Not Verified! OTP Has Been Sent To Email',['is_verified'=>false]);
                }
                $doctor->forceFill([
                    'device_type' => $request->device_type,
                    'device_token' => $request->device_token,
                    'timezone' => $request->timezone,
                    'api_token' => hash('sha256', Str::random(120)),

                ])->save();
                $doctorInfo=(new DoctorInfo($doctor))->resolve();
                $additionalData=[
                    'bearer_token'=>$doctor->api_token,
                    'role_type'=>'doctor'
                ];
                $profile=array_merge($doctorInfo,$additionalData);
                return $this->apiSuccessResponse('success',$profile);
            }
            return $this->apiErrorResponse('invalid credentials entered');
        }
        return $this->apiErrorResponse('invalid credentials entered');
    }

    public function signup(SignupRequest $request){
        $doctor = new Doctor();

        $request->request->add([
            'requestType' => 'api'
        ]);
        
        $doctor = $doctor->store($request);

        if($doctor instanceof Doctor){
            try {
                /*$client = new Client(env('TWILIO_ACCOUNT_ID'), env('TWILIO_AUTH_TOKEN'));

                $client->messages->create(
                    $doctor->phone_code.$doctor->phone,
                    [
                        'from' => env('TWILIO_FROM_PHONE_NO'),
                        'body' => 'Hi '.$doctor->first_name.', Your verification otp code is '.$doctor->otp,
                    ]
                );
*/
                Mail::send('emailTemplates.signUp', [
                    'record' => $doctor
                ], function ($message) use ($doctor){
                    $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                            ->to($doctor->email, ($doctor->first_name ?? '').' '.($doctor->last_name ?? ''))
                            ->subject('Sign Up');
                });
            } catch (Exception $e) {
                return $this->apiSuccessResponse('Verification Otp Has Been Sent To Your Email Address');
            }

            return $this->apiSuccessResponse('Verification Otp Has Been Sent To Your Email Address');
        }

        return $this->apiErrorResponse('Something Went Wrong!');
    }



    public function updateProfile(UpdateProfileRequest $request){
        $doctor = Doctor::find(Auth::user()->id);

        if($doctor){
            $doctor->store($request);

            if($doctor instanceof Doctor){
                $doctor=new DoctorProfile($doctor);
                
                return $this->apiSuccessResponse('Profile Updated Successfully',$doctor);
            }

            return $this->apiErrorResponse('Something Went Wrong!');
        }

        return $this->apiErrorResponse('No Records Found');
    }


    public function verifyOtp(Request $request){
        $rules=[
            'email'=>'required|email',
            'otp'=>'required'
        ];
        $this->validate($request,$rules);
        $doctor=Doctor::where('email',$request->email)->first();
        if(isset($doctor) && $doctor->otp==$request->otp){
            $doctor->verified_at=Carbon::now();
            $doctor->status=1;
            $doctor->otp=null;
            $doctor->save();
            return $this->apiSuccessResponse('Otp Verified');
        }
        return $this->apiErrorResponse('Invalid OTP entered');
    }


    public function resendOTPCode(Request $request){
        $rules=[
            'email'=>'required|email'
        ];
        $this->validate($request,$rules);
        $doctor=Doctor::where('email',$request->email)->first();
        if($doctor){
            $doctor->otp=rand(1001,9999);
            $doctor->save();
            
            try {
                /*$client = new Client(env('TWILIO_ACCOUNT_ID'), env('TWILIO_AUTH_TOKEN'));

                $client->messages->create(
                    $doctor->phone_code.$doctor->phone,
                    [
                        'from' => env('TWILIO_FROM_PHONE_NO'),
                        'body' => 'Hi '.$doctor->first_name.', Your verification otp code is '.$doctor->otp,
                    ]
                );*/

                Mail::send('emailTemplates.signUp', [
                    'record' => $doctor
                ], function ($message) use ($doctor){
                    $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                            ->to($doctor->email, ($doctor->first_name ?? '').' '.($doctor->last_name ?? ''))
                            ->subject('Sign Up');
                });
            } catch (Exception $e) {
                return $this->apiSuccessResponse('Otp Has Been Sent To Your Email');
            }

            return $this->apiSuccessResponse('Otp Has Been Sent To Your Email');
        }
        return $this->apiErrorResponse('Invalid Email/Otp');

    }

    public function changePassword(Request $request){
        $rules=[
            'current_password'=>'required',
            'new_password'=>'required|min:8',
        ];
        $this->validate($request,$rules);
        $password=Auth::user()->getAuthPassword();
        if(Hash::check($request->current_password,$password)){
            $doctor=Doctor::find(Auth::user()->id);
            $doctor->password=bcrypt($request->password);
            $doctor->save();
            return $this->apiSuccessResponse('Password Has Been Changed');
        }
        return $this->apiErrorResponse('Current Password Is Incorrect');
    }
    public function signOut(){
        $doctor=Doctor::find(Auth::user()->id);
        $doctor->api_token=null;
        $doctor->save();
        return $this->apiSuccessResponse('Success');
    }

    public function searchProfiles(Request $request){
        $doctors=Doctor::where('first_name','like',"%$request->keyword%")->orWhere('email','like',"%$request->keyword%")->get();
        if($doctors->count()){
            $doctors=DoctorInfo::collection($doctors);
            return response()->json(['status'=>1,'api_status'=>200, 'message'=>'Profiles Found','data'=>$doctors]);
        }
        return response()->json(['status'=>0,'api_status'=>200, 'message'=>'No Profiles Found With Entered Keyword','data'=>null]);
    }
    public function claimProfile(Request $request){
        $doctor=Doctor::find($request->id);
        if($doctor){
            $token=app('auth.password.broker')->createToken($doctor);
           $reset= DB::table('password_resets')->insert([
                'email' => $doctor->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);
            $doctor->notify(new ProfileClaimed($doctor,$token));
            return response()->json(['status'=>1,'api_status'=>200, 'message'=>'A Confirmation Mail Has Been Sent To '.substr($doctor->email,0,3).'*********@'.substr(strrchr($doctor->email, "@"), 1),'data'=>null]);
        }
        return response()->json(['status'=>0,'api_status'=>200,'message'=>'Invalid ID','data'=>null]);
    }



    public function viewProfile(Request $request){
        $doctor=Doctor::find(Auth::user()->id);
        $doctor=new DoctorProfile($doctor);
        return response()->json(['status'=>1,'api_status'=>200,'message'=>'Profile','data'=>$doctor]);
    }


}
