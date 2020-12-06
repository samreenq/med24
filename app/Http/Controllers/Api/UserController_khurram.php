<?php
namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use App\UserCards;
use App\UserMedical;
use App\UserSessions;
use App\Gym;
use App\PushNotification;
use App\City;
use App\Country;
use App\Settings;
use App\Payments;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Vouchers;
use Twilio\Rest\Client;
use App\DeviceTokens;
use Illuminate\Validation\Rule;


class UserController extends Controller
{
    public function updateToken(Request $request)
    {
        $device_type = $request->device_type;
        $device_token = $request->device_token;
        $user = $request->user();

        if($device_token && $device_type && $user) {
            $deviceToken = DeviceTokens::where('user_id', $user->id)->first();
            if(!$deviceToken) {
                $deviceToken = new DeviceTokens;
                $deviceToken->user_id       = $user->id;
            }

            $deviceToken->device_type   = $device_type;
            $deviceToken->device_token  = $device_token;

            $deviceToken->save();
            
            return response()->json([
                'message' => 'Token Updated Successfully',
                'status' => 1,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Invalid Parameters',
                'status' => 0
            ], 401); 
        }
    }
    
    public function changeEmail(Request $request)
    {
        $user = $request->user();
        $email = $request->email;
        
        $rules = ['email' => 'required|string|email'];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Please Enter a valid Email',
                'status' => 0
            ], 401); 
        }
        
        if($user && $email) {
            $user = User::find($user->id);
            if($user) {
                if($user->email != $email) {
                    $email_val = User::where('email', $email)->first();
                    if(!$email_val) { 
                        // $user->email = $email;
                        // $user->email_verified_at = null;
                        $user->token_sent_on = 'email';
                         $user->token = mt_rand(1000, 9999);
//                        $user->token = 1234;
                        $user->save();

                        $user->email = $email;
                        $data['user'] = $user;
                        
                        try {
                            Mail::send('emails.verify_email', $data, function($message) use ($user) {
                                $message->to($user->email, $user->first_name)->subject('Email Verification | Train60');
                                $message->from('admin@train60.com','Train60');
                            });
                        } catch (\Exception $ex) {

                        }

                        return response()->json([
                            'message' => 'Check your email for otp confirmation',
                            'status' => 1,
                            'user' => $user
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'Email already exists',
                            'status' => 0
                        ], 401);
                    }
                } else {
                    return response()->json([
                        'message' => 'Please enter a different email',
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

    public function changePhone(Request $request)
    {
        $user = $request->user();
        $phone = $request->phone;
        $country_code = $request->country_code;
        
        $rules = ['phone' => 'required|numeric|digits_between:9,14', 'country_code' => 'required|numeric'];
        $validator = Validator::make($request->all(), $rules);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Please Enter a valid Phone Number',
                'status' => 0
            ], 401); 
        }

        if($user && $phone) {
            $user = User::find($user->id);
            if($user) {
                if($user->phone != $phone) {
                    $phone_val = User::where('phone', $phone)->first();
                    if(!$phone_val) {
                        // $user->phone = $phone;
                        // $user->phone_verified_at = null;
                        $user->token_sent_on = 'phone';
                         $user->token = mt_rand(1000, 9999);
//                        $user->token = 1234;
                        $user->save();

                        $data['user'] = $user;

                        try {
                            $sid = env('TWILIO_SID');
                            $token = env('TWILIO_TOKEN');
                            $client = new Client($sid, $token);
                            $res = $client->messages->create(
                                '+' . $country_code . $phone,
                                [
                                    'from' => env('TWILIO_FROM'),
                                    'body' => 'Hi ' . $user->first_name . ', Your verification token is ' . $user->token,
                                ]
                            );
                        } catch (\Exception $ex) {

                        }

                        return response()->json([
                            'message' => 'Check your phone for otp confirmation',
                            'status' => 1,
                            'user' => $user
                        ], 200);
                    } else {
                        return response()->json([
                            'message' => 'Phone number already exists',
                            'status' => 0
                        ], 401);
                    }
                } else {
                    return response()->json([
                        'message' => 'Please enter a different phone number',
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

    public function addCard(Request $request)
    {
        $user = $request->user();

        $data = $request->all();
        $data['user_id'] = $user->id;

        if(isset($data['token'])) {
            $card = UserCards::where('token', $data['token'])->where('user_id', $user->id)->first();

            if($card) {
                return response()->json([
                    'message' => 'Duplicate Entry',
                    'status' => 0,
                    'error' => ['card_number' => 'card already exist.']
                ], 401);
            }

            $card = new UserCards();
            $card = $card->store($data);

            if( $card instanceof \App\UserCards ) {
                return response()->json([
                    'status' => '1',
                    'message' => 'card added successfully',
                    'data' => $card
                ], 201);
            }

            return response()->json([
                'message' => 'Parameters Mismatch',
                'status' => 0,
                'error' => $card->errors()
            ], 401);
        }

        return response()->json([
            'message' => 'Parameters Mismatch',
            'status' => 0
        ], 401);
    }

    public function cards(Request $request)
    {
        $cards = UserCards::where('user_id', $request->user()->id)->get();

        if(count($cards) <= 0) {
            return response()->json([
                'message' => 'No record found',
                'status' => 0
            ], 401);
        }

        return response()->json([
            'status' => '1',
            'data' => $cards
        ], 200);
    }

    public function removeCard(Request $request)
    {
        $sessions = UserSessions::where('user_id', $request->user()->id)->where('status', 'on-going')->get();
        if(count($sessions) > 0) {
            return response()->json([
                'message' => 'Please complete the training before removing your card',
                'status' => 0
            ], 401);
        }
        $card_id = $request->get('card_id');
        $card = UserCards::find($card_id);

        if(!$card) {
            return response()->json([
                'message' => 'No record found',
                'status' => 0
            ], 401);
        }

        $card = $card->delete();

        if(!$card) {
            return response()->json([
                'message' => 'Something went wrong!',
                'status' => 0
            ], 401);
        }

        return response()->json([
            'status' => '1',
            'message' => 'Successfully Removed Card'
        ], 200);
    }

    public function medicalInfo(Request $request)
    {
        $medicalInfo = UserMedical::where('user_id', $request->user()->id)->first();

        if(!$medicalInfo) {
            return response()->json([
                'message' => 'No record found',
                'status' => 0
            ], 401);
        }

        $medicalInfo->age = $medicalInfo->getAgeAttribute();

        return response()->json([
            'status' => '1',
            'data' => $medicalInfo
        ], 200);
    }

    public function addMedicalInfo(Request $request)
    {
        $user = $request->user();

        $data = $request->all();
        $data['user_id'] = $user->id;

        $medicalInfo = UserMedical::where('user_id', $user->id)->first();

        if(!$medicalInfo) {
            $medicalInfo = new UserMedical();
        }

        $medicalInfo = $medicalInfo->store($data);

        if( $medicalInfo instanceof \App\UserMedical ) {
            return response()->json([
                'status' => '1',
                'message' => 'data updated successfully',
                'data' => $medicalInfo
            ], 201);
        }

        return response()->json([
            'message' => 'Parameters Mismatch',
            'status' => 0,
            'error' => $medicalInfo->errors()
        ], 401);
    }

    public function addFavouriteListing(Request $request)
    {
        $gym_id = $request->get('gym_id');
        $user = $request->user();

        if(!$gym_id || !$user) {
            return response()->json([
                'message' => 'Invalid Parameters'
            ], 401);
        }

        $listing = Gym::where('status', 1)->find($gym_id);

        if(!$listing) {
            return response()->json([
                'message' => 'No Record Found'
            ], 401);
        }

        $fav = $user->favourites()->detach($gym_id);
        $fav = $user->favourites()->attach($gym_id);

        return response()->json(['status' => 1, 'message' => 'Updated Successfully']);
    }

    public function getFavouriteListing(Request $request)
    {
        $user = User::with('favourites')->find($request->user()->id);

        $listings = [];

        $price_text = Settings::get_value('additional_charge_after_mins');
        $additional_price_text = Settings::get_value('additional_charge_loop_mins');

        foreach ($user->favourites as $favourite) {
            $ratings = DB::table('user_ratings')->where('gym_id', $favourite->id)->avg('ratings');

            $listing['id']               = $favourite->id;
            $listing['name']             = $favourite->name;
            $listing['price']            = $favourite->get_price();
            $listing['additional_price'] = $favourite->get_additional_price();
            $listing['price_text']              = ($price_text == 60) ? '1st hour' : '1st ' . $price_text . ' mins';
            $listing['additional_price_text']   = '/ ' . $additional_price_text . ' mins';
            $listing['logo']             = $favourite->logo;
            $listing['banner']           = $favourite->banner;
            $listing['gym_location']     = $favourite->city->name . ', ' . $favourite->town;
            $listing['gym_status']       = $favourite->get_status();
            $listing['ratings']          = ($ratings) ? $ratings - fmod($ratings, 0.5) : 0;

            $listings[] = $listing;
            unset($listing);
        }

        if(count($listings) <= 0) {
            return response()->json([
                'message' => 'No Record Found',
                'status' => 0
            ], 401);
        }

        return response()->json([
            'status' => '1',
            'data' => $listings
        ], 200);
    }

    public function removeFavouriteListing(Request $request)
    {
        $gym_id = $request->get('gym_id');
        $user = $request->user();

        if(!$gym_id || !$user) {
            return response()->json([
                'message' => 'Invalid Parameters'
            ], 401);
        }

        $listing = Gym::where('status', 1)->find($gym_id);

        if(!$listing) {
            return response()->json([
                'message' => 'No Record Found'
            ], 401);
        }

        $fav = $user->favourites()->detach($gym_id);

        return response()->json([
            'status' => '1',
            'message' => 'Successfully Removed Favourite Listing'
        ], 200);
    }

    public function profile(Request $request)
    {

        $user = User::with('city', 'country')->find($request->user()->id);

        if(!$user) {
            return response()->json([
                'status' => '0',
                'data' => 'User not found'
            ], 200);
        }

        $city = City::find($user->city_id);
        $country = Country::find($user->country_id);

        $user_data['id']                = $user->id;
        $user_data['first_name']        = $user->first_name;
        $user_data['last_name']         = $user->last_name;
        $user_data['email']         = $user->email;
        $user_data['country_code']         = $user->country_code;
        $user_data['phone']         = $user->phone;
        $user_data['gender']         = $user->gender;
        $user_data['city_id']         = $user->city_id;
        $user_data['city_name']         =  $city ? $city->name : '';
        $user_data['country_id']         = $user->country_id;
        $user_data['country_name']         = $country ? $country->name : '';
        $user_data['image']         = $user->image;
        $user_data['dob']         = $user->dob;
        $user_data['area']         = $user->area;


        return response()->json([
            'status' => '1',
            'data' => $user_data
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = User::find($request->user()->id);

        $user = $user->updateProfile($request);

        if( $user instanceof \App\User ) {

            $user = User::with('city', 'country')->find($user->id);

            return response()->json([
                'message' => 'Profile Updated Successfully',
                'status' => '1',
                'data' => $user
            ], 200);
        }

        return response()->json([
            'message' => 'Something went wrong',
            'status' => 0,
            'data' => $user->errors()
        ], 401);
    }

    public function transactionHistory(Request $request)
    {
        $user = $request->user();

        if($user) {
            $sessions = UserSessions::transactionHistory($user);

            if($sessions && count($sessions) > 0) {

                return response()->json([
                    'status' => '1',
                    'data' => $sessions
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Records Found',
                    'status' => 0
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Something went wrong',
                'status' => 0
            ], 401);
        }
    }

    public function workoutSummary(Request $request)
    {
        $user = $request->user();
        $id = $request->get('session_id');

        if($user && $id) {
            $session = UserSessions::with('gym')->where('user_id', $user->id)->where('status', 'completed')->where('id', $id)->first();

            if($session) {
                $ratings = DB::table('user_ratings')->where('gym_id', $session->gym->id)->where('user_id', $user->id)->first();

                $listing['gym_name']       = $session->gym->name;
                $listing['gym_logo']       = $session->gym->logo;
                $listing['gym_location']   = $session->gym->city->name . ', ' . $session->gym->town;
                $listing['ratings']        = ($ratings) ? $ratings->ratings : 0;
                $listing['start_datetime'] = $session->start_datetime;
                $listing['end_datetime']   = $session->end_datetime;
                $sub_total = $session->initial_amount + $session->additional_amount - $session->discount;
                $listing['sub_total']      = Settings::get_value('currency') . ' ' . $sub_total;
                $listing['vat']            = Settings::get_value('currency') . ' ' . $session->vat;
                $listing['amount']         = Settings::get_value('currency') . ' ' . $session->total_amount;

                $startTime = Carbon::parse($session->start_datetime);
                $finishTime = Carbon::parse($session->end_datetime);

                $totalDuration = $finishTime->diffInSeconds($startTime);
                $listing['time']          = gmdate('H:i:s', $totalDuration);

                return response()->json([
                    'status' => '1',
                    'data' => $listing
                ], 200);
            } else {
                return response()->json([
                    'message' => 'No Record Found',
                    'status' => 0
                ], 401);
            }
        } else {
            return response()->json([
                'message' => 'Parameters Mismatch',
                'status' => 0
            ], 401);
        }
    }

    public function widgets(Request $request)
    {
        $user = $request->user();

        if($user) {
            $query = UserSessions::where('user_id', $user->id)->where('status', 'completed');
            $trainings = $query->count();
            $gyms_used = $query->get()->unique('gym_id')->count();
            $time_spend = $query->sum('time_spend_minutes');


            $data['tainings']      = $trainings;
            $data['gyms_used']     = $gyms_used;
            $data['time_spend']    = ($time_spend > 0) ? $this->number_format_short($time_spend) : "0";

            return response()->json([
                'status' => '1',
                'data' => $data
            ], 200);

        } else {
            return response()->json([
                'message' => 'Parameters Mismatch',
                'status' => 0
            ], 401);
        }
    }

    public function steps(Request $request)
    {
        $user = $request->user();

        if($user) {
            $medical = UserMedical::where('user_id', $user->id)->first();
            $card = UserCards::where('user_id', $user->id)->first();

            $medical = ($medical) ? $medical->is_complete() : 0;
            $card = ($card) ? 1 : 0;
            $user = ($user) ? $user->is_complete() : 0;

            $steps = $medical + $card + $user;

            return response()->json([
                'status' => 1,
                'medical' => $medical,
                'payment' => $card,
                'profile' => $user,
                'total_steps' => $steps,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Parameters Mismatch',
                'status' => 0
            ], 401);
        }
    }

    public function notifications(Request $request)
    {
        $query = PushNotification::where('user_id', $request->user()->id)->orWhere('user_id', '')->get();

        if(!$query) {
            return response()->json([
                'status' => 0,
                'message' => 'No records found'
            ], 401);
        }

        $notifications = [];

        foreach ($query as $key => $row) {
            $notification['title'] = $row->title;
            $notification['message'] = $row->message;
            $notification['trigger_type'] = $row->trigger_type;
                $notification['trigger_id'] = $row->trigger_id;
                $notification['date'] = date('Y-m-d', strtotime($row->created_at));
                $notification['time'] = date('H:i', strtotime($row->created_at));

            $notifications[] = $notification;
            unset($notification);
        }

        return response()->json([
            'status' => 1,
            'data' => $notifications
        ], 200);
    }

    public function vouchers(Request $request)
    {
        $user = $request->user();
        $query = Vouchers::getVouchers('', $user->id);
        if(count($query) <= 0) {
            return response()->json([
                'status' => 0,
                'message' => 'No records found'
            ], 401);
        }

        $vouchers = [];

        foreach ($query as $q) {

            if($q->discount_unit == 'amount') {
                $title = Settings::get_value('currency') . ' ' . $q->discount;
            } elseif($q->discount_unit == '%') {
                $title = $q->discount . '% DISCOUNT';
            } else {
                $title = $q->count - $q->usage_count . ' Free Training(s)';
            }

            $voucher['code']                    = $q->name;
            $voucher['expiry_date']             = $q->end_datetime;
            $voucher['title']                   = $title;

            $vouchers[] = $voucher;
            unset($voucher);
        }

        return response()->json([
            'status' => 1,
            'data' => $vouchers
        ], 200);
    }

    function number_format_short( $n ) {
        if ($n > 0 && $n < 1000) {
            // 1 - 999
            $n_format = floor($n);
            $suffix = '';
        } else if ($n >= 1000 && $n < 1000000) {
            // 1k-999k
            $n_format = floor($n / 1000);
            $suffix = 'K+';
        } else if ($n >= 1000000 && $n < 1000000000) {
            // 1m-999m
            $n_format = floor($n / 1000000);
            $suffix = 'M+';
        } else if ($n >= 1000000000 && $n < 1000000000000) {
            // 1b-999b
            $n_format = floor($n / 1000000000);
            $suffix = 'B+';
        } else if ($n >= 1000000000000) {
            // 1t+
            $n_format = floor($n / 1000000000000);
            $suffix = 'T+';
        }

        return !empty($n_format . $suffix) ? $n_format . $suffix : 0;
    }
    
    public function makePayment(Request $request)
    {
        $validationRules = [
            'session_id' => [
                'required',
                Rule::exists('user_sessions', 'id')->where(function ($query) use($request){
                    $query->where('user_id', $request->user()->id);
                }),
            ],
            'card_id' => [
                'required',
                Rule::exists('user_cards', 'id')->where(function ($query) use($request){
                    $query->where('user_id', $request->user()->id);
                }),
            ]
        ];
        
        if( $validationRules ) {
            $validator = Validator::make($request->all(), $validationRules);

            if ($validator->fails()) {
                // dd($validator);
                return response()->json([
                    'status' => 0,
                    'data' => $validator->errors()
                ], 401);
            }
        }
        
        $session = UserSessions::find($request->session_id);
        $card = UserCards::find($request->card_id);
        $payment_res = Payments::addPayment($session, $card);
        $payment = $payment_res['payment'];
        $pay = $payment_res['pay'];
        $res = [];
        if(isset($pay) && isset($pay['result'])) {
            $res['payment_response'] = $pay['result'];
        }
        
        if(isset($pay) && isset($pay['response_code']) && $pay['response_code'] == '100') {
            return response()->json([
                'status' => 1,
                'data' => $payment
            ], 200);
        } else {
            return response()->json([
                'status' => 0,
                'message' => (isset($res['payment_response'])) ? $res['payment_response'] : 'Unable to make payment',
                'data' => $payment
            ], 401);
        }
        
        
    }
}
