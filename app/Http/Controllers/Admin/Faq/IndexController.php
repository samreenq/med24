<?php

namespace App\Http\Controllers\Admin\Faq;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Faq;
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
        $data['menu_active']    = 'faq';
        $data['title'] = "Faq";
        $data['faqs'] = Faq::get();
        return view('admin.faq.index', $data);
    }

    public function addEdit($faqId = null)
    {
        $data['menu_active']    = 'faq';

        if( $faqId ) {
            $data['faq'] = Faq::where(['id' => $faqId])->first();
            if( !$data['faq'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['faq']->name;
        } else {
            $data['title'] = 'Create Faq';
            $data['faq'] = new Faq;
        }

        return view('admin.faq.add_edit', $data);
    }

    public function save(Request $request, $faqId = null )
    {
        $faq = Faq::where(['id' => $faqId])->first();

        if( !$faq ) {
            $faq = new Faq;
        }

        $faq = $faq->store($request);

        if( $faq instanceof \App\Faq ) {
            return redirect()->route('admin.faq.edit', $faq->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($faq->errors());
    }

    public function destroy($id)
    {
        $faq = Faq::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
