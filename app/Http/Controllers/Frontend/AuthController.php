<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\DeviceTokens;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Mail;
use Hash;
use Twilio\Rest\Client;
use View;


class AuthController extends WebController
{
    public $_data = [];

    public function __construct()
    {
       // die('sam');
        parent::__construct();
    }

    public function register(Request $request)
    {
        return View::make('site.user.signup');
    }

    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */

    public function signupSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
            'email' => (!$request->patient_id) ? 'string|email|unique:patients' : 'string|email|unique:patients,email,'.$request->patient_id,
            'phone_code' => 'string',
            'phone_no' => (!$request->patient_id) ? 'numeric|unique:patients|digits_between:9,14' : 'numeric|unique:patients|digits_between:9,14,phone_no,'.$request->patient_id,
            'password' => 'required|string|min:8|max:16|confirmed',
            'password_confirmation' => 'required ',

        ]);

        if ($validator->fails()) {
            return redirect('signup')->withInput()->withErrors($validator->errors());
        }
        //$params = $request->all();
       // $params['temp_pass'] = encrypt($request->password);
        $patient = new Patient();
        $request->merge(['temp_pass' => encrypt($request->password)]);

        $patient->store($request);

        if($patient instanceof  Patient) {
           // $patient = (new PatientInfo($patient))->resolve();
            $patient = $patient->patientInfo($patient);

            //echo '<pre>'; print_r($patient->patientInfo($patient)); exit;

            $data['record'] = $patient;
            $data['source'] = 'web';

            if ($patient['email']) {
                try {
                    Mail::send('emailTemplates.signUp', $data, function ($message) use ($patient) {
                        $message->to($patient['email'], $patient['first_name'])->subject('Email Verification | med24');
                        $message->from('admin@train60.com', 'Train60');
                    });
                } catch (\Exception $ex) {
                    return redirect('signup')->withInput()->with('error', $ex->getMessage());
                }
            } elseif ($patient['phone']) {
                try {
                    $sid = env('TWILIO_SID');
                    $token = env('TWILIO_TOKEN');
                    $client = new Client($sid, $token);
                    $res = $client->messages->create(
                        '+' . $patient['country_code'] . $patient['phone'],
                        [
                            'from' => env('TWILIO_FROM'),
                            'body' => 'Hi ' . $patient['first_name'] . ', Your verification token is ' . $patient['otp'],
                        ]
                    );

                } catch (\Exception $ex) {
                    return redirect('signup')->withInput()->with('error', $ex->getMessage());
                }
            }
        }
        return redirect('verify-user/'.$patient['id'])->with('success', 'Successfully created user');
    }

    public function verifyUser(Request $request,$user_id)
    {
        return View::make('site.user.verify-code',['user_id'=>$user_id]);
    }


    public function verifyEmail(Request $request)
    {
        $otp = $request->otp;
        $user_id = $request->user_id;

        if($otp && $user_id) {
            $user = Patient::find($user_id);
            if($user) {
                $temp_pass = $user->temp_pass;
                if(isset($user) && $user->otp==$request->otp) {
                    if($user->verified_at == ''){
                        $user->verified_at = Carbon::now();
                        $user->status = 1;
                        $user->otp = null;
                       // $user->token_sent_on = null;
                        $user->temp_pass = null;
                       $user->save();
                        $this->_userSession($user,$temp_pass);
                        return redirect('/');
                    } else {
                        return redirect('verify-user/'.$user_id)->with('error','User is already verified');
                    }
                } else {
                    return redirect('verify-user/'.$user_id)->with('error','Please enter valid otp');
                }
            } else {
                return redirect('verify-user/'.$user_id)->with('error','Invalid User');
            }
        } else {
            return redirect('verify-user/'.$user_id)->with('error','Invalid Parameters');
        }
    }

    private function _userSession($user_info,$temp_pass)
    {
        $user = Patient::where('id',$user_info->id)->get()->toArray();
        $user_arr = array_merge($user[0],array('password'=>decrypt($temp_pass)));


        Auth::guard('user')->attempt(array(
            'email' => $user_arr['email'],
            'password' => $user_arr['password']
        ));
        //echo '<pre>'; print_r(Auth::guard('user')->user()); exit;
    }

    public function login(Request $request)
    {
        return View::make('site.user.login');
    }
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function loginSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect('sign-in')->withErrors($validator->errors());
        }

        $attempt_by_email = $user = Patient::where('email', $request->email)->first();
       /* if(!$attempt_by_email) {
            $attempt_by_phone = $user = Patient::where('phone', $request->email)->first();
            if(!$attempt_by_phone) {
                $attempt_by_phone = $user = Patient::whereRaw("CONCAT(`phone_code`, `phone`) = ?", [$request->email])->first();
                $request->email = substr($request->email, 2);
            }
        }*/
        if(!$user) {
            return redirect('sign-in')->with('error', 'Invalid Credentials');
        }
        if($attempt_by_email) {
            $credentials = ['email' => $request->email, 'password' => $request->password];
        } else {
            $credentials = ['phone' => $request->email, 'password' => $request->password];
        }
        if(!Auth::guard('user')->attempt($credentials)) {
            return redirect('sign-in')->with('error', 'Invalid Credentials');
        }

        if($user->status != 1){
            return redirect('sign-in')->with('error', 'User is not verified');
        }
        return redirect('/');
    }

    /**
     * Facebook Signup & Login user and create token
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] provider_id
     * @param  [string] social_provider
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function socialLogin(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if(!$user) {
            $validationRules = [
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'provider_id' => 'required|string',
                'social_provider' => 'required|string'
            ];

            $validator = Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Parameters Mismatch',
                    'status' => 0,
                    'error' => $validator->errors()
                ], 401);
            }

            $user = new User([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'provider_id' => $request->get('provider_id'),
                'social_provider' => $request->get('social_provider'),
                'status' => 1,
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);
            $user->save();

            $role = Role::where('name', 'User')->first();
            if(!$role) {
                $role = Role::create(['name' => 'User']);
            }

            $permissions = ['user'];
            $role->givePermissionTo($permissions);

            $user->assignRole($role);

            $data['user'] = $user;
        }

        if(!Auth::loginUsingId($user->id))
            return response()->json([
                'message' => 'Unauthorized',
                'status' => 0
            ], 401);

        if($request->user()->status != 1)
            return response()->json([
                'message' => 'User is not verified',
                'status' => 0
            ], 401);

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;

        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);

        $token->save();

        $device_type = $request->has('device_type') ? $request->device_type : '';
        $device_token = $request->has('device_token') ? $request->device_token : '';

        if($device_token && $device_type) {
            $deviceToken = DeviceTokens::where('user_id', $user->id)->first();
            if(!$deviceToken) {
                $deviceToken = new DeviceTokens;
            }

            $deviceToken->user_id       = $user->id;
            $deviceToken->device_type   = $device_type;
            $deviceToken->device_token  = $device_token;

            $deviceToken->save();
        }

        return response()->json([
            'status' => 1,
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString(),
            'user' => $user
        ]);
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        try {
            Auth::guard('user')->logout();
          //  $deviceToken = DeviceTokens::where('user_id', $request->user()->id)->delete();
           // $request->user()->token()->revoke();
        } catch(Exception $exception) {
            return $exception;
            if($exception instanceof \Illuminate\Auth\AuthenticationException ){
                return redirect()->back()->with('error','Unauthorized');
            }
        }
        return redirect('/');
    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        $user = User::with('city', 'country')->find($request->user()->id);
        return response()->json($user);
    }

    /**
     * Change Password
     *
     * @return [json] status, message
     */
    public function changePassword(Request $request){
        $rules = [
            'current_password'=>'required',
            'new_password'=>'required|min:8',
        ];

        $this->validate($request, $rules);

        $password = Auth::guard('user')->user()->getAuthPassword();

        if(\Illuminate\Support\Facades\Hash::check($request->current_password, $password)){
            $patient = Patient::find(Auth::guard('user')->user()->id);
            $patient->password = Hash::make($request->new_password);
            $patient->save();

            return redirect('change-password')->with('success', 'Password has been changed');
        }

        return redirect('change-password')->with('error', 'Current password is incorrect');
    }

    public function getForgotPassword(Request $request)
    {
        $email = $request->email;

        $attempt_by_email = $user = User::where('email', $request->email)->where('email_verified_at', '!=', '')->first();
        if(!$attempt_by_email) {
            $attempt_by_phone = $user = User::where('phone', $request->email)->where('phone_verified_at', '!=', '')->first();
            if(!$attempt_by_phone) {
                $attempt_by_phone = $user = User::whereRaw("CONCAT(`country_code`, `phone`) = ?", [$request->email])->where('phone_verified_at', '!=', '')->first();
            }
        }

        if(!$user) {
            return response()->json(['status' => 0, 'message' => "Email doesn't exist in our records"], 401);
        }

        // $otp = mt_rand(1000, 9999);
        $otp = 1234;

        $user->token = $otp;

        $user->save();

        $data['user'] = $user;

        if($attempt_by_email) {
            try {
                Mail::send('emails.verify_email', $data, function($message) use ($user) {
                    $message->to($user->email, $user->first_name)->subject('Forgot Password | Train60');
                    $message->from('admin@train60.com','Train60');
                });
            } catch (\Exception $ex) {

            }
        } elseif(isset($attempt_by_phone) && $attempt_by_phone) {
            try {
                $sid = env('TWILIO_SID');
                $token = env('TWILIO_TOKEN');
                $client = new Client($sid, $token);
                $res = $client->messages->create(
                    '+' . $user->country_code . $user->phone,
                    [
                        'from' => env('TWILIO_FROM'),
                        'body' => 'Hi ' . $user->first_name . ', Your verification token is ' . $user->token,
                    ]
                );
            } catch (\Exception $ex) {

            }
        }

        return response()->json(['status' => 1, 'message' => "Email Sent"]);
    }

    public function postForgotPassword(Request $request)
    {
        $email = $request->email;
        $otp = $request->otp;

        if(!$otp || !$email) {
            return response()->json(['status' => 0, 'message' => "Invalid Parameters"], 401);
        }

        $attempt_by_email = $user = User::where('email', $request->email)->first();
        if(!$attempt_by_email) {
            $attempt_by_phone = $user = User::where('phone', $request->email)->first();
            if(!$attempt_by_phone) {
                $attempt_by_phone = $user = User::whereRaw("CONCAT(`country_code`, `phone`) = ?", [$request->email])->first();
            }
        }

        if(!$user) {
            return response()->json(['status' => 0, 'message' => "Please enter valid OTP"], 401);
        }

        return response()->json(['status' => 1, 'message' => "OTP is valid"]);
    }

    public function updatePassword(Request $request)
    {
        $new_password = $request->get('new_password');
        $confirm_password = $request->get('new_password_confirmation');
        $email = $request->get('email');
        $otp = $request->get('otp');

        if(!$otp || !$email) {
            return response()->json(['status' => 0, 'message' => "Invalid Parameters"], 401);
        }

        $attempt_by_email = $user = User::where('email', $request->email)->first();
        if(!$attempt_by_email) {
            $attempt_by_phone = $user = User::where('phone', $request->email)->first();
            if(!$attempt_by_phone) {
                $attempt_by_phone = $user = User::whereRaw("CONCAT(`country_code`, `phone`) = ?", [$request->email])->first();
            }
        }

        if(!$user) {
            return response()->json(['status' => 0, 'message' => "Please enter valid OTP"], 401);
        }

        $validator = Validator::make($request->all(), ['new_password' => 'required|string|min:8|max:16|confirmed']);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'error' => $validator->errors()
            ], 401);
        }

        $user->password = bcrypt($new_password);
        $user->token = null;
        $user->save();
        return response(['status' => 1, 'message' => 'Password Updated Successfully'], 200);
    }
}
