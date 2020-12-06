<?php
namespace App\Http\Controllers\Hospital\Doctors;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Auth;
use App\Http\Controllers\Controller;
use App\UserSessions;
use App\Doctor;
use App\City;
use App\Country;
use App\Hospital;
use App\Speciality;
use App\Certification;
use App\Insurance;
use App\Language;

class IndexController extends Controller{
	public $get_name = 'doctor';
	
	function pageTitle($title){
		if(substr($title, -1) == 'y'){
			$title = substr($title, 0, -1) . 'ies';
		}elseif(substr($title, -2) == 'ss'){
			$title = $title . 'es';
		}else{
			$title = $title . 's';
		}

		return str_replace(['-', '_'], ' ', $title);
	}

	function index(Request $request){
       	$data['menu_active'] = $this->get_name;
        $data['title'] = $this->pageTitle($this->get_name);
		
        $records = Doctor::whereHas('Hospitals', function ($query){
                        $query->where('hospital_id', Auth::guard('hospital')->user()->id);
                    });

                    if(isset($request->isApproved) && ($request->isApproved == 0 || $request->isApproved == 1)){
                        $records->where('isApproved', $request->isApproved);
                    }

		$data['records'] = $records->get();
		
        return view('hospital.'.$this->get_name.'.index', $data);
    }

    function addEdit($record_id = null){
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->pageTitle($this->get_name);
		
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

		if($record_id){
            $data['record'] = Doctor::whereHas('hospitals', function ($query){
                                    $query->where('hospital_id', Auth::guard('hospital')->user()->id);
                                })
            					->where('id', $record_id)
        						->first();

            $country = Country::find($data['record']->country_id);

            $data['cities'] = [];

            if($country){
                $data['cities'] = City::where('country_id', $country->code)->get();
            }

            if(!$data['record']){
                abort('404');
            }
			
            $data['title'] = 'Edit '.$this->get_name;
        }else{
            $data['title'] = 'Create '.$this->get_name;

            $data['record'] = new Doctor;
        }
        
		$data['genders'] = [
			'male' => 'Male', 
			'female' => 'Female', 
			'other' => 'Other'
		];

        return view('hospital.'.$this->get_name.'.add_edit', $data);
    }

    function save(Request $request, $record_id = null){
		$record = Doctor::whereHas('hospitals', function ($query){
                        $query->where('hospital_id', Auth::guard('hospital')->user()->id);
                    })
					->where('id', $record_id)
					->first();

        if(!$record_id){
            $record = new Doctor;
        }

        $request->request->add([
            'hospitals' => [Auth::guard('hospital')->user()->id],
            'doctorId' => $record_id,
        	'status' => $request->get('status') == 'on' ? true : false
        ]); 

        $record = $record->store($request);

        if($record instanceof Doctor){
            return redirect()->route('hospital.'.$this->pageTitle($this->get_name).'.edit', $record->id)->with('success', 'Data has been saved successfully!');
        }
		
        return redirect()->back()->withInput()->withErrors($record->errors());
    }

    function destroy($id){
        $user = Doctor::whereHas('hospitals', function ($query){
                    $query->where('hospital_id', Auth::guard('hospital')->user()->id);
                })
				->where('id', $id)
				->first();

		if(!$user){
			return redirect()->back()->with('error', 'Invalid id!');
		}

        $user->delete();

        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }

    function search(Request $request){
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->pageTitle($this->get_name);
        
        if(isset($request->searchKeyword)){
            $records = Doctor::where('email', 'like', '%'.$request->searchKeyword.'%')
                        ->where([
                            'isApproved' => 1,
                            'status' => 1
                        ]);

            $data['records'] = $records->get();

            return view('hospital.'.$this->get_name.'.searchListing', $data);
        }
        
        return view('hospital.'.$this->get_name.'.search', $data);
    }

    function add(Request $request, $id){
        $record = Doctor::find($id);

        if(!$record){
            return redirect()->route('hospital.'.$this->pageTitle($this->get_name).'.search')->with('error', 'Invalid doctor id'); 
        }

        DB::table('doctor_hospitals')
            ->insert([
                'hospital_id' => Auth::guard('hospital')->user()->id,
                'doctor_id' => $id,
                'hospital_type' => 'primary',
            ]);

        return redirect()->route('hospital.'.$this->pageTitle($this->get_name).'.search')->with('success', $record->first_name.' '.$record->last_name.' has been added in your hospital successfully');
    }
}