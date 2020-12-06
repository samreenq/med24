<?php

namespace App\Http\Controllers\Admin\Pages;

use App\Newsletter;
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
    public function get_toc()
    {
        $data['menu_active']    = 'pages';
        $data['title'] = "Terms & Conditions";
        $data['toc'] = Settings::where('name', 'toc')->first();
        return view('admin.pages.toc', $data);
    }

    public function get_privacy_policy()
    {
        $data['menu_active']    = 'pages';
        $data['title'] = "Privacy Policy";
        $data['privacy_policy'] = Settings::where('name', 'privacy_policy')->first();
        return view('admin.pages.privacy_policy', $data);
    }

    public function get_faq()
    {
        $data['menu_active']    = 'pages';
        $data['title'] = "Offers";
        $data['offers'] = Offers::paginate(15);
        return view('admin.offers.index', $data);
    }

    public function post_toc(Request $request)
    {
        $toc = Settings::where('name', 'toc')->first();

        if(!$toc) {
            $toc = new Settings;
            $toc->name = 'toc';
        }

        $toc->value = $request->toc;

        $toc->save();

        return redirect()->route('admin.pages.get_toc')->with('success', 'Data has been saved successfully!');
    }

    public function post_privacy_policy(Request $request)
    {
        $toc = Settings::where('name', 'privacy_policy')->first();

        if(!$toc) {
            $toc = new Settings;
            $toc->name = 'privacy_policy';
        }

        $toc->value = $request->privacy_policy;

        $toc->save();

        return redirect()->route('admin.pages.get_privacy_policy')->with('success', 'Data has been saved successfully!');
    }

    public function newsletter()
    {
        $data['menu_active']    = 'pages';
        $data['title'] = "Newsletter";
        $data['newsletters'] = Newsletter::orderBy('id', 'desc')->get();
        return view('admin.pages.newsletter', $data);
    }
}
