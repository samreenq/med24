<?php

namespace App\Http\Controllers\Admin\Banners;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Banners;
use App\Offers;
use App\Gym;
use Illuminate\Support\Facades\Validator;
use App\Classes\Helper;

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
    public function index(Request $request)
    {
        $data['menu_active'] = 'banner';
        $data['title'] = "Banners";
        $data['module_type'] = $request->module_type;
        $data['banners'] = Banners::where('module_type', $request->module_type)->get();
        return view('admin.banners.index', $data);
    }

    public function addEdit(Request $request, $bannerId = null)
    {
        $data['menu_active']    = 'banner';

        if( $bannerId ) {
            $data['banner'] = Banners::where('module_type', $request->module_type)->where(['id' => $bannerId])->first();
            if( !$data['banner'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['banner']->name;
        } else {
            $data['title'] = 'Create Banner';
            $data['banner'] = new Banners;
        }

//        $data['listings'] = Gym::pluck_data();
        $data['offers'] = Offers::pluck_data();
        $data['module_type'] = $request->module_type;
//        $data['module_types'] = ['general' => 'General', 'gym' => 'Gym', 'offers' => 'Offers'];

        return view('admin.banners.add_edit', $data);
    }

    public function save(Request $request, $bannerId = null)
    {
        $banner = Banners::where('module_type', $request->module_type)->where(['id' => $bannerId])->first();

        if( !$banner ) {
            $banner = new Banners;
        }

        $request->request->add(['image_type' => 'base64']);
        $request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);
        $banner = $banner->store($request);

        if( $banner instanceof \App\Banners ) {
            return redirect()->route('admin.banners.edit', [$banner->id, 'module_type' => $request->module_type])->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($banner->errors());
    }

    public function destroy(Request $request, $id)
    {
        $banner = Banners::where('module_type', $request->module_type)->where('id', $id)->first();
        $banner->deleteBanner();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
