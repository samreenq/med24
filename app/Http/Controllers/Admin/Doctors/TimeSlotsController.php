<?php
namespace App\Http\Controllers\Admin\Doctors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use App\DoctorTiming;
use App\Doctor;
use App\Hospital;

class TimeSlotsController extends Controller{
	function __construct(){
        $this->middleware('auth');
    }

    public $currentMenu = 'doctor';
    public $title = 'Time Slots';

	function index(Request $request, $id){
		$data['menu_active'] = $this->currentMenu;
        $data['title'] = $this->title;
        $data['doctorId'] = $id;
        $data['records'] = DoctorTiming::with('hospital')
        					->where('doctor_id', $id)
						  	->orderBy('created_at', 'desc')
						  	->get();

        return view('admin.doctor.'.strtolower(str_replace(' ', '', $this->title)).'.index', $data);
	}

	function add(Request $request, $id){
		$data['menu_active'] = $this->currentMenu;
        $data['title'] = $this->title;
        $data['hospitals'] = Hospital::select('id', 'name')
    							->where('status', 1)
    							->orderBy('created_at', 'desc')
    							->get();

        if($id){
        	if($request->recordId){
        		if(Auth::user()->can('admin')){
	                $data['record'] = DoctorTiming::where('id', $request->recordId)
	                					->first();
	            }else{
	                $data['record'] = DoctorTiming::where([
	                						'created_by' => Auth::user()->id,
	                						'id' => $request->recordId,
	                					])
	                					->first();
	            }

	            if(!$data['record']){
	                abort('404');
	            }

				$data['title'] = 'Edit ' . $this->title;
			}else{
				$data['title'] = 'Create ' . $this->title;

            	$data['record'] = new DoctorTiming;
			}	
        }else{
        	abort('404');
        }

        return view('admin.doctor.'.strtolower(str_replace(' ', '', $this->title)).'.add', $data);
	}

	function save(Request $request, $id){
		$validator = Validator::make($request->all(), [
			'day' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
			'hospital' => 'required',
			'openingTime' => 'required',
			'closingTime' => 'required',
			//'appointmentInterval' => 'required',
			'status' => 'nullable|in:on'
		]);

		if($validator->fails()){
			return redirect()->back()->withInput()->withErrors($validator->errors());
		}

		$doctor = Doctor::find($id);

		if(!$doctor){
			return redirect()->back()->with('error', 'Invalid doctor choosen')->withInput();
		}

		$hospital = Hospital::find($request->hospital);

		if(!$hospital){
			return redirect()->back()->with('error', 'Invalid hospital selected')->withInput();
		}
		
		$timing = DoctorTiming::find($request->id);

		if(!$timing){
			$timing = new DoctorTiming();
		}

		$timing->doctor_id = $id;
		$timing->hospital_id = $request->hospital;
		$timing->from = date('H:i', strtotime($request->openingTime));
		$timing->to = date('H:i', strtotime($request->closingTime));
		$timing->day = $request->day;
		$timing->appointment_interval = 30;
		$timing->status = $request->status ? true : false;

		if(!$timing->save()){
			return redirect()->back()->with('error', 'Something wen\'t wrong')->withInput();
		}

		return redirect()->route('admin.doctor.timeSlots.add', ['' => $id, 'recordId' => $timing->id])->with('success', 'Record has been saved successfully');
	}

	function delete(Request $request, $id){
		$timing = DoctorTiming::find($request->recordId);

		if(!$timing){
			return redirect()->back()->with('error', 'Invalid Id');
		}

		if(!$timing->delete()){
			return redirect()->back()->with('error', 'Something wen\'t wrong');
		}

		return redirect()->back()->with('success', 'Record has been saved successfully');
	}
}