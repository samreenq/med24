<?php

namespace App\Http\Controllers\Admin\Roles;

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
        $data['menu_active']    = 'role';
        $data['title'] = "Roles";
        $data['roles'] = Role::get();
        return view('admin.role.index', $data);
    }

    public function addEdit($roleId = null)
    {
        $data['menu_active']    = 'role';

        if( $roleId ) {
            $data['role'] = Role::where(['id' => $roleId])->first();
            if( !$data['role'] ) {
                abort('404');
            }
            if($data['role']->hasPermissionTo('admin')) {
                $data['role_type'] = 'admin';
            } elseif($data['role']->hasPermissionTo('gym owner')) {
                $data['role_type'] = 'gym owner';
            } elseif($data['role']->hasPermissionTo('branch manager')) {
                $data['role_type'] = 'branch manager';
            } else {
                $data['role_type'] = 'user';
            }
            $data['title'] = 'Edit '. $data['role']->name;
        } else {
            $data['title'] = 'Create Role';
            $data['role'] = new Role;
        }

        $data['role_types'] = ['user' => 'User', 'admin' => 'Admin', 'branch manager' => 'Branch Manager', 'gym owner' => 'Gym Owner'];

        return view('admin.role.add_edit', $data);
    }

    public function save(Request $request, $roleId = null )
    {
        $validator = Validator::make($request->all(), ['name' => 'required|string|unique:roles' . ($roleId ? ",name,".$roleId : '')]);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $role = Role::where(['id' => $roleId])->first();

        if( !$role ) {
            $role = new Role;
        }

        $data = $request->all();

        if(!$roleId) {
            $role = $role->create(['name' => $data['name']]);
        } else {
            $role->update(['name' => $data['name']]);
        }

        if($request->role_type == 'admin') {
            if(isset($data['permissions'])) {
                $data['permissions'][] = $request->role_type;
            } else {
                $data['permissions'] = $request->role_type;                
            }
        } elseif($request->role_type == 'gym owner') {
            if(isset($data['permissions_gym'])) {
                $data['permissions'] = $data['permissions_gym'];
                $data['permissions'][] = $request->role_type;
            } else {
                $data['permissions'] = $request->role_type;           
            }
        } else {
            $data['permissions'] = [$request->role_type, 'view sessions', 'scanout', 'view gyms', 'edit gym', 'view gym classes', 'create gym class', 'edit gym class', 'delete gym class']; 
        }

        $role->syncPermissions((isset($data['permissions'])) ? $data['permissions'] : []);

        if( $role instanceof Role ) {
            return redirect()->route('admin.role.edit', $role->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($role->errors());
    }

    public function destroy($id)
    {
        $role = Role::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
