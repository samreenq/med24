<?php
namespace App\Http\Controllers\Admin\Doctors;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;
use App\Doctor;
use App\Notifications\ProfileClaimed;

class ClaimProfilesController extends Controller{
	public $get_name = 'claim profile';
	
	function __construct(){
        $this->middleware('auth');
    }
	
	function get_title($title){
		if(substr($title, -1) == 'y'){
			$title = substr($title, 0, -1).'ies';
		}elseif(substr($title, -2) == 'ss'){
			$title = $title.'es';
		}else{
			$title = $title.'s';
		}

		return str_replace(['-', '_'], ' ', ucwords($title));
	}

    function index(){
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
        $profiles = Doctor::orderBy('created_at', 'desc')
                                  ->get();

        $array = [];

        if(count($profiles) > 0){
            foreach($profiles as $profile){
                $isExist = DB::table('password_resets')
                          ->where('email', $profile->email)
                          ->first();

                if($isExist){
                    $array[] = array(
                        'id' => $profile->id,
                        'first_name' => $profile->first_name,
                        'last_name' => $profile->last_name,
                        'email' => $profile->email,
                        'phone' => $profile->phone,
                        'status' => $profile->status,
                        'created_at' => $profile->created_at,
                        'updated_at' => $profile->updated_at,
                    );
                }
            }
        }

        $data['profiles'] = $array;

        return view('admin.doctor.'.str_replace(' ', '', $this->get_name).'.index', $data);
    }

    function claimProfile(Request $request, $id){
    	$doctor = Doctor::find($request->id);

        if($doctor){
            $token = app('auth.password.broker')->createToken($doctor);

           	$reset = DB::table('password_resets')->insert([
                'email' => $doctor->email,
                'token' => $token,
                'created_at' => date('Y-m-d h:i:s'),
            ]);

           	try{
           		$doctor->notify(new ProfileClaimed($doctor, $token));

           		return redirect()->back()->with('success', 'A Confirmation Mail Has Been Sent To '.$doctor->email);
           	}catch(Exception $ex){
           		return redirect()->back()->with('error', 'Somethig wen\'t wrong !!');
           	}
        }
        
        return redirect()->back()->with('error', 'Invalid doctor.');
    }
}