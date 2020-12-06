<?php
namespace App\Http\Controllers\Admin\Patients;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\FamilyMember;

class FamilyMembersController extends Controller{
	function __construct(){
        $this->middleware('auth');
    }
	
	function index(Request $request, $id){
        $data['menu_active'] = 'family_member';
        $data['title'] = 'Family Members';
        $data['familyMembers'] = FamilyMember::where('patient_id', $id)
                                  	   		 ->get();

        return view('admin.patient.familyMembers.index', $data);
    }
}