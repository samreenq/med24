<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Payments;
use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Self_;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class UserSessions extends Model
{
    use LogsActivity;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'user_sessions';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'gym_id', 'offer_id', 'voucher_id', 'start_datetime', 'end_datetime', 'time_spend_minutes', 'initial_amount', 'additional_amount', 'discount', 'vat', 'total_amount', 'payment_method', 'status', 'code'
    ];

    protected static $logAttributes = [
        'user_id', 'gym_id', 'offer_id', 'voucher_id', 'start_datetime', 'end_datetime', 'time_spend_minutes', 'initial_amount', 'additional_amount', 'discount', 'vat', 'total_amount', 'payment_method', 'status', 'code'
    ];

    public function getDescriptionForEvent(string $eventName): string
    {
        if($eventName == 'created') {
            return 'Scan In';
        } elseif($eventName == 'updated') {
            return 'Scan Out';
        }
        return "User Session has been {$eventName}";
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function gym()
    {
        return $this->belongsTo('App\Gym');
    }

    public function offer()
    {
        return $this->belongsTo('App\Offers');
    }

    public function voucher()
    {
        return $this->belongsTo('App\Vouchers', 'voucher_id');
    }

    public function payments()
    {
        return $this->hasOne('App\Payments', 'session_id');
    }

    public function commission()
    {
        return $this->hasOne('App\Commission', 'session_id');
    }

    public static function sessions($request)
    {
        $query = Self::orderBy('status', 'desc');
        $query = $query->orderBy('id', 'desc');

        if($request->from) {
            $query = $query->where('start_datetime', '>=', $request->from);
        }

        if($request->to) {
            $query = $query->where('start_datetime', '<=', $request->to);
        }

        if($request->gym_id) {
            $query = $query->where('gym_id', $request->gym_id);
        }

        if($request->user_id) {
            $query = $query->where('user_id', $request->user_id);
        }

        if($request->offer_id) {
            $query = $query->where('offer_id', $request->offer_id);
        }

        if($request->payment_status) {
            $query = $query->whereHas('payments', function($q) use($request) {
                return $q->where('status', $request->payment_status);
            });
        }

        if(!\Auth::user()->can('admin')) {
            if(\Auth::user()->can('gym owner')) {
                $query = $query->whereHas('gym', function($q) use($request){
                    $q->where('owner_id', \Auth::user()->id);
                });
            } elseif(\Auth::user()->can('branch manager')) {
                $query = $query->whereHas('gym', function($q) use($request){
                    $q->where('branch_manager_id', \Auth::user()->id);
                });
            }
        }

        return $query->get();
    }

    public static function active_sessions()
    {

        if(\Auth::user()->can('admin')) {
            $active_sessions = UserSessions::where('status', 'on-going')->orderBy('id', 'desc')->take(10)->get();
        } elseif(\Auth::user()->can('gym owner')) {
            $active_sessions = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->where('status', 'on-going')->orderBy('id', 'desc')->take(10)->get();
        } else {
            $active_sessions = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->where('status', 'on-going')->orderBy('id', 'desc')->take(10)->get();
        }

        return $active_sessions;
    }

    public static function unpaid_sessions()
    {

        if(\Auth::user()->can('admin')) {
            $unpaid_sessions = UserSessions::whereHas('payments', function($q){
                $q->where('status', 'DUE');
            })->orderBy('id', 'desc')->take(10)->get();
        } elseif(\Auth::user()->can('gym owner')) {
            $unpaid_sessions = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->whereHas('payments', function($q){
                $q->where('status', 'DUE');
            })->orderBy('id', 'desc')->take(10)->get();
        } else {
            $unpaid_sessions = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->whereHas('payments', function($q){
                $q->where('status', 'DUE');
            })->orderBy('id', 'desc')->take(10)->get();
        }

        return $unpaid_sessions;
    }

    public static function session_count($date = '')
    {
        if(!$date) {
            $date = date('Y-m-d 00:00:00');
        }
        if(\Auth::user()->can('admin')) {
            $gyms = UserSessions::where('start_datetime', '>=', $date)->distinct()->pluck('gym_id');
        } elseif(\Auth::user()->can('gym owner')) {
            $gyms = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->where('start_datetime', '>=', $date)->distinct()->pluck('gym_id');
        } else {
            $gyms = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->where('start_datetime', '>=', $date)->distinct()->pluck('gym_id');
        }

        $sessions = [];

        foreach($gyms as $gym)
        {
            $gym_detail = Gym::find($gym);
            if($gym_detail) {
                $count = UserSessions::where('start_datetime', '>=', $date)->where('gym_id', $gym)->count();
                $sum_total = UserSessions::where('start_datetime', '>=', $date)->where('gym_id', $gym)->sum('total_amount');
                $sessions[] = ['gym_name' => $gym_detail->name, 'count' => $count, 'total' => $sum_total];
            }
        }

        return $sessions;
    }

    public static function finance($request)
    {
        $sessions = [];
        $period = CarbonPeriod::create($request->from, $request->to);
        foreach($period as $date) {
            $date = $date->format('Y-m-d');
            $tomorrow = date('Y-m-d', strtotime($date .' +1 day'));

            if(!$request->gym_id) {
                if(\Auth::user()->can('admin')) {
                    $gyms = UserSessions::where('start_datetime', '>=', $date)->where('start_datetime', '<', $tomorrow)->distinct()->pluck('gym_id');
                } elseif(\Auth::user()->can('gym owner')) {
                    $gyms = UserSessions::whereHas('gym', function($q){
                        $q->where('owner_id', \Auth::user()->id);
                    })->where('start_datetime', '>=', $date)->where('start_datetime', '<', $tomorrow)->distinct()->pluck('gym_id');
                } else {
                    $gyms = UserSessions::whereHas('gym', function($q){
                        $q->where('branch_manager_id', \Auth::user()->id);
                    })->where('start_datetime', '>=', $date)->where('start_datetime', '<', $tomorrow)->distinct()->pluck('gym_id');
                }
            } else {
                if(\Auth::user()->can('admin')) {
                    $gyms = UserSessions::where('start_datetime', '>=', $date)->where('start_datetime', '<', $tomorrow)->where('gym_id', $request->gym_id)->distinct()->pluck('gym_id');
                } elseif(\Auth::user()->can('gym owner')) {
                    $gyms = UserSessions::whereHas('gym', function($q) use($request){
                        $q->where('owner_id', \Auth::user()->id)->where('id', $request->gym_id);
                    })->where('start_datetime', '>=', $date)->where('start_datetime', '<', $tomorrow)->distinct()->pluck('gym_id');
                } else {
                    $gyms = UserSessions::whereHas('gym', function($q) use($request){
                        $q->where('branch_manager_id', \Auth::user()->id)->where('id', $request->gym_id);
                    })->where('start_datetime', '>=', $date)->where('start_datetime', '<', $tomorrow)->distinct()->pluck('gym_id');
                }
            }

            foreach($gyms as $gym) {
                // if($date == '2019-10-13') {
                //     return $query = Self::where('start_datetime', '>=', $date)->where('start_datetime', '<', $tomorrow)->get();
                // }
                $query = Self::where('start_datetime', '>=', $date)->where('start_datetime', '<', $tomorrow);

                if(!\Auth::user()->can('admin')) {
                    if(\Auth::user()->can('gym owner')) {
                        $query = $query->whereHas('gym', function($q) use($request){
                            $q->where('owner_id', \Auth::user()->id);
                        });
                    } elseif(\Auth::user()->can('branch manager')) {
                        $query = $query->whereHas('gym', function($q) use($request){
                            $q->where('branch_manager_id', \Auth::user()->id);
                        });
                    }
                }

                $total_amount = $query->where('gym_id', $gym)->sum('total_amount');

                if($total_amount) {
                    $commission = Commission::whereHas('session', function($q) use($date, $tomorrow){
                        $q->where('start_datetime', '>=', $date)->where('start_datetime', '<', $tomorrow);
                    })->where('gym_id', $gym)->sum('commission_amount');
                    $gym_detail = Gym::find($gym);
                    if($gym_detail) {
                        $parent = Gym::find($gym_detail->parent_id);
                    }

                    $session['branch'] = ($gym_detail) ? $gym_detail->name : $gym;
                    $session['gym'] = (isset($parent) && $parent) ? $parent->name : $session['branch'];
                    $session['date'] = $date;
                    $session['total_amount'] = $total_amount;
                    $session['commission'] = $commission;

                    $sessions[] = $session;
                    unset($session);
                }
            }
        }

        return $sessions;
    }

    public static function vouchersUsage($request)
    {
        $query = Self::query();

        if($request->from) {
            $query = $query->where('start_datetime', '>=', $request->from);
        }

        if($request->to) {
            $query = $query->where('start_datetime', '<', $request->to);
        }

        if($request->voucher) {
            $query = $query->where('code', $request->voucher);
        } else {
            $query = $query->where('code', '!=', '');
        }

        if(\Auth::user()->can('gym owner')) {

        } elseif(\Auth::user()->can('gym owner')) {
            $query = $query->whereHas('gym', function($q) use($request){
                $q->where('owner_id', \Auth::user()->id);
            });
        } elseif(\Auth::user()->can('branch manager')) {
            $query = $query->whereHas('gym', function($q) use($request){
                $q->where('branch_manager_id', \Auth::user()->id);
            });
        }

        return $query = $query->get();
    }

    public static function total_monthly_sessions()
    {
        $start_date = date('Y-m-01 00:00:00');
        $end_date = date('Y-m-t 23:59:59');
        if(\Auth::user()->can('admin')) {
            $monthly_count = UserSessions::where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->count();
        } elseif(\Auth::user()->can('gym owner')) {
            $monthly_count = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->count();
        } else {
            $monthly_count = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->count();
        }

        return $monthly_count;
    }

    public static function total_unpaid_sessions()
    {
        if(\Auth::user()->can('admin')) {
            $unpaid_count = UserSessions::where('status', 'completed')->whereHas('payments', function($q){
                $q->where('status', 'DUE');
            })->count();
        } elseif(\Auth::user()->can('gym owner')) {
            $unpaid_count = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->whereHas('payments', function($q){
                $q->where('status', 'DUE');
            })->where('status', 'completed')->count();
        } else {
            $unpaid_count = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->whereHas('payments', function($q){
                $q->where('status', 'DUE');
            })->where('status', 'completed')->count();
        }

        return $unpaid_count;
    }

    public static function total_todays_sessions()
    {
        $start_date = date('Y-m-d 00:00:00');
        $end_date = date('Y-m-d 23:59:59');
        if(\Auth::user()->can('admin')) {
            $todays_count = UserSessions::where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->count();
        } elseif(\Auth::user()->can('gym owner')) {
            $todays_count = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->count();
        } else {
            $todays_count = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->count();
        }

        return $todays_count;
    }

    public static function total_active_sessions()
    {
        if(\Auth::user()->can('admin')) {
            $active_count = UserSessions::where('status', 'on-going')->count();
        } elseif(\Auth::user()->can('gym owner')) {
            $active_count = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->where('status', 'on-going')->count();
        } else {
            $active_count = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->where('status', 'on-going')->count();
        }

        return $active_count;
    }

    public static function total_customers_trained()
    {
        $start_date = date('Y-m-01 00:00:00');
        $end_date = date('Y-m-t 23:59:59');
        if(\Auth::user()->can('admin')) {
            $monthly_count = UserSessions::where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->select('user_id')->distinct()->get();
        } elseif(\Auth::user()->can('gym owner')) {
            $monthly_count = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->unique('user_id')->count();
        } else {
            $monthly_count = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->unique('user_id')->count();
        }

        return $monthly_count;
    }

    public static function total_monthly_hours()
    {
        $start_date = date('Y-m-01 00:00:00');
        $end_date = date('Y-m-t 23:59:59');
        if(\Auth::user()->can('admin')) {
            $monthly_count = UserSessions::where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->sum('time_spend_minutes');
        } elseif(\Auth::user()->can('gym owner')) {
            $monthly_count = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->sum('time_spend_minutes');
        } else {
            $monthly_count = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->sum('time_spend_minutes');
        }

        if($monthly_count) {
            return round($monthly_count / 60);
        }
        return '0';
    }

    public static function total_monthly_revenue()
    {
        $start_date = date('Y-m-01 00:00:00');
        $end_date = date('Y-m-t 23:59:59');
        if(\Auth::user()->can('admin')) {
            $monthly_count = UserSessions::where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->sum('total_amount');
        } elseif(\Auth::user()->can('gym owner')) {
            $monthly_count = UserSessions::whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->sum('total_amount');
        } else {
            $monthly_count = UserSessions::whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->sum('total_amount');
        }

        if($monthly_count) {
            return round($monthly_count / 60);
        }
        return '0';
    }

    public static function total_monthly_commission()
    {
        $start_date = date('Y-m-01 00:00:00');
        $end_date = date('Y-m-t 23:59:59');
        if(\Auth::user()->can('admin')) {
            $commission = Commission::whereHas('session', function($q) use($start_date, $end_date) {
                $q->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date);
            })->sum('commission_amount');
        } elseif(\Auth::user()->can('gym owner')) {
            $commission = Commission::whereHas('session', function($q) use($start_date, $end_date) {
                $q->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date);
            })->whereHas('gym', function($q){
                $q->where('owner_id', \Auth::user()->id);
            })->sum('commission_amount');
        } else {
            $commission = Commission::whereHas('session', function($q) use($start_date, $end_date) {
                $q->where('status', 'completed')->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date);
            })->whereHas('gym', function($q){
                $q->where('branch_manager_id', \Auth::user()->id);
            })->sum('commission_amount');
        }

        if($commission) {
            return $commission;
        }
        return '0';
    }

    public static function checkReward($request, $user)
    {
        $sess_count = UserSessions::where('status', 'completed')->where('total_amount', '>', 0)->where('user_id', $user->id)->count();
        $milestone = Milestones::where('no_of_sessions', $sess_count)->orderBy('id', 'desc')->first();
        if(!$milestone) {
            return false;
        }

        for($i = 1; $i < 5; $i++) {
            $code = rand(100000,999999) . $user->id;
            $check_voucher = Vouchers::where('name', $code)->first();
            if (!$check_voucher) {
                $i = 10;
            } else {
                $i = 1;
            }
        }

        $request->request->add([
            'name' => $code,
            'slug' => str_slug($code),
            'user_id' => [$user->id],
            'discount' => $milestone->discount,
            'discount_unit' => $milestone->discount_unit,
            'discount' => $milestone->discount,
            'start_datetime' => date('Y-m-d H:i:s'),
            'end_datetime' => date('Y-m-d H:i:s', strtotime('+'.$milestone->expires_in_days.' days')),
            'status' => 1,
            'count' => $milestone->count
        ]);

        $voucher = new Vouchers();
        $voucher = $voucher->store($request, $user);
        if( $voucher instanceof \App\Vouchers ) {
            return $milestone;
        }
        return false;
    }

    public static function getRedeemedTotal($offerId)
    {
        $total = Self::where('offer_id', $offerId)->where('total_amount', '>', 0)->where('status', 'completed')->sum('total_amount');
        $count = Self::where('offer_id', $offerId)->where('total_amount', '>', 0)->where('status', 'completed')->count();
        if($count) {
            return ['count' => $count, 'total' => $total];
        }
        return false;
    }

    public static function transactionHistory($user = [], $session = [])
    {
        if($user) {
            $sessions = UserSessions::with('gym', 'payments')->where('user_id', $user->id)->orderBY('id', 'desc')->get();

            if($session) {
                $sessions = $sessions->except($session->id);
            }

            if (count($sessions) > 0) {
                $listings = [];
                foreach ($sessions as $session) {

                    $listing['session_id'] = $session->id;
                    $listing['gym_name'] = $session->gym->name;
                    $listing['gym_logo'] = $session->gym->logo;
                    $listing['gym_location'] = $session->gym->city->name . ', ' . $session->gym->town;
                    $listing['amount'] = Settings::get_value('currency') . ' ' . $session->total_amount;
                    $listing['session_time'] = Carbon::createFromTimestamp(strtotime($session->end_datetime))->diffForHumans();
                    $start_datetime = \Carbon::parse($session->start_datetime);
                    $end_datetime = \Carbon::parse($session->end_datetime);

                    $diff_in_seconds = $start_datetime->diffInSeconds($end_datetime);
                    $duration_in_seconds = gmdate('H:i:s', $diff_in_seconds);

                    $listing['total_time'] = $duration_in_seconds;

                    if($session->payments) {
                        $listing['payment_status'] = $session->payments->status;
                    } else {
                        if($session->total_amount != 0) {
                            $listing['payment_status'] = 'DUE';
                        } else {
                            $listing['payment_status'] = 'PAID';
                        }

                    }

                    $listings[] = $listing;
                    unset($listing);
                }
                return $listings;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}
