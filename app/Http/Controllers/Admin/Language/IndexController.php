<?php
namespace App\Http\Controllers\Admin\Language;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Language;
use Illuminate\Support\Facades\Validator;

class IndexController extends Controller{
	public $get_name = 'language';

	public function __construct(){
        $this->middleware('auth');
    }
	
	public function get_title($title){
		if(substr($title, -1) == 'y'){
			$title = substr($title, 0, -1).'ies';
		}elseif(substr($title, -2) == 'ss'){
			$title = $title.'es';
		}else{
			$title = $title.'s';
		}

		return str_replace(['-', '_'], ' ', $title);
	}

    public function index(){
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
        $data['languages'] = Language::get();

        return view('admin.'.$this->get_name.'.index', $data);
    }

    public function addEdit($record_id = null){
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
		
        if($record_id){
            $data['language'] = Language::where(['id' => $record_id])->first();

            if(!$data['language']){
                abort('404');
            }

            $data['title'] = 'Edit ' .$this->get_name;
        }else{
            $data['title'] = 'Create '.$this->get_name;
            $data['language'] = new Language;
        }

        return view('admin.'.$this->get_name.'.add_edit', $data);
    }

    public function save(Request $request, $id = null){
        $language = Language::where(['id' => $id])->first();

        if(!$language){
            $language = new Language;
        }

        $request->request->add([
            'slug' => str_slug($request->get('name'), '-'),
            'status' => $request->status ? true : false,
        ]);

        $language = $language->store($request);

        if($language instanceof Language){
            return redirect()->route('admin.'.$this->get_name.'.edit', $language->id)->with('success', 'Data has been saved successfully!');
        }

        return redirect()->back()->withInput()->withErrors($language->errors());
    }

    public function destroy($id){
        $language = Language::where('id', $id)->delete();

        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}