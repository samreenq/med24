<?php
namespace App\Http\Controllers\Api;
use App\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Gym;
use App\Offers;
use App\UserSessions;
use App\UserCards;
use App\Payments;
use App\Vouchers;
use App\Settings;
use App\DeviceTokens;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;


class SessionController extends Controller
{

    public function scanIn($step, Request $request)
    {
        $token = $request->get('token');
        $code = $request->get('voucher');
        $user = $request->user();
        
        if($token && $user) {
            $session = UserSessions::where('user_id', $user->id)->whereStatus('on-going')->first();
            if($session) {
                return response()->json([
                    'status' => 0,
                    'data' => 'Session already running'
                ], 401);
            }

            $session_payment = Payments::where('user_id', $user->id)->whereStatus('DUE')->first();
            if($session_payment) {
                return response()->json([
                    'status' => 2,
                    'data' => 'You have an in complete payment in your account, please complete your due amount to start a new session.',
                    'total_amount' => $session_payment->amount,
                    'gym_name' => $session_payment->session->gym->name
                ], 401);
            }

            $cards = UserCards::where('user_id', $user->id)->get();
            if(count($cards) <= 0) {
                return response()->json([
                    'status' => 0, 
                    'data' => 'Please add payment gateway to proceed'
                ], 401);
            }
            $gym = Gym::where('checkin_token', $token)->where('status', 1)->first();
            if($gym) {
                $offer = Offers::getOffers($gym->id);
                if($step == 1) {
                    $res = [
                        'gym_id'                        => $gym->id,
                        'gym_name'                      => $gym->name,
                        'gym_logo'                      => $gym->logo,
                        'gym_location'                  => $gym->city->name . ', ' . $gym->town,
                        'initial_amount'                => $gym->get_price('', $user->id),
                        'additional_amount'             => $gym->get_additional_price('', $user->id),
                        'discount'                      => 1
                    ];

                    $transaction_history = UserSessions::transactionHistory($user);
                    $res['transaction_history'] = $transaction_history;

                    if($offer) {
                        $res['discount'] = 0;
                    }

                    return response()->json([
                        'status' => 1,
                        'data' => $res
                    ], 200);
                } else {

                    $session = new UserSessions();
                    $session->user_id        = $user->id;
                    $session->gym_id         = $gym->id;
                    $session->start_datetime = date('Y-m-d H:i:s');
                    $session->initial_amount = $gym->price;

                    if(count($offer) > 0) {
                        $session->offer_id = $offer[0]->id;
                        $session->discount = $offer[0]->get_discount($gym->price);
                    } elseif($code) {
                        $voucher = Vouchers::check_voucher($code, $gym->id, $user->id);
                        if(!$voucher) {
                            return response()->json([
                                'status' => 0,
                                'data' => 'Invalid Voucher'
                            ], 401);
                        }

                        $redeemed = Vouchers::redeem_voucher($voucher->name);
                        if(!$redeemed) {
                            return response()->json([
                                'status' => 0,
                                'data' => 'Invalid Voucher'
                            ], 401);
                        }

                        $session->voucher_id = $voucher->id;
                        $session->code = $voucher->name;
                        $session->discount = $voucher->get_discount($gym->price);
                    }
                    $session->status = 'on-going';

                    $session->save();

                    $device = DeviceTokens::where('user_id', $request->user()->id)->first();

                    if($device) {
                        $title = "Scan In";
                        $message = "Test";
                        $notification = app('App\Http\Controllers\Api\NotificationController')->sendPushNotification($device->device_token, $title, $message);
                    }

                    $res = [
                        'session_id'                    => $session->id,
                        'gym_id'                        => $gym->id,
                        'gym_name'                      => $gym->name,
                        'gym_logo'                      => $gym->logo,
                        'gym_location'                  => $gym->city->name . ', ' . $gym->town,
                        'session_start_datetime'        => $session->start_datetime,
                        'initial_amount'                => $gym->get_price($code, $user->id),
                        'additional_amount'             => $gym->get_additional_price($code, $user->id),
                        'first_charge_after_mins'       => Settings::get_value('first_charge_after_mins'),
                        'additional_charge_after_mins'  => Settings::get_value('additional_charge_after_mins'),
                        'additional_charge_loop_mins'   => Settings::get_value('additional_charge_loop_mins'),
                        'vat'                           => Settings::get_value('vat') . '%',
                        'notification'                  => isset($notification) ? $notification : '',
                    ];

                    $transaction_history = UserSessions::transactionHistory($user, $session);
                    $res['transaction_history'] = $transaction_history;
                }

                return response()->json([
                    'status' => 1,
                    'message' => 'Session Started',
                    'data' => $res
                ], 200);
            } else {
                $gym = Gym::where('checkout_token', $token)->where('status', 1)->first();
                if($gym) {
                    return response()->json([
                        'status' => 0,
                        'data' => 'This is a scan-out QR code, please scan the scan-in code'
                    ], 401);
                }
                return response()->json([
                    'status' => 0,
                    'data' => 'Invalid Token'
                ], 401);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'something went wrong, please try again later!'
            ], 401);
        }
    }

    public function scanOut(Request $request)
    {
        $token = $request->get('token');
        $card_id = $request->get('card_id');
        $user = $request->user();

        $card = UserCards::where('user_id', $user->id)->find($card_id);

        if(!$card) {
            return response()->json([
                'status' => 0,
                'data' => 'Please select a valid card'
            ], 401);
        }

        if($token && $user) {
            $session = UserSessions::where('user_id', $user->id)->whereStatus('on-going')->first();
            if(!$session) {
                return response()->json([
                    'status' => 0,
                    'data' => 'No running sessions found'
                ], 401);
            }

            $gym = Gym::where('checkout_token', $token)->where('status', 1)->first();
            if($gym) {
                if($gym->id == $session->gym_id) {

                    $start_datetime = \Carbon::parse($session->start_datetime);
                    $end_datetime = \Carbon::parse(date('Y-m-d H:i:s'));
                    $diff = $start_datetime->diffInMinutes($end_datetime);
                    $duration = gmdate($diff);

                    $diff_in_seconds = $start_datetime->diffInSeconds($end_datetime);
                    $duration_in_seconds = gmdate('H:i:s', $diff_in_seconds);

                    if($duration > Settings::get_value('first_charge_after_mins')) {

                        $additional_discount = 0;
                        $additional_minutes = $duration - Settings::get_value('additional_charge_after_mins');
                        $additional_amount = 0;
                        if($additional_minutes > 0) {
                            $additional_periods = ceil($additional_minutes / Settings::get_value('additional_charge_loop_mins')) * Settings::get_value('additional_charge_loop_mins') / Settings::get_value('additional_charge_loop_mins');
                            $additional_amount = $gym->additional_price * $additional_periods;

                            $offer = Offers::find($session->offer_id);
                            if($offer) {
                                $additional_discount = $offer->get_discount($gym->additional_price * $additional_periods);
                            } elseif($session->code) {
                                $voucher = Vouchers::check_voucher($session->code, $gym->id, $user->id, 2);
                                if($voucher) {
                                    $additional_discount = $voucher->get_discount($gym->additional_price * $additional_periods);
                                    if($voucher->discount_unit == 'free_trainings') {
                                        $free = 1;
                                    }
                                }
                            }
                        }

                        $vat_percent = Settings::get_value('vat');
                        $vat_amount = 0;
                        $sub_total = $session->initial_amount + $additional_amount;
                        if($vat_percent) {
                            $vat_amount = $sub_total / 100 * $vat_percent;
                        }

                        $discount = $session->discount + $additional_discount;

                        $session->end_datetime        = date('Y-m-d H:i:s');
                        $session->time_spend_minutes  = $duration;
                        $session->additional_amount   = $additional_amount;
                        $session->vat                 = $vat_amount;
                        if(isset($free) && $free == 1) {
                            $session->discount        = $sub_total;
                            $session->total_amount    = 0;
                        } else {
                            $session->discount        = $discount;
                            $session->total_amount    = $sub_total - $discount + $vat_amount;
                        }
                        $session->status              = 'completed';
                    } else {
                        $session->end_datetime        = date('Y-m-d H:i:s');
                        $session->time_spend_minutes  = $duration;
                        $session->initial_amount      = "0";
                        $session->additional_amount   = "0";
                        $session->discount            = "0";
                        $session->total_amount        = "0";
                        $session->status              = 'completed';
                    }

                    $session->save();

                    if($gym->parent_id) {
                        $parent = Gym::find($gym->parent_id);
                        if(!$parent) {
                            $parent = $gym;
                        }
                    } else {
                        $parent = $gym;
                    }

                    if($parent) {
                        $commission = new Commission();
                        $commission->gym_id = $session->gym_id;
                        $commission->session_id = $session->id;
                        $commission->total_amount = $session->total_amount;
                        $commission->commission_percent = $parent->commission;
                        $commission->commission_amount = ($parent->commission) ? $session->total_amount / $parent->commission : 0;
                        $commission->save();
                    }

                    if($session->total_amount > 0) {
                        $payment_res = Payments::addPayment($session, $card);
                        $payment = $payment_res['payment'];
                        $pay = $payment_res['pay'];
                    }

                    $device = DeviceTokens::where('user_id', $request->user()->id)->first();

                    if($device) {
                        $title = "Scan Out";
                        $message = "Test";
                        $notification = app('App\Http\Controllers\Api\NotificationController')->sendPushNotification($device->device_token, $title, $message);
                    }

                    $check_reward = UserSessions::checkReward($request, $user);
                    if($check_reward) {
                        $device = DeviceTokens::where('user_id', $user->id)->first();

                        if ($device) {
                            $title = "Train60";
                            $message = "Congratulations! you have completed your " . $check_reward->no_of_sessions . " trainings. Scan In now to get your rewards.";
                            $notification = app('App\Http\Controllers\Api\NotificationController')->sendPushNotification($device->device_token, $title, $message);
                        }
                    }

                    $res = [
                        'session_id'              => $session->id,
                        'gym_id'                  => $gym->id,
                        'gym_name'                => $gym->name,
                        'gym_logo'                => $gym->logo,
                        'gym_location'            => $gym->city->name . ', ' . $gym->town,
                        'session_start_datetime'  => $session->start_datetime,
                        'session_end_datetime'    => $session->end_datetime,
                        'initial_amount'          => Settings::get_value('currency') . ' ' . $session->initial_amount,
                        'additional_amount'       => Settings::get_value('currency') . ' ' . $session->additional_amount,
                        'discount'                => Settings::get_value('currency') . ' ' . $session->discount,
                        'vat'                     => Settings::get_value('currency') . ' ' . $session->vat,
                        'total_amount'            => Settings::get_value('currency') . ' ' . $session->total_amount,
                        'duration_mins'           => $session->time_spend_minutes,
                        'duration_time'           => $duration_in_seconds,
                    ];

                    if(isset($pay) && isset($pay['result'])) {
                        $res['payment_response'] = $pay['result'];
                        $res['status']           = $payment->status;
                    }

                    $transaction_history = UserSessions::transactionHistory($user, $session);
                    $res['transaction_history'] = $transaction_history;

                    return response()->json([
                        'status' => 1,
                        'message' => 'Session completed',
                        'data' => $res
                    ], 200);
                } else {
                    return response()->json([
                        'status' => 0,
                        'data' => 'Invalid Token'
                    ], 401);
                }

            } else {
                $gym = Gym::where('checkin_token', $token)->where('status', 1)->first();
                if($gym) {
                    return response()->json([
                        'status' => 0,
                        'data' => 'This is a scan-in QR code, please scan the scan-out code'
                    ], 401);
                }
                return response()->json([
                    'status' => 0,
                    'data' => 'Invalid Token'
                ], 401);
            }
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'something went wrong, please try again later!'
            ], 401);
        }

        return response()->json([
            'status' => 1,
            'data' => $listings
        ], 200);
    }

    public function checkSession(Request $request)
    {
        $user = $request->user();

        if($user) {
            $session = UserSessions::where('user_id', $user->id)->whereStatus('on-going')->first();
            if(!$session) {
                return response()->json([
                    'status' => 0,
                    'message' => "User doesn't have any active sessions"
                ], 401);
            }

            $res = [
                'gym_id'                        => $session->gym->id,
                'gym_name'                      => $session->gym->name,
                'gym_logo'                      => $session->gym->logo,
                'gym_location'                  => $session->gym->city->name . ', ' . $session->gym->town,
                'session_start_datetime'        => $session->start_datetime,
                'initial_amount'                => $session->gym->get_price($session->code, $user->id),
                'additional_amount'             => $session->gym->get_additional_price($session->code, $user->id),
                'first_charge_after_mins'       => Settings::get_value('first_charge_after_mins'),
                'additional_charge_after_mins'  => Settings::get_value('additional_charge_after_mins'),
                'additional_charge_loop_mins'   => Settings::get_value('additional_charge_loop_mins'),
            ];

            return response()->json([
                'status' => 1,
                'data' => $res
            ], 200);
        }

        return response()->json([
            'status' => 0,
            'message' => "User Not Found"
        ], 401);
    }

    function create_pay_page() {
        $values['merchant_email'] = 'waleed.jawaid@salsoft.biz';
        $values['secret_key'] = 'VvbggEvjXsoaxLnnst5c5lJkhUlJiXbesnuJ202Zsra0cxnF0sR6NAq5uMPNK11dowWqwZqYqAZD7mi8P9eDJFIesy1cz2qXzmo5';
        $values['title'] = 'test';
        $values['cc_first_name'] = 'john';
        $values['cc_last_name'] = 'Doe';
        $values['order_id'] = '123';
        $values['product_name'] = 'Gymnation';
        $values['customer_email'] = 'abcd1234@test.net';
        $values['phone_number'] = '33333333';
        $values['amount'] = '10.00';
        $values['currency'] = 'AED';
        $values['address_billing'] = 'manama bahrain';
        $values['state_billing'] = 'manama';
        $values['city_billing'] = 'manama';
        $values['postal_code_billing'] = '00973';
        $values['country_billing'] = 'BHR';
        $values['address_shipping'] = 'Juffair bahrain';
        $values['city_shipping'] = 'manama';
        $values['state_shipping'] = 'manama';
        $values['postal_code_shipping'] = '00973';
        $values['country_shipping'] = 'BHR';
        $values['pt_token'] = 'OCZ7XwCl52I86R1zdZkB00Dw7f6LQPhE';
        $values['pt_customer_email'] = 'abcd1234@test.net';
        $values['pt_customer_password'] = 'F2v7CADAIw';
        $values['billing_shipping_details'] = 'no';

        return $this->runPost('https://www.paytabs.com/apiv3/tokenized_transaction_prepare', $values);
    }

    function runPost($url, $fields) {
        $fields_string = "";
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        $fields_string = rtrim($fields_string, '&');
        $ch = curl_init();
        $ip = $_SERVER['REMOTE_ADDR'];
        $ip_address = array(
            "REMOTE_ADDR" => $ip,
            "HTTP_X_FORWARDED_FOR" => $ip
        );
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function payLastSession(Request $request)
    {
        $user = $request->user();
        $card_id = $request->card_id;

        $session = UserSessions::where('user_id', $user->id)->whereHas('payments', function($q){
            $q->where('status', 'DUE');
        })->first();

        if(!$session) {
            return response()->json([
                'status' => 0,
                'message' => 'No pending payments found'
            ], 401);
        }

        $card = UserCards::find($card_id);

        if(!$session) {
            return response()->json([
                'status' => 0,
                'message' => 'Please select a valid card for payment'
            ], 401);
        }

        $payment_res = Payments::addPayment($session, $card);
        $payment = $payment_res['payment'];
        $pay = $payment_res['pay'];

        if(isset($pay['response_code']) && $pay['response_code'] == '100') {
            return response()->json([
                'status' => 1,
                'message' => 'Payment has been made successfully'
            ], 200);
        } else {
            return response()->json([
                'status' => 0,
                'message' => 'Unsuccesfull Payment',
                'response_code' => isset($pay['response_code']) ? $pay['response_code'] : '',
                'result' => isset($pay['result']) ? $pay['result'] : '',
            ], 401);
        }
    }
}
