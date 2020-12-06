<?php

namespace App\Http\Controllers\Admin\Feedback;

use App\Feedbacks;
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
    public function index()
    {
        $data['menu_active']    = 'feedback';
        $data['title'] = "Feedback";
        $data['feedbacks'] = Feedbacks::get();
        return view('admin.feedback.index', $data);
    }
}
