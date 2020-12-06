<?php

namespace App\Http\Controllers\Admin\Patients;

use App\UserSessions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

use App\Patient;
use App\City;
use App\Country;
use App\Hospital;
use App\Speciality;
use App\Certification;

class IndexController extends \App\Http\Controllers\Admin\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $get_name = 'patient';
	
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
		
        $get_countries = Country::all();
		$get_cities = City::all();
		
		foreach($get_countries as $key => $val)
		{
			$data['countries'][$val->id] = $val->name;
		}
		
		foreach($get_cities as $key => $val)
		{
			$data['cities'][$val->id] = $val->name;
		}
		
		//dd($get_countries);
		
        if(\Auth::user()->can('admin')){
            $data['records'] = Patient::orderBy('id', 'DESC')->get();
			//$data['doctors'] = Patient::permission('user')->get();
        } else {
            $data['records'] = Patient::where('created_by', \Auth::user()->id)->get();
        }
		
        return view('admin.'. $this->get_name . '.index', $data);
    }

    public function detail(Request $request, $userId = null)
    {
        $data['menu_active']    = $this->get_title($this->get_name);

        if(\Auth::user()->can('admin')) {
            $data['record'] = Patient::permission('user')->where(['id' => $userId])->first();
        } else {
            $data['record'] = Patient::permission('user')->where(['id' => $userId])->where('created_by', \Auth::user()->id)->first();
        }

        if(!$data['doctor']) {
            abort('404');
        }

        $data['title'] = 'View '. $data['record']->first_name;
//        return $data['user']->sessions->where('start_datetime', '>=', '2019-10-10')->where('start_datetime', '<=', '2019-10-21');
        for ($i = 0; $i < 12; $i++) {
            $data['month_display'][] = date("M-Y", strtotime( date( 'Y-m-01' )." -$i months"));
            $data['months'][] = date("Y-m", strtotime( date( 'Y-m-01' )." -$i months"));
            $start_date = date('Y-m-1 00:00:00', strtotime($data['month_display'][$i]));
            $end_date = date('Y-m-t 23:59:59', strtotime($data['month_display'][$i]));
            $data['monthly_sessions_count'][] = $data['record']->sessions->where('start_datetime', '>=', $start_date)->where('start_datetime', '<=', $end_date)->count();
        }

//        return $data['user']->getMonthlySessionsCount($data['months']);
        $gym_ids = $data['user']->sessions->unique('gym_id')->take(4);
        $total_count = 0;
        $data['top_gyms']['gym_name'] = [];
        $data['top_gyms']['counts'] = [];
        $data['top_gyms']['amount'] = [];
        $data['time_spend_minutes'] = [];
        foreach($gym_ids as $row) {
            $data['top_gyms']['gym_name'][] = $row->gym->name;
            $data['top_gyms']['counts'][] = $data['record']->sessions->where('gym_id', $row->gym_id)->count();
            $data['top_gyms']['amount'][] = $data['record']->sessions->where('gym_id', $row->gym_id)->sum('total_amount');
            $total_count += $data['user']->sessions->where('gym_id', $row->gym_id)->count();
            $data['time_spend_minutes'][] = ['gym_name' => $row->gym->name, 'total_time' => $data['record']->sessions->where('gym_id', $row->gym_id)->sum('time_spend_minutes')];
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
            $data['gym_ids'] = $data['record']->sessions->where('start_datetime', '>=', $start_date_time)->where('end_datetime', '<=', $end_date_time)->unique('gym_id');
//            return $data['user']['sessions']->where('gym_id', 3)->sum('total_amount');
        }

        return view('admin.doctor.detail', $data);
    }

    public function addEdit($record_id = null)
    {
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_name;
		
        $get_countries = Country::all();
		$get_cities = City::all();
		$get_hospitals = Hospital::all();
		$get_specialities = Speciality::all();
		$get_certifications = Certification::all();
		
		foreach($get_countries as $key => $val)
		{
			$data['countries'][$val->id] = $val->name;
		}
		
		foreach($get_cities as $key => $val)
		{
			$data['cities'][$val->id] = $val->name;
		}
		
		foreach($get_hospitals as $key => $val)
		{
			$data['hospitals'][$val->id] = $val->name;
		}
		
		foreach($get_specialities as $key => $val)
		{
			$data['specialities'][$val->id] = $val->name;
		}
		
		foreach($get_certifications as $key => $val)
		{
			$data['certifications'][$val->id] = $val->name;
		}

        if( $record_id ) {
            
			if(\Auth::user()->can('admin'))
			{
                $data['record'] = Patient::where(['id' => $record_id])->first();
            }
			else
			{
                $data['record'] = Patient::where('created_by', \Auth::user()->id)->where(['id' => $record_id])->first();
            }

            if( !$data['record'] )
			{
                abort('404');
            }
			
            $data['title'] = 'Edit ' . $this->get_name;
            
			/*if($data['record']->can('admin')) {
                $data['user_type'] = 'admin';
            } elseif($data['record']->can('gym owner')) {
                $data['user_type'] = 'gym owner';
            } elseif($data['record']->can('branch manager')) {
                $data['user_type'] = 'branch manager';
            } else {
                $data['user_type'] = 'user';
            }*/
        } else {
            $data['title'] = 'Create ' . $this->get_name;
            $data['record'] = new Patient;
        }

        $data['roles'] = Role::whereHas('permissions', function ($q){
            $q->where('name', 'user');
        })->pluck('name', 'id');
        
		$data['genders'] = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];

//        $data['user_types'] = ['user' => 'User', 'admin' => 'Admin', 'branch manager' => 'Branch Manager', 'gym owner' => 'Gym Owner'];

        return view('admin.'. $this->get_name . '.add_edit', $data);
    }

    public function save(Request $request, $record_id = null )
    {
		//return $request->all();
		
        if(\Auth::user()->can('admin')) {
            $record = Patient::where(['id' => $record_id])->first();
        } else {
            $record = Patient::where('created_by', \Auth::user()->id)->where(['id' => $record_id])->first();
        }

        if( !$record_id ) {
            $record = new Patient;
        }

        $request->request->add(['status' => $request->get('status') == 'on' ? true : false]);
		
        //$request->request->add(['image_type' => 'base64']);

        $record = $record->store($request);

        if( $record instanceof \App\Patient )
		{
            return redirect()->route('admin.'. $this->get_name . '.edit', $record->id)->with('success', 'Data has been saved successfully!');
        }
		
        return redirect()->back()->withInput()->withErrors($record->errors());
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
}
