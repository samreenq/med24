<?php

namespace App\Http\Controllers\Admin\Gym;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Gym;
use App\City;
use App\User;
use App\Country;
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
//         $data = [
        //     'Manage Banners', 'view banners', 'create banner', 'edit banner', 'delete banner',
        //     'Manage Users', 'view users', 'create user', 'edit user', 'delete user',
        //     'Manage Roles', 'view roles', 'create role', 'edit role', 'delete role',
        //     'Manage Amenities', 'view amenities', 'create amenity', 'edit amenity', 'delete amenity',
        //     'Manage Countries', 'view countries', 'create country', 'edit country', 'delete country',
        //     'Manage Cities', 'view cities', 'create city', 'edit city', 'delete city',
        //     'Manage Gyms', 'view gyms', 'create gym', 'edit gym', 'delete gym',
        //     'Manage Offers', 'view offers', 'create offer', 'edit offer', 'delete offer',
        //     'Manage Vouchers', 'view vouchers', 'create voucher', 'edit voucher', 'delete voucher',
        //     'Manage Pages', 'edit toc', 'edit privacy_policy',
        //     'Manage Faq', 'view faqs', 'create faq', 'edit faq', 'delete faq',
        //     'view sessions', 'scanout',
        //     'admin', 'branch manager', 'gym owner',
        //     'view gym classes', 'create gym class', 'edit gym class', 'delete gym class',
//             'view gym images', 'create gym image', 'edit gym image', 'delete gym image',
//             'view gym banners', 'create gym banner', 'edit gym banner', 'delete gym banner',
//             'view offer banners', 'create offer banner', 'edit offer banner', 'delete offer banner',
            // 'view notifications',
//        'view feedback',
//        'view newsletters',
//        'view commissions',
//         'view finance',
//         'view vouchers_usage',
//         ];

//        $data = [
//            'Manage Customers', 'view customers', 'create customer', 'edit customer', 'delete customer',
//            'Manage Admins', 'view admins', 'create admin', 'edit admin', 'delete admin',
//            'Manage Gym Owners', 'view gym owners', 'create gym owner', 'edit gym owner', 'delete gym owner',
//        ];
        // $user = \App\User::find(1)->givePermissionTo('gym owner');

        // \Auth::user()->syncPermissions($data);

//         foreach($data as $value) {
//             $permission = Permission::create(['name' => $value]);
//         }

        $data['menu_active'] = 'gym';
        $data['title'] = "Gym";
        if(\Auth::user()->can('admin')) {
            $data['listings'] = Gym::where('parent_id', null)->get();
        } elseif(\Auth::user()->can('gym owner')) {
            $data['listings'] = Gym::where('parent_id', null)->where('owner_id', \Auth::user()->id)->get();
        } else {
            $gym = Gym::where('branch_manager_id', \Auth::user()->id)->first();
            if($gym) {
                $data['listings'] = Gym::where('parent_id', null)->where('owner_id', $gym->owner_id)->get();
            } else {
                $data['listings'] = [];
            }
        }
        return view('admin.gym.index', $data);
    }

    public function branches($parentId)
    {
        $data['menu_active'] = 'gym';
        $data['title'] = "Gym";
        if(\Auth::user()->can('admin')) {
            $data['listings'] = Gym::where(function($q) use($parentId){
                $q->where('parent_id', $parentId);
            })->get();
        } elseif(\Auth::user()->can('gym owner')) {
            $data['listings'] = Gym::where(function($q) use($parentId){
                $q->where('parent_id', $parentId);
            })->where('owner_id', \Auth::user()->id)->get();
        } else {
            $data['listings'] = Gym::where(function($q) use($parentId){
                $q->where('parent_id', $parentId);
            })->where('branch_manager_id', \Auth::user()->id)->get();
        }
        $data['parent_id'] = $parentId;
        return view('admin.gym.branches', $data);
    }

    public function create($parentId = null)
    {
        $data['menu_active']    = 'gym';


        $data['title'] = 'Create Gym';
        $data['listing'] = new Gym;

        $data['parent_id'] = $parentId;
        $data['amenities'] = Amenities::pluck('name', 'id');
        $data['cities'] = City::pluck('name', 'id');
        $data['countries'] = Country::pluck('name', 'id');
        $data['days'] = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $data['owners'] = User::permission('gym owner')->pluck('first_name', 'id');
        $data['branch_managers'] = User::permission('branch manager')->pluck('first_name', 'id');
        $data['parent'] = Gym::find($parentId);

        return view('admin.gym.add_edit', $data);
    }

    public function store(Request $request, $parentId = null )
    {
        $listing = new Gym;

        $request->request->add(['parent_id' => $parentId]);
        $request->request->add(['slug' => str_slug($request->get('name'), '-')]);
        $request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);

        $listing = $listing->store($request);

        if( $listing instanceof \App\Gym ) {
            return redirect()->route('admin.gym.edit', $listing->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($listing->errors());
    }

    public function regenerateToken(Request $request, $listingId = null)
    {
        if( $listingId ) {
            $listing = Gym::where(['id' => $listingId])->first();
            if( !$listing ) {
                abort('404');
            }

            $token = $listing->regenerateToken($request);
        }

        if(isset($token) && $token) {
            return redirect()->route('admin.gym.edit', $listing->id)->with('success', 'Token has been updated successfully!');
        }
        return redirect()->back()->with('error', 'Something! went wrong');
    }

    public function edit($listingId = null)
    {
        $data['menu_active']    = 'gym';

        if( $listingId ) {
            $data['listing'] = Gym::where(['id' => $listingId])->first();
            if( !$data['listing'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['listing']->name;
        }

        $data['amenities'] = Amenities::pluck('name', 'id');
        $data['cities'] = City::pluck('name', 'id');
        $data['countries'] = Country::pluck('name', 'id');
        $data['days'] = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $data['owners'] = User::permission('gym owner')->pluck('first_name', 'id');
        $data['branch_managers'] = User::permission('branch manager')->pluck('first_name', 'id');

        return view('admin.gym.add_edit', $data);
    }

    public function update(Request $request, $listingId = null )
    {
        $listing = Gym::where(['id' => $listingId])->first();

        if( !$listing ) {
            abort('404');
        }

        $request->request->add(['slug' => str_slug($request->get('name'), '-')]);
        $request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);

        $listing = $listing->store($request);

        if( $listing instanceof \App\Gym ) {
            return redirect()->route('admin.gym.edit', $listing->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($listing->errors());
    }

    public function destroy($id)
    {
        $gym = Gym::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }

    public function getCities(Request $request)
    {
        $country_id = $request->country_id;
        $country = Country::find($country_id);

        if($country) {
            $cities = City::where('country_id', $request->country_id)->get();
            if($cities) {
                return response()->json(['status' => 1, 'data' => $cities]);
            }
            return response()->json(['status' => 0, 'data' => 'No Record Found']);
        } else {
            return response()->json(['status' => 0, 'data' => 'Invalid Country Selected']);
        }
    }
}
