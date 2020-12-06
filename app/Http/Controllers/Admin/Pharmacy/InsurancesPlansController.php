<?php
namespace App\Http\Controllers\Admin\Pharmacy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use App\Insurance;
use App\InsurancesPlans;

class InsurancesPlansController extends Controller{
	function __construct(){
        $this->middleware('auth');
    }

    public $get_name = 'insurance_plan';
	
	public function get_title($title){
		if(substr($title, -1) == 'y'){
			$title = substr($title, 0, -1) . 'ies';
		}elseif(substr($title, -2) == 'ss'){
			$title = $title . 'es';
		}else{
			$title = $title . 's';
		}

		return str_replace(['-', '_'], ' ', $title);
	}

    public function index($id){
        $data['menu_active'] = explode('_', $this->get_name)[0];
        $data['title'] = $this->get_title($this->get_name);

        $data['records'] = Insurance::with([
        								'insurances_plans' => function ($query){
        									$query->orderBy('created_at', 'desc');
        								},
        							])
        							->where('type', 'pharmacy')
							  		->where('id', $id)
								  	->first();

        return view('admin.pharmacy.'.explode('_', $this->get_name)[0].'s.'.explode('_', $this->get_name)[1].'s.index', $data);
    }

    public function addEdit($insuranceId, $id = null){
        $data['menu_active'] = explode('_', $this->get_name)[0];
        $data['title'] = $this->get_title($this->get_name);
		
        if($id){
            $data['record'] = InsurancesPlans::where([
        							'id' => $id
        						])
            					->first();

            if(!$data['record']){
                abort('404');
            }

            $data['title'] = 'Edit '.$this->get_name;
        }else{
            $data['title'] = 'Create '.$this->get_name;

            $data['record'] = new InsurancesPlans;
        }

        return view('admin.pharmacy.'.explode('_', $this->get_name)[0].'s.'.explode('_', $this->get_name)[1].'s.add_edit', $data);
    }

    public function save(Request $request, $insuranceId, $id = null){
        $insurance = InsurancesPlans::where([
						'id' => $id,
					])
					->first();

        if(!$insurance){
            $insurance = new InsurancesPlans;
        }

        $request->request->add([
        	'insuranceId' => $insuranceId,
        	'type' => 'pharmacy',
        ]);

        $insurance = $insurance->store($request);

        if($insurance instanceof InsurancesPlans){
            return redirect()->route('admin.pharmacies.insurances.plans.index', $insuranceId)->with('success', 'Data has been saved successfully!');
        }

        return redirect()->back()->withInput()->withErrors($insurance->errors());
    }

    public function destroy($insuranceId, $id){
        $insurance = InsurancesPlans::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}