<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Vouchers;
use Illuminate\Http\Request;
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
use Carbon\CarbonPeriod;
use Carbon\Carbonite;

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

    public function finance(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $data['sessions'] = UserSessions::finance($request);
        }

        $data['menu_active']    = 'reports';
        $data['title'] = "Finance Report";

        $data['listings'] = Gym::pluck_data();
        $data['offers'] = Offers::pluck_data();
        $data['users'] = User::pluck_data();
        return view('admin.reports.finance', $data);
    }

    public function vouchersUsage(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $data['sessions'] = UserSessions::vouchersUsage($request);
        }

        $data['menu_active']    = 'reports';
        $data['title'] = "Vouchers Report";

        $data['vouchers'] = Vouchers::pluck('name', 'name');
        return view('admin.reports.vouchers_usage', $data);
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
}
