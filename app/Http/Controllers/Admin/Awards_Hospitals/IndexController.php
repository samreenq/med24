<?php

namespace App\Http\Controllers\Admin\Awards_Hospitals;

use App\UserSessions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\AwardHospital;
use Illuminate\Support\Facades\Validator;
use Helpers;

class IndexController extends \App\Http\Controllers\Admin\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $get_name = 'award_hospital';
	
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
            $data['records'] = AwardHospital::orderBy('id', 'DESC')->get();
			//$data['doctors'] = AwardHospital::permission('user')->get();
        } else {
            $data['records'] = AwardHospital::where('created_by', \Auth::user()->id)->orderBy('id', 'DESC')->get();
        }
        return view('admin.'. $this->get_name . '.index', $data);
    }

    public function addEdit($record_id = null)
    {
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);

        if( $record_id ) {
            
			if(\Auth::user()->can('admin')) {
                $data['user'] = AwardHospital::where(['id' => $record_id])->first();
            } else {
                $data['user'] = AwardHospital::where('created_by', \Auth::user()->id)->where(['id' => $record_id])->first();
            }

            if( !$data['user'] ) {
                abort('404');
            }
            
			$data['title'] = 'Edit ' . $this->get_name;
        } else {
            $data['title'] = 'Create ' . $this->get_name;
            $data['user'] = new AwardHospital;
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
//         return $request->all();
        if(\Auth::user()->can('admin')) {
            $record = AwardHospital::where(['id' => $record_id])->first();
        } else {
            $record = AwardHospital::where('created_by', \Auth::user()->id)->where(['id' => $record_id])->first();
        }

        if( !$record ) {
            $record = new AwardHospital;
        }

        $request->request->add(['name' => $request->get('name')]);
		$request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);        

        $record = $record->store($request);

        if( $record instanceof \App\AwardHospital ) {
            return redirect()->route('admin.'. $this->get_name . '.edit', $record->id)->with('success', 'Record has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($record->errors());
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('admin')) {
            $user = AwardHospital::where('id', $id)->first();
        } else {
            $user = AwardHospital::where('created_by', \Auth::user()->id)->where('id', $id)->first();
        }
        $user->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
