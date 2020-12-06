<?php

namespace App\Http\Controllers\Admin\Country;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Country;
use Illuminate\Support\Facades\Validator;

class IndexController extends \App\Http\Controllers\Admin\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public $get_name = 'country';
	
	public function get_title($title)
	{
		if (substr($title, -1) == 'y')
		{
			$title = substr($title, 0, -1) . 'ies';
		}
		elseif (substr($title, -2) == 'ss')
		{
			$title = $title . 'es';
		}
		else
		{
			$title = $title . 's';
		}

		return str_replace(['-','_'], ' ', $title);
	}
	
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
		
        $data['countries'] = Country::get();
        return view('admin.'. $this->get_name . '.index', $data);
    }

    public function addEdit($record_id = null)
    {
        $data['menu_active'] = $this->get_name;
        $data['title'] = $this->get_title($this->get_name);
		
        if( $record_id ) {
            $data['country'] = Country::with('cities')->where(['id' => $record_id])->first();
            if( !$data['country'] ) {
                abort('404');
            }
            $data['title'] = 'Edit ' . $this->get_name;
        } else {
            $data['title'] = 'Create ' . $this->get_name;
            $data['country'] = new Country;
        }

        return view('admin.'. $this->get_name . '.add_edit', $data);
    }

    public function save(Request $request, $countryId = null )
    {
        $country = Country::where(['id' => $countryId])->first();

        if( !$country ) {
            $country = new Country;
        }

        $request->request->add(['slug' => str_slug($request->get('name'), '-')]);
        $country = $country->store($request);

        if( $country instanceof \App\Country ) {
            return redirect()->route('admin.country.edit', $country->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($country->errors());
    }

    public function destroy($id)
    {
        $country = Country::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
