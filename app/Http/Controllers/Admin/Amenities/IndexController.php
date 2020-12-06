<?php

namespace App\Http\Controllers\Admin\Amenities;

use App\UserSessions;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Amenities;
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
        $data['menu_active']    = 'amenities';
        $data['title'] = "Amenities";
        $data['amenities'] = Amenities::paginate(15);
        return view('admin.amenities.index', $data);
    }

    public function addEdit($amenityId = null)
    {
        $data['menu_active']    = 'amenities';

        if( $amenityId ) {
            $data['amenities'] = Amenities::where(['id' => $amenityId])->first();
            if( !$data['amenities'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['amenities']->name;
        } else {
            $data['title'] = 'Create Amenities';
            $data['amenities'] = new Amenities;
        }

        return view('admin.amenities.add_edit', $data);
    }

    public function save(Request $request, $amenityId = null )
    {
        $amenities = Amenities::where(['id' => $amenityId])->first();

        if( !$amenities ) {
            $amenities = new Amenities;
        }

        $request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);
        $request->request->add(['slug' => str_slug($request->get('name'), '-')]);

        $amenities = $amenities->store($request);

        if( $amenities instanceof \App\Amenities ) {
            return redirect()->route('admin.amenities.edit', $amenities->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($amenities->errors());
    }

    public function destroy($id)
    {
        $amenity = Amenities::where('id', $id)->first();
        $amenity->deleteAmenity();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
