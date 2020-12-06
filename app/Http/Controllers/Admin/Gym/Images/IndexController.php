<?php

namespace App\Http\Controllers\Admin\Gym\Images;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\GymImages;
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
    public function index($gymId)
    {
        $data['menu_active']    = 'gym';
        $data['title'] = "Gym Images";
        $data['images'] = GymImages::where('gym_id', $gymId)->get();
        $data['gym_id'] = $gymId;
        $data['image'] = new GymImages;
        return view('admin.gym.images.index', $data);
    }

    public function save(Request $request, $gymId, $imageId = null )
    {
        $image = GymImages::where(['id' => $imageId])->where('gym_id', $gymId)->first();

        if( !$image ) {
            $image = new GymImages;
        }

        $request->request->add(['gym_id' => $gymId]);

        $image = $image->store($request);

        if( $image instanceof \App\GymImages ) {
            return redirect()->route('admin.gym.images.index', $gymId)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($image->errors());
    }

    public function destroy($gymId, $imageId)
    {
        if(\Auth::user()->can('admin')) {
            $image = GymImages::where('id', $imageId)->first();
        } else {
            $image = GymImages::where('created_by', \Auth::user()->id)->where('id', $imageId)->first();
        }
        $image->deleteImage();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
