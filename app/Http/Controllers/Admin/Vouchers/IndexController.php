<?php

namespace App\Http\Controllers\Admin\Vouchers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Vouchers;
use App\Gym;
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
        $data['menu_active']    = 'voucher';
        $data['title'] = "Vouchers";
        $data['vouchers'] = Vouchers::paginate(15);
        return view('admin.vouchers.index', $data);
    }

    public function addEdit($voucherId = null)
    {
        $data['menu_active']    = 'voucher';

        if( $voucherId ) {
            $data['voucher'] = Vouchers::where(['id' => $voucherId])->first();
            if( !$data['voucher'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['voucher']->name;
        } else {
            $data['title'] = 'Create Voucher';
            $data['voucher'] = new Vouchers;
        }

        $data['listings'] = Gym::pluck_data();
        $data['users'] = User::pluck_data();

        return view('admin.vouchers.add_edit', $data);
    }

    public function save(Request $request, $voucherId = null )
    {
        $voucher = Vouchers::where(['id' => $voucherId])->first();

        if( !$voucher ) {
            $voucher = new Vouchers;
        }

        $request->request->add(['slug' => str_slug($request->get('name'))]);
        $request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);

        $voucher = $voucher->store($request);

        if( $voucher instanceof \App\Vouchers ) {
            return redirect()->route('admin.vouchers.edit', $voucher->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($voucher->errors());
    }

    public function destroy($id)
    {
        $voucher = Vouchers::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
