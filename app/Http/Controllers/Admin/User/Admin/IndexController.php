<?php

namespace App\Http\Controllers\Admin\User\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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
        $data['menu_active']    = 'admin';
        $data['title'] = "Admin";
        if(\Auth::user()->can('admin')) {
            $data['users'] = User::permission('admin')->get();
        } else {
            $data['users'] = User::where('created_by', \Auth::user()->id)->get();
        }
        return view('admin.user.admin.index', $data);
    }

    public function addEdit($userId = null)
    {
        $data['menu_active']    = 'admin';

        if( $userId ) {
            if(\Auth::user()->can('admin')) {
                $data['user'] = User::where(['id' => $userId])->first();
            } else {
                $data['user'] = User::where('created_by', \Auth::user()->id)->where(['id' => $userId])->first();
            }

            if( !$data['user'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['user']->name;
            if($data['user']->can('admin')) {
                $data['user_type'] = 'admin';
            } elseif($data['user']->can('gym owner')) {
                $data['user_type'] = 'gym owner';
            } elseif($data['user']->can('branch manager')) {
                $data['user_type'] = 'branch manager';
            } else {
                $data['user_type'] = 'user';
            }
        } else {
            $data['title'] = 'Create User';
            $data['user'] = new User;
        }

        $data['roles'] = Role::whereHas('permissions', function ($q){
            $q->where('name', 'admin');
        })->pluck('name', 'id');
        $data['genders'] = ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'];

//        $data['user_types'] = ['user' => 'User', 'admin' => 'Admin', 'branch manager' => 'Branch Manager', 'gym owner' => 'Gym Owner'];

        return view('admin.user.admin.add_edit', $data);
    }

    public function save(Request $request, $userId = null )
    {
        // return $request->all();
        if(\Auth::user()->can('admin')) {
            $user = User::where(['id' => $userId])->first();
        } else {
            $user = User::where('created_by', \Auth::user()->id)->where(['id' => $userId])->first();
        }

        if( !$user ) {
            $user = new User;
        }

        $request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);
        $request->request->add(['image_type' => 'base64']);

        $user = $user->store($request);

        if( $user instanceof \App\User ) {
            return redirect()->route('admin.user.admin.edit', $user->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($user->errors());
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('admin')) {
            $user = User::where('id', $id)->first();
        } else {
            $user = User::where('created_by', \Auth::user()->id)->where('id', $id)->first();
        }
        $user->deleteUser();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        if($request->status == 0 || $request->status) {
            if (\Auth::user()->can('admin')) {
                $user = User::where('id', $id)->first();
            } else {
                $user = User::where('created_by', \Auth::user()->id)->where('id', $id)->first();
            }
            $user->updateStatus($request->status);
            return redirect()->back()->with('success', 'Status has been updated successfully!');
        }
        return redirect()->back()->with('error', 'Something! went wrong');
    }
}
