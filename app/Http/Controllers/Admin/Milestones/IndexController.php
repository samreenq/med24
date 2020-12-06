<?php

namespace App\Http\Controllers\Admin\Milestones;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Vouchers;
use App\Milestones;
use App\User;
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
        $data['menu_active']    = 'milestones';
        $data['title'] = "Milestones";
        $data['milestones'] = Milestones::get();
        return view('admin.milestones.index', $data);
    }

    public function addEdit($milestoneId = null)
    {
        $data['menu_active']    = 'milestones';

        if( $milestoneId ) {
            $data['milestone'] = Milestones::where(['id' => $milestoneId])->first();
            if( !$data['milestone'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['milestone']->name;
        } else {
            $data['title'] = 'Create Milestone';
            $data['milestone'] = new Milestones();
        }

        return view('admin.milestones.add_edit', $data);
    }

    public function save(Request $request, $milestoneId = null )
    {
        $milestone = Milestones::where(['id' => $milestoneId])->first();

        if( !$milestone ) {
            $milestone = new Milestones();
        }

        $milestone = $milestone->store($request);

        if( $milestone instanceof \App\Milestones ) {
            return redirect()->route('admin.milestones.edit', $milestone->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($milestone->errors());
    }

    public function destroy($id)
    {
        $milestone = Milestones::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
