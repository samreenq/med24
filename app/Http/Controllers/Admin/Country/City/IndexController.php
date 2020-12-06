<?php

namespace App\Http\Controllers\Admin\Country\City;

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
    public function index($countryId)
    {
        $data['country_id'] = $countryId;
        $data['country'] = Country::find($countryId);
        if(!$data['country']) {
            abort(404);
        }
        $data['menu_active']    = 'country';
        $data['title'] = 'Manage Cities';
        $data['cities'] = City::where('country_id', $data['country']->code)->get();
        return view('admin.country.city.index', $data);
    }

    public function addEdit($countryId, $cityId = null)
    {
        $data['menu_active']    = 'city';

        if( $cityId ) {
            $data['city'] = City::where('country_id', $countryId)->where(['id' => $cityId])->first();
            if( !$data['city'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['city']->name;
        } else {
            $data['title'] = 'Create city';
            $data['city'] = new City;
        }

        $data['countries'] = Country::pluck_data();

        return view('admin.country.city.add_edit', $data);
    }

    public function save(Request $request, $countryId, $cityId = null )
    {
        $city = City::where('country_id', $request->country_id)->where(['id' => $request->cityId])->first();

        if(!$city){
            $city = new City;
        }

        $request->request->add(['slug' => str_slug($request->get('name'), '-')]);
        
        $city = $city->store($request);

        if( $city instanceof City ) {
            return redirect()->route('admin.city.index', [$countryId])->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($city->errors());
    }

    public function destroy($countryId, $id)
    {
        $city = City::where('country_id', $countryId)->where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
