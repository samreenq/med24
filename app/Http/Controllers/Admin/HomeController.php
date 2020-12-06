<?php

namespace App\Http\Controllers\Admin;

use App\Gym;
use App\Offers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use App\UserSessions;

use App\Doctor;
use App\Speciality;
use App\Certification;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['total_active_doctors'] = Doctor::where('status', 1)->get()->count();
        $data['total_active_specialities'] = Speciality::where('status', 1)->get()->count();
        $data['total_active_certifications'] = Certification::where('status', 1)->get()->count();
        
		$data['total_active_customers'] = User::active_customers(Auth::user()->id);
        $data['total_active_gyms'] = Gym::total_gyms_registered();
        $data['total_monthly_sessions'] = UserSessions::total_monthly_sessions();
        $data['total_unpaid_sessions'] = UserSessions::total_unpaid_sessions();
        $data['total_todays_sessions'] = UserSessions::total_todays_sessions();
        $data['total_active_sessions'] = UserSessions::total_active_sessions();
        $data['total_active_offers'] = Offers::total_active_offers();
        $data['total_customers_trained'] = count(UserSessions::total_customers_trained());
        $data['total_monthly_hours'] = UserSessions::total_monthly_hours();
        $data['total_monthly_revenue'] = UserSessions::total_monthly_revenue();
//        $data['total_monthly_commission'] = UserSessions::total_monthly_commission();
        $data['active_sessions'] = UserSessions::active_sessions();
        $data['unpaid_sessions'] = UserSessions::unpaid_sessions();
        $data['recent_activities'] = Activity::latest()->take(10)->get();

        for ($i = 0; $i < 12; $i++) {
            $data['month_display'][] = date("M-Y", strtotime( date( 'Y-m-01' )." -$i months"));
            $data['months'][] = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));
            $start_date = date('Y-m-1 00:00:00', strtotime($data['month_display'][$i]));
            $end_date = date('Y-m-t 23:59:59', strtotime($data['month_display'][$i]));
            $data['user_count'][] = User::permission('user')->where('created_at', '>=', $start_date)->where('created_at', '<=', $end_date)->count();
            $data['revenue'][] = UserSessions::where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->sum('total_amount');
        }

        $data['todays_sessions'] = UserSessions::session_count();

        $data['title'] = 'Dashboard';
        $data['menu_active'] = 'dashboard';

        return view('admin.home', $data);
    }
}
