<?php
namespace App\Http\Controllers\Hospital\Dashboard;
use Illuminate\Http\Request;
use Auth;
use App\Http\Controllers\Controller;
use App\Doctor;
use App\Appointment;

class IndexController extends Controller{
	function index(){
    	$data['title'] = 'Dashboard';
        $data['menu_active'] = 'dashboard';

        $data['totalDoctors'] = Doctor::whereHas('hospitals', function ($query){
                                    $query->where('hospital_id', Auth::guard('hospital')->user()->id);
                                })
        						->count();

		$data['totalAppointments'] = Appointment::with([
											'hospital' => function ($query){
												$query->where('id', Auth::guard('hospital')->user()->id);
											},
										])
										->count();

        return view('hospital.dashboard.index', $data);
    }
}