<?php

namespace App\Http\Controllers\Admin\Hospital;

use App\UserSessions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Hospital;
use Illuminate\Support\Facades\Validator;
use App\City;
use App\Country;
use App\SpecialityHospital;
use App\CertificationHospital;
use App\AwardHospital;
use App\Insurance;

class IndexController extends \App\Http\Controllers\Admin\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $get_name = 'hospital';
	
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
		
        if(\Auth::user()->can('admin')) {
            $data['records'] = Hospital::with(['city', 'country'])->get();
			//$data['hospitals'] = Hospital::permission('user')->get();
        } else {
            $data['records'] = Hospital::where('created_by', \Auth::user()->id)->get();
        }
		
		//dd($data['records'][0]->city->name);
		
        return view('admin.'. $this->get_name . '.index', $data);
    }

    public function detail(Request $request, $userId = null)
    {
        $data['menu_active']    = 'Hospital';

        if(\Auth::user()->can('admin')) {
            $data['record'] = Hospital::permission('user')->where(['id' => $userId])->first();
        } else {
            $data['record'] = Hospital::permission('user')->where(['id' => $userId])->where('created_by', \Auth::user()->id)->first();
        }

        if(!$data['hospital']) {
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

        return view('admin.'. $this->get_name . '.detail', $data);
    }

    public function addEdit($userId = null)
    {
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
		
        $get_countries = Country::all();
		$get_specialities_hospitals = SpecialityHospital::all();
		$get_certifications_hospitals = CertificationHospital::all();
		$get_awards_hospitals = AwardHospital::all();
        $insurances =   Insurance::with([
                            'insurances_plans' => function ($query){
                                $query->where('status', 1);
                            },
                        ])
                        ->where([
                            'status' => 1
                        ])
                        ->orderBy('created_at', 'desc')
                        ->get();

        $insurancesPlans = [];

        if(count($insurances) > 0){
            foreach($insurances as $insurance){
                if(count($insurance->insurances_plans) > 0){
                    foreach($insurance->insurances_plans as $insurancesPlan){
                        $insurancesPlans[$insurancesPlan->id] = $insurancesPlan->name;
                    }
                }
            }
        }

        $data['hospital_insurances'] = $insurancesPlans;
		
		foreach($get_countries as $key => $val)
		{
			$data['countries'][$val->id] = $val->name;
		}
		
		foreach($get_specialities_hospitals as $key => $val)
		{
			$data['specialities_hospitals'][$val->id] = $val->name;
		}
		
		foreach($get_certifications_hospitals as $key => $val)
		{
			$data['certifications_hospitals'][$val->id] = $val->name;
		}
		
		foreach($get_awards_hospitals as $key => $val)
		{
			$data['awards_hospitals'][$val->id] = $val->name;
		}
		
		if( $userId ) {
            if(\Auth::user()->can('admin')) {
                $data['record'] = Hospital::with(['city', 'country', 'hospital_insurances'])->where(['id' => $userId])->first();
            } else {
                $data['record'] = Hospital::with(['city', 'country', 'hospital_insurances'])->where('created_by', \Auth::user()->id)->where(['id' => $userId])->first();
            }

            $country = Country::find($data['record']->country_id);
            
            $data['cities'] = [];

            if($country){
                $data['cities'] = City::where('country_id', $country->code)->get();
            }

            if( !$data['record'] ) {
                abort('404');
            }
            
			 $data['title'] = 'Edit ' . $this->get_name;
			
        }
		else
		{
            $data['title'] = 'Create ' . $this->get_name;
            $data['record'] = new Hospital;
        }

        $data['roles'] = Role::whereHas('permissions', function ($q){
            $q->where('name', 'user');
        })->pluck('name', 'id');
        
		$data['genders'] = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];

//        $data['user_types'] = ['user' => 'User', 'admin' => 'Admin', 'branch manager' => 'Branch Manager', 'gym owner' => 'Gym Owner'];

        return view('admin.'. $this->get_name . '.add_edit', $data);
    }

    public function save(Request $request, $recordId = null )
    {
//         return $request->all();
        if(\Auth::user()->can('admin')) {
            $record = Hospital::where(['id' => $recordId])->first();
        } else {
            $record = Hospital::where('created_by', \Auth::user()->id)->where(['id' => $recordId])->first();
        }

        if( !$record ) {
            $record = new Hospital;
        }

        $request->request->add([
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'isFeatured' => $request->get('isFeatured') == 'on' ? 1 : 0,
            'isRegistered' => $request->isRegistered == 'on' ? 'yes' : 'no',
            'image_type' => 'base64'
        ]);

        $record = $record->store($request);

        if( $record instanceof \App\Hospital ) {
            return redirect()->route('admin.'. $this->get_name . '.edit', $record->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($record->errors());
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('admin')) {
            $user = Hospital::where('id', $id)->first();
        } else {
            $user = Hospital::where('created_by', \Auth::user()->id)->where('id', $id)->first();
        }
        $user->delete();
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
