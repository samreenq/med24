<?php

namespace App\Http\Controllers\Admin\Gym\Classes;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\GymClasses;
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
        $data['menu_active']    = 'classes';
        $data['title'] = "Classes";
        $data['classes'] = GymClasses::where('gym_id', $gymId)->get();
        $data['gym_id'] = $gymId;
        return view('admin.gym.classes.index', $data);
    }

    public function addEdit($gymId, $classId = null)
    {
        $data['menu_active']    = 'classes';

        if( $classId ) {
            $data['class'] = GymClasses::where(['id' => $classId])->where('gym_id', $gymId)->first();
            if( !$data['class'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['class']->name;
        } else {
            $data['title'] = 'Add New Class';
            $data['class'] = new GymClasses;
        }

        $data['gym_id'] = $gymId;
        $data['days'] = ['Monday' => 'Monday', 'Tuesday' => 'Tuesday', 'Wednesday' => 'Wednesday', 'Thursday' => 'Thursday', 'Friday' => 'Friday', 'Saturday' => 'Saturday', 'Sunday' => 'Sunday'];
        return view('admin.gym.classes.add_edit', $data);
    }

    public function save(Request $request, $gymId, $classId = null )
    {
        $class = GymClasses::where(['id' => $classId])->where('gym_id', $gymId)->first();

        if( !$class ) {
            $class = new GymClasses;
        }

        $request->request->add(['gym_id' => $gymId]);

        $class = $class->store($request);

        if( $class instanceof \App\GymClasses ) {
            return redirect()->route('admin.gym.class.edit', [$gymId, $class->id])->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($class->errors());
    }

    public function destroy($gymId, $classId)
    {
        $class = GymClasses::where('id', $classId)->where('gym_id', $gymId)->first();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
