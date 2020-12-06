<?php

namespace App\Http\Controllers\Admin\Settings;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Settings;
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
    public function get_settings()
    {
        $data['menu_active']    = 'settings';
        $data['title'] = "General Settings";
        $data['settings'] = Settings::get();
        return view('admin.settings.index', $data);
    }

    public function post_settings(Request $request)
    {
        foreach($request->all() as $key => $val) {
            if($key != '_token') {
                $row = Settings::where('name', $key)->first();

                if(!$row) {
                    $row = new Settings;
                    $row->name = $key;
                }

                $row->value = $val;

                $row->save();
            }
        }

        return redirect()->route('admin.settings.index')->with('success', 'Data has been saved successfully!');
    }
}
