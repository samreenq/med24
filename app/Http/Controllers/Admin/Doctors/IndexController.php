<?php
namespace App\Http\Controllers\Admin\Doctors;
use App\UserSessions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Doctor;
use App\City;
use App\Country;
use App\Hospital;
use App\Speciality;
use App\Certification;
use App\Insurance;
use App\Language;

class IndexController extends \App\Http\Controllers\Admin\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $get_name = 'doctor';
	
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
    public function index(Request $request)
    {
       	$data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
		
        if(\Auth::user()->can('admin')){
            $records = Doctor::orderBy('id', 'DESC');
			//$data['doctors'] = Doctor::permission('user')->get();
        } else {
            $records = Doctor::where('created_by', \Auth::user()->id);
        }

        if(isset($request->isApproved) && ($request->isApproved == 0 || $request->isApproved == 1)){
            $records->where('isApproved', $request->isApproved);
        }

        $data['records'] = $records->get();
		
        return view('admin.'. $this->get_name . '.index', $data);
    }

    public function addEdit($record_id = null)
    {
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_name;
		
        $data['countries'] = Country::get()->pluck('name', 'id');
		$data['hospitals'] = Hospital::get()->pluck('name', 'id');
		$data['specialities'] = Speciality::get()->pluck('name', 'id');
		$data['certifications'] = Certification::get()->pluck('name', 'id');
		$insurances =   Insurance::with([
                                            'insurances_plans' => function ($query){
                                                $query->where('status', 1);
                                            },
                                        ])
                                        ->where([
                                            'type' => 'doctor',
                                            'status' => 1,
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

        $data['doctors_insurances'] = $insurancesPlans;

        $data['languages'] = Language::get()->pluck('name', 'id');

		if( $record_id ) {
            
			if(\Auth::user()->can('admin'))
			{
                $data['record'] = Doctor::where(['id' => $record_id])->first();
            }
			else
			{
                $data['record'] = Doctor::where('created_by', \Auth::user()->id)->where(['id' => $record_id])->first();
            }

            $country = Country::find($data['record']->country_id);

            $data['cities'] = [];

            if($country){
                $data['cities'] = City::where('country_id', $country->code)->get();
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
            $data['record'] = new Doctor;
        }

        $data['roles'] = Role::whereHas('permissions', function ($q){
            $q->where('name', 'user');
        })->pluck('name', 'id');
        
		$data['genders'] = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];

        return view('admin.'. $this->get_name . '.add_edit', $data);
    }

    public function save(Request $request, $record_id = null )
    {
		if(\Auth::user()->can('admin')) {
            $record = Doctor::where(['id' => $record_id])->first();
        } else {
            $record = Doctor::where('created_by', \Auth::user()->id)->where(['id' => $record_id])->first();
        }

        if( !$record_id ) {
            $record = new Doctor;
        }

        $request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);
		
        //$request->request->add(['image_type' => 'base64']);

        $record = $record->store($request);

        if( $record instanceof \App\Doctor )
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

    function isApproved(Request $request, $id){
        $record = Doctor::find($id);

        if(!$record){
            return redirect()->route('admin.'.$this->get_name.'.index')->with('error', 'Invalid doctor id');
        }

        $record->isApproved = $record->isApproved == 1 ? false : true;

        if(!$record->save()){
            return redirect()->route('admin.'.$this->get_name.'.index')->with('error', 'Something wen\'t wrong');
        }

        $approved = $record->first_name.' '.$record->last_name.' has been '.($record->isApproved == 1 ? 'approved' : 'unapproved').' successfully';

        return redirect()->route('admin.'.$this->get_name.'.index', ['isApproved' => $record->isApproved == 0 ? 1 : 0])->with('success', $approved);
    }
}