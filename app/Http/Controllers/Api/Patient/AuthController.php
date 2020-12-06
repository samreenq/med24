<?php

namespace App\Http\Controllers\Api\Patient;
use App\Patient;
use App\Http\Requests\Api\Patient\UpdateProfileRequest;
use App\Http\Resources\Api\Patient\PatientProfile;
use App\Http\Requests\Api\Patient\LoginRequest;
use App\Notifications\sendOTP;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Patient\SignupRequest;
use App\Http\Resources\Api\Patient\PatientInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Mail;
use Twilio\Rest\Client;

class AuthController extends ApiController
{
    public function signup(SignupRequest $request){
        $patient = new Patient();

        $request->request->add([
            'requestType' => 'api'
        ]);

        $patient->store($request);

        if($patient instanceof  Patient){
            $patient = (new PatientInfo($patient))->resolve();

            try {
                /*$client = new Client(env('TWILIO_ACCOUNT_ID'), env('TWILIO_AUTH_TOKEN'));

                $client->messages->create(
                    $patient['phone_code'].$patient['phone_no'],
                    [
                        'from' => env('TWILIO_FROM_PHONE_NO'),
                        'body' => 'Hi '.$patient['first_name'].', Your verification otp code is '.$patient['otp'],
                    ]
                );*/

                Mail::send('emailTemplates.signUp', [
                    'record' => $patient
                ], function ($message) use ($patient){
                    $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                            ->to($patient['email'], ($patient['first_name'] ?? '').' '.($patient['last_name'] ?? ''))
                            ->subject('Sign Up');
                });
            } catch (Exception $e) {
                return $this->apiDataResponse($patient,'success');
            }

            return $this->apiDataResponse($patient,'success');
        }
        
        return $this->apiErrorResponse('Something Went Wrong');
    }




    public function socialAuth(Request $request){
        $rules=[
            'email'=>'required',
            'first_name'=>'required',
            'provider_type'=>'required',
            'provider_id'=>'required',
            'device_type'=>'required',
            'device_token'=>'required',
            'timezone'=>'required'
        ];
        $this->validate($request,$rules);
        $patient=Patient::where('email',$request->email)->first();
        if(!$patient){
            $patient=new Patient();
            $patient->store($request);
        }
        $patient->forceFill([
            'device_type' => $request->device_type,
            'device_token' => $request->device_token,
            'timezone' => $request->timezone,
            'api_token' => hash('sha256', Str::random(120)),
        ])->save();
        $patientInfo=(new PatientInfo($patient))->resolve();
        $additionalData=[
            'bearer_token'=>$patient->api_token,
            'role_type'=>'patient'
        ];
        $profile=array_merge($patientInfo,$additionalData);
        return $this->apiSuccessResponse('success',$profile);
    }

    public function login(LoginRequest $request)
    {
        $patient = Patient::where('email', $request->email)->first();
        if ($patient) {
            $verify_password = Hash::check($request->password, $patient->password);
            if ($verify_password) {
                if ($patient->status !== 1 && !$patient->verified_at) {
                    $patient->otp=rand(1001,9999);
                    $patient->save();
                    
                    try {
                        /*$client = new Client(env('TWILIO_ACCOUNT_ID'), env('TWILIO_AUTH_TOKEN'));
                        
                        $client->messages->create(
                            $patient->phone_code.$patient->phone_no,
                            [
                                'from' => env('TWILIO_FROM_PHONE_NO'),
                                'body' => 'Hi '.$patient->first_name.', Your verification otp code is '.$patient->otp,
                            ]
                        );*/

                        Mail::send('emailTemplates.signUp', [
                            'record' => $patient
                        ], function ($message) use ($patient){
                            $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                                    ->to($patient['email'], ($patient['first_name'] ?? '').' '.($patient['last_name'] ?? ''))
                                    ->subject('Sign Up');
                        });
                    } catch (Exception $e) {
                        return $this->apiErrorResponse('Account Not Verified! OTP Has Been Sent To Email',['is_verified'=>false]);
                    }

                    return $this->apiErrorResponse('Account Not Verified! OTP Has Been Sent To Email',['is_verified'=>false]);
                }
                $patient->forceFill([
                    'device_type' => $request->device_type,
                    'device_token' => $request->device_token,
                    'timezone' => $request->timezone,
                    'api_token' => hash('sha256', Str::random(120)),
                ])->save();
                $patientInfo = (new PatientInfo($patient))->resolve();
                $additionalData = [
                    'bearer_token' => $patient->api_token,
                    'role_type'=>'patient'
                ];
                $profile = array_merge($patientInfo, $additionalData);
                return $this->apiSuccessResponse('success',$profile);

            }
            return $this->apiErrorResponse('invalid credentials entered');
        }
        return $this->apiErrorResponse('invalid credentials entered');
    }


    public function verifyOtp(Request $request){
        $rules=[
            'email'=>'required|email',
            'otp'=>'required'
        ];
        $this->validate($request,$rules);
        $patient=Patient::where('email',$request->email)->first();
        if(isset($patient) && $patient->otp==$request->otp){
            $patient->verified_at=Carbon::now();
            $patient->status=1;
            $patient->otp=null;
            $patient->save();
            return $this->apiSuccessResponse('Otp Verified');
        }
        return $this->apiErrorResponse('Invalid OTP entered');
    }

    public function viewProfile(Request $request){
        $patient=Patient::find(Auth::user()->id);
        $patient=(new PatientProfile($patient))->resolve();
        return $this->apiDataResponse($patient);;
    }

    public function updateProfile(UpdateProfileRequest $request){
        $patient = Patient::find(Auth::user()->id);

        if($patient){
            $patient->store($request);

            if($patient instanceof Patient){
                $patient = new PatientProfile($patient);

                return $this->apiSuccessResponse('Profile Updated Successfully',$patient);
            }

            return $this->apiErrorResponse('Something Went Wrong!');
        }

        return $this->apiErrorResponse('No Records Found');
    }

    public function resendOTPCode(Request $request){
        $rules=[
            'email'=>'required|email'
        ];
        $this->validate($request,$rules);
        $patient=Patient::where('email',$request->email)->first();
        if($patient){
            $patient->otp=rand(1001,9999);
            $patient->save();
            
            try {
                /*$client = new Client(env('TWILIO_ACCOUNT_ID'), env('TWILIO_AUTH_TOKEN'));

                $client->messages->create(
                    $patient->phone_code.$patient->phone_no,
                    [
                        'from' => env('TWILIO_FROM_PHONE_NO'),
                        'body' => 'Hi '.$patient->first_name.', Your verification otp code is '.$patient->otp,
                    ]
                );*/

                Mail::send('emailTemplates.signUp', [
                    'record' => $patient
                ], function ($message) use ($patient){
                    $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                            ->to($patient['email'], ($patient['first_name'] ?? '').' '.($patient['last_name'] ?? ''))
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
        $rules = [
            'current_password'=>'required',
            'new_password'=>'required|min:8',
        ];

        $this->validate($request, $rules);

        $password = Auth::user()->getAuthPassword();

        if(Hash::check($request->current_password, $password)){
            $patient = Patient::find(Auth::user()->id);
            $patient->password = Hash::make($request->new_password);
            $patient->save();

            return $this->apiSuccessResponse('Password Has Been Changed');
        }

        return $this->apiErrorResponse('Current Password Is Incorrect');
    }

    public function signOut(){
        $patient=Patient::find(Auth::user()->id);
        $patient->api_token=null;
        $patient->save();
        return $this->apiSuccessResponse('Success');
    }

}
