<?php

namespace App\Http\Controllers\Admin\Sessions;

use App\Commission;
use App\Vouchers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Maatwebsite\Excel\Excel;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\PushNotification;
use App\User;
use App\Gym;
use App\Offers;
use App\Payments;
use App\UserSessions;
use App\DeviceTokens;
use App\UserCards;
use App\Settings;
use App\Exports\SessionsExport;

class IndexController extends \App\Http\Controllers\Admin\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function sessions(Request $request)
    {
        $data = $request->all();
        $data['sessions'] = UserSessions::sessions($request);


        $data['menu_active']    = 'sessions';
        $data['title'] = "Sessions";

        $data['listings'] = Gym::pluck_data();
        $data['offers'] = Offers::pluck_data();
        $data['users'] = User::pluck_data();
        $data['list_payment_status'] = Payments::where('status', '!=', '')->groupBy('status')->pluck('status', 'status');

        return view('admin.sessions.index', $data);
    }

    public function exportSessions(Request $request)
    {
        return \Maatwebsite\Excel\Facades\Excel::download(new SessionsExport($request), 'sessions.xlsx');
    }

    public function getCards(Request $request) {
        $session_id = $request->session_id;
        $session = UserSessions::find($session_id);

        if($session) {
            $cards = UserCards::where('user_id', $session->user_id)->get();
            if($cards) {
                return response()->json(['status' => 1, 'data' => $cards]);
            }
            return response()->json(['status' => 0, 'data' => 'No Cards Found']);
        } else {
            return response()->json(['status' => 0, 'data' => 'This Session is Expired']);
        }
    }

    public function scanOut(Request $request)
    {
        $session_id = $request->get('session_id');
        $card_id = $request->get('card_id');

        if($session_id && $card_id) {
            $session = UserSessions::whereStatus('on-going')->find($session_id);
            if($session) {
                $user = User::find($session->user_id);
                if($user) {
                    $card = UserCards::where('user_id', $user->id)->find($card_id);
                    if($card) {
                        $gym = Gym::where('status', 1)->find($session->gym_id);
                        if($gym) {
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
                                'gym_id'                  => $gym->id,
                                'gym_name'                => $gym->name,
                                'gym_logo'                => $gym->logo,
                                'gym_location'            => $gym->city->name . ', ' . $gym->town,
                                'session_start_datetime'  => $session->start_datetime,
                                'session_end_datetime'    => $session->end_datetime,
                                'initial_amount'          => $session->initial_amount,
                                'additional_amount'       => $session->additional_amount,
                                'discount'                => $session->discount,
                                'total_amount'            => $session->total_amount,
                                'duration_mins'           => $session->time_spend_minutes,
                                'duration_time'           => $duration_in_seconds,
                            ];

                            if(isset($pay) && isset($pay['result'])) {
                                $res['payment_response'] = $pay['result'];
                                $res['status']           = $payment->status;
                            }

                            if(isset($pay) && isset($pay['response_code']) && $pay['response_code'] == '100') {
                                $data = ['success' => 'Session Completed & Paid Successfully'];
                            } else {
                                $data = ['success' => 'Session Completed but not paid'];
                            }
                        } else {
                            $data = ['error' => 'Invalid Gym ID'];
                        }
                    } else {
                        $data = ['error' => 'Invalid Card ID'];
                    }
                } else {
                    $data = ['error' => 'No User Found'];
                }
            } else {
                $data = ['error' => 'No Active Session Found'];
            }
        } else {
            $data = ['error' => 'Invalid Parameters'];
        }

        return redirect()->route('admin.sessions.detail', $session->id)->with($data);
    }

    public function detail($session_id)
    {
        $data['title'] = 'View Session';
        $data['session'] = UserSessions::find($session_id);

        if(!$data['session']) {
            abort(404, 'Page not found');
        }

        $data['user_history'] = UserSessions::where('user_id', $data['session']->user_id)->orderBy('id', 'desc')->take(5)->get();

        return view('admin.sessions.detail', $data);
    }
}
