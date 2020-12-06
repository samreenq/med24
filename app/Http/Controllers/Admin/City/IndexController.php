<?php

namespace App\Http\Controllers\Admin\City;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\City;
use App\Country;
use Illuminate\Support\Facades\Validator;

class IndexController extends \App\Http\Controllers\Admin\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $data['menu_active']    = 'city';
        $data['title'] = "City";
        $data['cities'] = City::paginate(15);
        $data['countries'] = Country::pluck_data();
        return view('admin.city.index', $data);
    }

    public function addEdit($cityId = null)
    {
        $data['menu_active']    = 'city';

        if( $cityId ) {
            $data['city'] = City::where(['id' => $cityId])->first();
            if( !$data['city'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['city']->name;
        } else {
            $data['title'] = 'Create city';
            $data['city'] = new City;
        }
        
        $data['countries'] = Country::pluck_data();

        return view('admin.city.add_edit', $data);
    }

    public function save(Request $request, $cityId = null )
    {
        $city = City::where(['id' => $cityId])->first();

        if( !$city ) {
            $city = new City;
        }

        $request->request->add(['slug' => str_slug($request->get('name'), '-')]);

        $city = $city->store($request);

        if( $city instanceof \App\City ) {
            return redirect()->route('admin.city.edit', $city->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($city->errors());
    }

    public function destroy($id)
    {
        $city = City::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
