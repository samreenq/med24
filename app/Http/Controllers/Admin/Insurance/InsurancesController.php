<?php
namespace App\Http\Controllers\Admin\Insurance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
use App\Insurance;

class InsurancesController extends Controller{
	function __construct(){
        $this->middleware('auth');
    }

    public $get_name = 'insurance';
	
	public function get_title($title){
		if(substr($title, -1) == 'y'){
			$title = substr($title, 0, -1) . 'ies';
		}elseif(substr($title, -2) == 'ss'){
			$title = $title . 'es';
		}else{
			$title = $title . 's';
		}

		return str_replace(['-','_'], ' ', $title);
	}

    public function index(){
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
        $data['records'] = Insurance::orderBy('created_at', 'desc')
				                ->get();

        return view('admin.'.$this->get_title($this->get_name).'.index', $data);
    }

    public function addEdit($id = null){
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
		
        if($id){
            $data['record'] = Insurance::where([
        							'id' => $id
					            ])
            					->first();

            if(!$data['record']){
                abort('404');
            }

            $data['title'] = 'Edit '.$this->get_name;
        }else{
            $data['title'] = 'Create '.$this->get_name;

            $data['record'] = new Insurance;
        }

        return view('admin.'.$this->get_title($this->get_name).'.add_edit', $data);
    }

    public function save(Request $request, $id = null){
        $insurance = Insurance::where([
						'id' => $id
					])
					->first();

        if(!$insurance){
            $insurance = new Insurance;
        }

        $insurance = $insurance->store($request);

        if($insurance instanceof Insurance){
            return redirect()->route('admin.insurances.edit', $insurance->id)->with('success', 'Data has been saved successfully!');
        }

        return redirect()->back()->withInput()->withErrors($insurance->errors());
    }

    public function destroy($id){
        $insurance = Insurance::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }

    function importExcel(Request $request){
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx',
        ]);

        $path = $request->file('file')->getRealPath();
        $data = Excel::load($path)->get(); 

        if(isset($data) && $data->count() > 0){
            foreach($data->toArray() as $data){
                if($data['medical_center'] != null){
                    $record = Insurance::where([
                                        'name' => $data['medical_center'],
                                    ])  
                                    ->first();

                    if(!$record){
                        $record = new Insurance;

                        $record->created_by = Auth::user()->id;
                        $record->updated_by = Auth::user()->id;
                        $record->name = $data['medical_center'];
                        $record->status = 1;

                        if(!$record->save()){
                            return redirect()->route('admin.insurances.index')->with('error', 'Something wen\'t wrong');
                        }
                    }
                }
            }

            return redirect()->route('admin.insurances.index')->with('success', 'Record has been saved successfully!');
        }else{
            return redirect()->route('admin.insurances.index')->with('error', 'You can\'t submit the empty file');
        }
    }
}