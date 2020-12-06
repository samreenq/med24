<?php
namespace App\Http\Controllers\Admin\Patients;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\MedicalInfo;

class MedicalInfoController extends Controller{
	function __construct(){
        $this->middleware('auth');
    }
	
	function index(Request $request, $id){
        $data['menu_active'] = 'medical_info';
        $data['title'] = 'Medical Info';
        $data['records'] = MedicalInfo::with('patient')
        								   ->where('patient_id', $id)
        								   ->orderBy('created_at', 'desc')
                              	   		   ->get();

        return view('admin.medical_info.index', $data);
    }
}