<?php

namespace App\Http\Controllers\Admin\User\Customer;

use App\UserSessions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use Illuminate\Support\Facades\Validator;
use Helpers;

class IndexController extends \App\Http\Controllers\Admin\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $get_name = 'customer';
	
	public function get_title($title)
	{
		if (substr($title, -1) == 'y')
		{
			$title = substr($title, 0, -1) . 'ies';
		}
		elseif (substr($title, -2) == 'ss')
		{
			$title = $title . 'es';
		}
		else
		{
			$title = $title . 's';
		}

		return str_replace(['-','_'], ' ', $title);
	}
	
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
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
		
        if(\Auth::user()->can('admin')) {
            $data['records'] = User::permission('user')->where('status', 1)->get();
        } else {
            $data['records'] = User::where('created_by', \Auth::user()->id)->where('status', 1)->get();
        }
        return view('admin.user.'. $this->get_name . '.index', $data);
    }

    public function detail(Request $request, $userId = null)
    {
        $data['menu_active']    = 'customer';

        if(\Auth::user()->can('admin')) {
            $data['user'] = User::permission('user')->where(['id' => $userId])->first();
        } else {
            $data['user'] = User::permission('user')->where(['id' => $userId])->where('created_by', \Auth::user()->id)->first();
        }

        if(!$data['user']) {
            abort('404');
        }

        $data['title'] = 'View '. $data['user']->first_name;
		// return $data['user']->sessions->where('start_datetime', '>=', '2019-10-10')->where('start_datetime', '<=', '2019-10-21');
        for ($i = 0; $i < 12; $i++) {
            $data['month_display'][] = date("M-Y", strtotime( date( 'Y-m-01' )." -$i months"));
            $data['months'][] = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));
            $start_date = date('Y-m-1 00:00:00', strtotime($data['month_display'][$i]));
            $end_date = date('Y-m-t 23:59:59', strtotime($data['month_display'][$i]));
            $data['monthly_sessions_count'][] = $data['user']->sessions->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->count();
        }

		// return $data['user']->getMonthlySessionsCount($data['months']);
        $gym_ids = $data['user']->sessions->unique('gym_id')->take(4);
        $total_count = 0;
        $data['top_gyms']['gym_name'] = [];
        $data['top_gyms']['counts'] = [];
        $data['top_gyms']['amount'] = [];
        $data['time_spend_minutes'] = [];
        foreach($gym_ids as $row) {
            $data['top_gyms']['gym_name'][] = $row->gym->name;
            $data['top_gyms']['counts'][] = $data['user']->sessions->where('gym_id', $row->gym_id)->count();
            $data['top_gyms']['amount'][] = $data['user']->sessions->where('gym_id', $row->gym_id)->sum('total_amount');
            $total_count += $data['user']->sessions->where('gym_id', $row->gym_id)->count();
            $data['time_spend_minutes'][] = ['gym_name' => $row->gym->name, 'total_time' => $data['user']->sessions->where('gym_id', $row->gym_id)->sum('time_spend_minutes')];
        }
        $data['top_gyms']['colours'] = ['success', 'danger', 'brand', 'warning'];
        $data['top_gyms']['total_count'] = $total_count;

        $data['sessions'] = [];
        if($request->has('month')) {
            $data['month'] = $request->get('month');
            $start_date_time = date('Y-m-1 00:00:00', strtotime($data['month']));
            $end_date_time = date('Y-m-t 23:59:59', strtotime($data['month']));
            $data['sessions'] = $data['user']->sessions->where('start_datetime', '>=', $start_date_time)->where('end_datetime', '<=', $end_date_time);
        }

        $days = ['SUNDAY', 'MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY'];
        $data['session_count_by_day'] = [];
        foreach($data['user']->sessions as $session){
            foreach ($days as $index => $day) {
                if(!isset($data['session_count_by_day'][$day])) {
                    $data['session_count_by_day'][$day] = 0;
                }
//                dd(Carbon::parse($session->start_datetime)->dayOfWeek);
                if (Carbon::parse($session->start_datetime)->dayOfWeek == $index){
                    $data['session_count_by_day'][$day] += 1;
                }
            }
        }

        $data['gym_ids'] = [];
        if($request->has('month_2')) {
            $data['month_2'] = $request->get('month_2');
            $start_date_time = date('Y-m-1 00:00:00', strtotime($data['month_2']));
            $end_date_time = date('Y-m-t 23:59:59', strtotime($data['month_2']));
            $data['gym_ids'] = $data['user']->sessions->where('start_datetime', '>=', $start_date_time)->where('end_datetime', '<=', $end_date_time)->unique('gym_id');
//            return $data['user']['sessions']->where('gym_id', 3)->sum('total_amount');
        }

        return view('admin.user.customer.detail', $data);
    }

    public function addEdit($userId = null)
    {
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);

        if( $userId ) {
            
			if(\Auth::user()->can('admin')) {
                $data['user'] = User::where(['id' => $userId])->first();
            } else {
                $data['user'] = User::where('created_by', \Auth::user()->id)->where(['id' => $userId])->first();
            }

            if( !$data['user'] ) {
                abort('404');
            }
            
			$data['title'] = 'Edit ' . $this->get_name;
            
			if($data['user']->can('admin')) {
                $data['user_type'] = 'admin';
            } elseif($data['user']->can('gym owner')) {
                $data['user_type'] = 'gym owner';
            } elseif($data['user']->can('branch manager')) {
                $data['user_type'] = 'branch manager';
            } else {
                $data['user_type'] = 'user';
            }
        } else {
            $data['title'] = 'Create ' . $this->get_name;
            $data['user'] = new User;
        }

        $data['roles'] = Role::whereHas('permissions', function ($q){
            $q->where('name', 'user');
        })->pluck('name', 'id');

        $data['genders'] = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];

//		$data['user_types'] = ['user' => 'User', 'admin' => 'Admin', 'branch manager' => 'Branch Manager', 'gym owner' => 'Gym Owner'];

        return view('admin.user.'. $this->get_name . '.add_edit', $data);
    }

    public function save(Request $request, $userId = null )
    {
//         return $request->all();
        if(\Auth::user()->can('admin')) {
            $user = User::where(['id' => $userId])->first();
        } else {
            $user = User::where('created_by', \Auth::user()->id)->where(['id' => $userId])->first();
        }

        if( !$user ) {
            $user = new User;
        }

        $request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);
        $request->request->add(['image_type' => 'base64']);

        $user = $user->store($request);

        if( $user instanceof \App\User ) {
            return redirect()->route('admin.user.customer.edit', $user->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($user->errors());
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('admin')) {
            $user = User::where('id', $id)->first();
        } else {
            $user = User::where('created_by', \Auth::user()->id)->where('id', $id)->first();
        }
        $user->deleteUser();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        if($request->status == 0 || $request->status) {
            if (\Auth::user()->can('admin')) {
                $user = User::where('id', $id)->first();
            } else {
                $user = User::where('created_by', \Auth::user()->id)->where('id', $id)->first();
            }
            $user->updateStatus($request->status);
            return redirect()->back()->with('success', 'Status has been updated successfully!');
        }
        return redirect()->back()->with('error', 'Something! went wrong');
    }
    public function block()
    {
        $data['menu_active']    = strtolower('block');
        $data['title'] = 'Blocked Customers';
		
        if(\Auth::user()->can('admin')) {
            $data['records'] = User::permission('user')->where('status', 0)->get();
        } else {
            $data['records'] = User::where('created_by', \Auth::user()->id)->where('status', 0)->get();
        }
        return view('admin.user.customer.index', $data);
    }
}
