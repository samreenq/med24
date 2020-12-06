<?php
namespace App\Http\Controllers\Admin\Patients;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use DB;

class FavouriteController extends Controller{
	function __construct(){
        $this->middleware('auth');
    }
	
	function index(Request $request, $id){
        $data['menu_active'] = 'patient';
        $data['title'] = 'Favourite Hospitals';
        $data['records'] = DB::table('patient_favourites')
        					 ->select('patient_favourites.id', 'patients.first_name', 'patients.last_name', 'hospitals.name', 'patient_favourites.created_at', 'patient_favourites.updated_at')
    						 ->join('patients', 'patients.id', '=', 'patient_favourites.patient_id')
    						 ->join('hospitals', 'hospitals.id', '=', 'patient_favourites.hospital_id')
    						 ->where('patient_id', $id)
    						 ->orderBy('patient_favourites.created_at', 'desc')
    						 ->get();

        return view('admin.patient.favourites.index', $data);
    }

    function favouriteDoctors(Request $request, $id){
        $data['menu_active'] = 'patient';
        $data['title'] = 'Favourite Doctors';
        $data['records'] = DB::table('patient_favourites')
        					 ->select('patient_favourites.id', 'patients.first_name', 'patients.last_name', 'doctors.first_name as d_firstName', 'doctors.last_name as d_lastName', 'patient_favourites.created_at', 'patient_favourites.updated_at')
    						 ->join('patients', 'patients.id', '=', 'patient_favourites.patient_id')
    						 ->join('doctors', 'doctors.id', '=', 'patient_favourites.doctor_id')
    						 ->where('patient_id', $id)
    						 ->orderBy('patient_favourites.created_at', 'desc')
    						 ->get();

        return view('admin.patient.favourites.index', $data);
    }
}