<?php

namespace App\Http\Controllers\Admin\Logs;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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
    public function index(Request $request)
    {
        $data['menu_active']    = 'settings';
        $data['title'] = "Logs";
        $modules = Activity::select('subject_type')->distinct()->get();
        foreach ($modules as $row) {
            $data['modules'][$row->subject_type] = $row->subject_type;
        }
//        return $data['modules'];
        $data['users'] = Activity::select('causer_id')->distinct()->get();

        if($request->isMethod('POST')) {
            $data['from'] = ($request->from) ? $request->from : date('Y-m-d 00:00:00');
            $data['to'] = ($request->to) ? $request->to : date('Y-m-d 23:59:59');
            $data['module_name'] = $request->module_name;
            $query = Activity::where('created_at', '>=', $data['from'])->where('created_at', '<=', $data['to']);
            if($data['module_name']) {
                $query = $query->where('subject_type', $data['module_name']);
            }
            $data['logs'] = $query->get();
        }

        return view('admin.logs.index', $data);
    }
}
