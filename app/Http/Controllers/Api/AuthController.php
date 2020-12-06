<?php
namespace App\Http\Controllers\Api;
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


class AuthController extends Controller
{
    public function verifyEmail(Request $request)
    {
        $otp = $request->otp;
        $user_id = $request->user_id;

        if($otp && $user_id) {
            $user = User::find($user_id);
            if($user) {
                if($otp == $user->token) {
                    if($user->token_sent_on == 'email' && $user->email_verified_at == '' || $request->email) {
                        $user->email_verified_at = date('Y-m-d H:i:s');
                        $user->token = null;
                        $user->token_sent_on = null;
                        $user->temp_pass = null;
                        if($request->email) {
                            $user->email = $request->email;
                        }
                        $user->save();

                        return response()->json([
                            'message' => 'Email is verified successfully',
                            'status' => 1,
                            'user' => $user
                        ], 200);
                    } elseif ($user->token_sent_on == 'phone' && $user->phone_verified_at == '' || $request->phone) {
                        $user->phone_verified_at = date('Y-m-d H:i:s');
                        $user->token = null;
                        $user->token_sent_on = null;
                        $user->temp_pass = null;
                        if($request->phone) {
                            $user->phone = $request->phone;
                            $user->country_code = $request->country_code;
                        }
                        $user->save();

                        return response()->json([
                            'message' => 'Phone Number is verified successfully',
                            'status' => 1,
                            'user' => $user
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'User is already verified',
                            'status' => 0
                        ], 401);
                    }
                } else {
                    return response()->json([
                        'message' => 'Please enter valid otp',
                        'status' => 0
                    ], 401);
                }
            } else {
                return response()->json([
                    'message' => 'Invalid User',
                    'status' => 0
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Invalid Parameters',
                'status' => 0
            ], 401);
        }
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

    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
            'email' => (!$request->user_id) ? 'string|email|unique:users' : 'string|email|unique:users,email,'.$request->user_id,
            'country_code' => 'string',
            'phone' => (!$request->user_id) ? 'numeric|unique:users|digits_between:9,14' : 'numeric|unique:users|digits_between:9,14,phone,'.$request->user_id,
            'password' => 'required|string|min:8|max:16|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Parameters Mismatch',
                'status' => 0,
                'error' => $validator->errors()
            ], 401);
        }
        if($request->user_id) {
            $user = User::find($request->user_id);
            if(!$user) {
                return response()->json([
                    'message' => 'Invalid User ID'
                ], 401);
            }
        } else {
            $user = new User();
        }
        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'country_code' => $request->country_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'status' => 1,
            'token_sent_on' => ($request->email) ? 'email' : 'phone',
            'token' => mt_rand(1000, 9999),
            'temp_pass' => encrypt($request->password)
//            'token' => 1234
        ];
        $user->fill($data);
        $user->save();

        $role = Role::where('name', 'User')->first();
        if(!$role) {
            $role = Role::create(['name' => 'User']);
        }

        $permissions = ['user'];
        $role->givePermissionTo($permissions);

        $user->assignRole($role);

        $data['user'] = $user;

        if($user->email) {
            try {
                Mail::send('emails.verify_email', $data, function ($message) use ($user) {
                    $message->to($user->email, $user->first_name)->subject('Email Verification | Train60');
                    $message->from('admin@train60.com', 'Train60');
                });
            } catch (\Exception $ex) {

            }
        } elseif($user->phone) {
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

        return response()->json([
            'message' => 'Successfully created user!',
            'status' => 1,
            'user' => $user
        ], 201);
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
    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Parameters Mismatch'
            ], 401);
        }

        $attempt_by_email = $user = User::where('email', $request->email)->first();
        if(!$attempt_by_email) {
            $attempt_by_phone = $user = User::where('phone', $request->email)->first();
            if(!$attempt_by_phone) {
                $attempt_by_phone = $user = User::whereRaw("CONCAT(`country_code`, `phone`) = ?", [$request->email])->first();
                $request->email = substr($request->email, 2);
            }
        }
        if(!$user) {
            return response()->json([
                'message' => 'Invalid Credentials',
                'status' => 0
            ], 401);
        }
        if($attempt_by_email) {
            $credentials = ['email' => $request->email, 'password' => $request->password];
        } else {
            $credentials = ['phone' => $request->email, 'password' => $request->password];
        }
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Invalid Credentials',
                'status' => 0
            ], 401);

        if($attempt_by_email && $request->user()->email_verified_at == '') {

            $user = $request->user();
            $data['user'] = $user;

            try {
                Mail::send('emails.verify_email', $data, function($message) use ($user) {
                    $message->to($user->email, $user->first_name)->subject('Email Verification | Train60');
                    $message->from('admin@train60.com','Train60');
                });
            } catch (\Exception $ex) {

            }

            return response()->json([
                'message' => 'User is not verified',
                'status' => 2,
                'user' => $request->user()
            ], 200);
        } elseif(isset($attempt_by_phone) && $attempt_by_phone && $request->user()->phone_verified_at == '') {
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

            return response()->json([
                'message' => 'User is not verified',
                'status' => 2,
                'user' => $request->user()
            ], 200);
        }

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
                $deviceToken->user_id       = $user->id;
            }

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

            $deviceToken = DeviceTokens::where('user_id', $request->user()->id)->delete();

            $request->user()->token()->revoke();
        } catch(Exception $exception) {
            return $exception;
            if($exception instanceof \Illuminate\Auth\AuthenticationException ){
                return response()->json([
                    'message' => 'Unauthorized',
                    'status' => 0
                ], 401);
            }
        }
        return response()->json([
            'message' => 'Successfully logged out',
            'status' => 1
        ]);
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
    public function changePassword(Request $request)
    {
        $user = User::find($request->user()->id);
        $password = $request->get('password');
        $new_password = $request->get('new_password');
        $confirm_password = $request->get('new_password_confirmation');
        $validator = Validator::make($request->all(), ['new_password' => 'required|string|min:8|max:16|confirmed']);

        if ($validator->fails()) {
            return response()->json([
                'status' => 0,
                'error' => $validator->errors()
            ], 401);
        }
        if(Hash::check($request->password, $user->password)) {
            if($new_password == $confirm_password) {
                $user->password = bcrypt($new_password);
                $user->save();
                return response(['status' => 1, 'message' => 'Password Updated Successfully'], 200);
            }
        }

        return response(['status' => 0, 'message' => 'please enter valid password'], 401);
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
