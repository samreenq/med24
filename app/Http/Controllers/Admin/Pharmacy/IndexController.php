<?php
namespace App\Http\Controllers\Admin\Pharmacy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Pharmacy;
use App\City;
use App\Country;
use App\Insurance;

class IndexController extends \App\Http\Controllers\Admin\Controller{
    public $get_title = 'pharmacy';
	
    public function __construct(){
        $this->middleware('auth');
    }

    function index(){
        $data['menu_active'] = strtolower($this->get_title);
        $data['title'] = $this->get_title;
		
		if(\Auth::user()->can('admin')){
            $data['records'] = Pharmacy::all();
		}else{
            $data['records'] = Pharmacy::where('created_by', \Auth::user()->id)->get();
        }
		
        return view('admin.'.$this->get_title.'.index', $data);
    }

    public function addEdit($userId = null){
        $data['menu_active'] = $this->get_title;
		
		$data['countries'] = Country::get()
                                ->pluck('name', 'id');

		$data['insurance'] = Insurance::where([
                                    'type' => 'pharmacy',
                                    'status' => 1
                                ])
                                ->orderBy('created_at', 'desc')
                                ->get()
                                ->pluck('name', 'id');

        $data['cities'] = [];
		
		if($userId){
            if(\Auth::user()->can('admin')){
                $data['record'] = Pharmacy::where(['id' => $userId])
                                    ->first();
            }else{
                $data['record'] = Pharmacy::where([
                                        'created_by' => \Auth::user()->id,
                                        'id' => $userId
                                    ])
                                    ->first();
            }

            if(!$data['record']){
                abort('404');
            }

            $country = Country::find($data['record']->country_id);

            $data['cities'] = City::where('country_id', $country->code)->get();

            $data['title'] = 'Edit '. $data['record']->name;
        }else{
            $data['title'] = 'Create Pharmacy';

            $data['record'] = new Pharmacy;
        }

        return view('admin.'.$this->get_title.'.add_edit', $data);
    }

    public function save(Request $request, $recordId = null){
        if(\Auth::user()->can('admin')) {
            $record = Pharmacy::where(['id' => $recordId])->first();
        }else{
            $record = Pharmacy::where([
                            'created_by' => \Auth::user()->id, 
                            'id' => $recordId
                        ])
                        ->first();
        }

        if(!$record){
            $record = new Pharmacy;
        }

        $request->request->add([
            'status' => $request->get('status') == 'on' ? 1 : 0,
            'image_type' => 'base64'
        ]);

        $record = $record->store($request);

        if($record instanceof Pharmacy){
            return redirect()->route('admin.user.'.$this->get_title.'.edit', $record->id)->with('success', 'Data has been saved successfully!');
        }

        return redirect()->back()->withInput()->withErrors($record->errors());
    }

    public function destroy($id){
        if(\Auth::user()->can('admin')){
            $user = Pharmacy::where('id', $id)
                        ->first();
        }else{
            $user = Pharmacy::where([
                        'created_by' => \Auth::user()->id,
                        'id' => $id,
                    ])
                    ->first();
        }

        $user->delete();

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
                if($data['name'] != null){
                    $record = Pharmacy::where('name', $data['name'])
                                ->first();

                    if(!$record){
                        $city = City::where('name', 'like', '%'.$data['city'].'%')
                                    ->first();

                        if($city){
                            $country = Country::where('code', $city->country_id)
                                        ->first();
                        }

                        $request->request->add([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'phone' => $data['phone'],
                            'city' => $data['city'],
                            'address' => $data['address']
                        ]);

                        $request->validate([
                            'name' => 'required',
                            'email' => 'required|string|email|unique:pharmacies,email',
                            'phone' => 'required|numeric|unique:pharmacies,phone',
                            'city' => 'required'
                        ]);

                        $record = new Pharmacy;

                        $record->name = $data['name'];
                        $record->email = $data['email'];
                        $record->phone = $data['phone'];
                        $record->city_id = $city->id ?? null;
                        $record->country_id = $country->id ?? null;
                        $record->address = $data['address'];
                        $record->status = 1;
                        
                        if(!$record->save()){
                            return redirect()->route('admin.user.'.$this->get_title.'.index')->with('error', 'Something wen\'t wrong');
                        }
                    }
                }
            }

            return redirect()->route('admin.user.'.$this->get_title.'.index')->with('success', 'Record has been saved successfully!');
        }else{
            return redirect()->route('admin.user.'.$this->get_title.'.index')->with('error', 'You can\'t submit the empty file');
        }
    }
}