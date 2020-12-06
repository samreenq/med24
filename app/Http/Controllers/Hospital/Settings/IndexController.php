<?php
namespace App\Http\Controllers\Hospital\Settings;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Hospital;
use App\Country;
use App\SpecialityHospital;
use App\CertificationHospital;
use App\AwardHospital;
use App\Insurance;
use App\City;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Auth;

class IndexController extends Controller{
    public $activeMenu = 'profileSettings';
    public $pageTitle = 'Profile Settings';

    function index(Request $request){
        $data['menu_active'] = $this->activeMenu;
        $data['title'] = $this->pageTitle;
        
        $get_countries = Country::all();
        $get_specialities_hospitals = SpecialityHospital::all();
        $get_certifications_hospitals = CertificationHospital::all();
        $get_awards_hospitals = AwardHospital::all();
        $insurances = Insurance::with([
                            'insurances_plans' => function ($query){
                                $query->where('status', 1);
                            },
                        ])
                        ->where([
                            'type' => 'hospital',
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

        $data['hospital_insurances'] = $insurancesPlans;
        
        foreach($get_countries as $key => $val){
            $data['countries'][$val->id] = $val->name;
        }
        
        foreach($get_specialities_hospitals as $key => $val){
            $data['specialities_hospitals'][$val->id] = $val->name;
        }
        
        foreach($get_certifications_hospitals as $key => $val){
            $data['certifications_hospitals'][$val->id] = $val->name;
        }
        
        foreach($get_awards_hospitals as $key => $val){
            $data['awards_hospitals'][$val->id] = $val->name;
        }
        
        $data['record'] = Hospital::with([
            'city', 
            'country', 
            'hospital_insurances'
        ])
        ->where('id', Auth::guard('hospital')->user()->id)
        ->first();
        
        $country = Country::find($data['record']->country_id);
        
        $data['cities'] = [];

        if($country){
            $data['cities'] = City::where('country_id', $country->code)
                ->get();
        }

        if(!$data['record']){
            abort('404');
        }
        
        $data['title'] = 'Edit ' .$this->pageTitle;

        $data['genders'] = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];

        return view('hospital.settings.profile.index', $data);
    }

    function save(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'nullable',
            'confirmPassword' => 'nullable|required_with:password|same:password'
        ]);

        if($validator->fails()){
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $record = Hospital::where('id', Auth::guard('hospital')->user()->id)
            ->first();

        $request->request->add(['image_type' => 'base64']);

        $record = $record->store($request);

        if($record instanceof Hospital){
            return redirect()->route('hospital.settings.profile.index')->with('success', 'Record has been saved successfully!');
        }

        return redirect()->back()->withInput()->withErrors($record->errors());
    }
}