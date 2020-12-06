<?php

namespace App\Http\Controllers\Admin\Notifications;

use App\Gym;
use App\Offers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\PushNotification;
use App\User;
use App\DeviceTokens;

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

    public function index()
    {
        $data['menu_active']    = 'notifications';
        $data['title'] = "Notifications";
        $data['notifications'] = PushNotification::with('user')->where('is_admin', '1')->orderBy('id', 'asc')->get();
        return view('admin.notifications.index', $data);
    }

    public function loadFcmView(Request $request)
    {
    //  return $res;
        $data['menu_active']    = 'push_notifications';
        $data['title']    = 'Send New Push Notifications';
        $data['notification'] = new PushNotification;
        $data['trigger_types'] = [
            'home' => 'Home',
            'gym' => 'Gym',
            'offer' => 'Offer'
        ];
        $data['device_types'] = [
            'android' => 'Android',
            'ios' => 'IOS'
        ];
        $data['users'] = User::pluck_data();
        return view('admin.notifications.send', $data);
    }

    public function sendFCMMessage(Request $request)
    {
        $notification = new PushNotification();

        $notification = $notification->sendFcm($request);

        if( $notification instanceof \App\PushNotification ) {
            return redirect()->route('admin.notifications.index')->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($notification->errors());
    }

    public function getTriggerData(Request $request)
    {
        $trigger_type = $request->trigger_type;

        if($trigger_type == 'offer') {
            $data = Offers::pluck_data();
            return response()->json(['status' => 1, 'data' => $data]);
        } elseif ($trigger_type == 'gym') {
            $data = Gym::pluck_data();
            return response()->json(['status' => 1, 'data' => $data]);
        }

        return response()->json(['status' => 0, 'data' => []]);
    }
}
