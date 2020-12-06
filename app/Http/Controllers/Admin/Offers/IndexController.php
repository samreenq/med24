<?php

namespace App\Http\Controllers\Admin\Offers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Offers;

use App\Hospital;
use App\Doctor;
use App\Pharmacy;

use Illuminate\Support\Facades\Validator;
use App\Classes\Helper;

class IndexController extends \App\Http\Controllers\Admin\Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
	 
	public $get_title = 'offer';
	
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
        $data['menu_active']    = strtolower($this->get_title);
        $data['title'] = $this->get_title;
		
        if(\Auth::user()->can('admin')) {
            $data['records'] = Offers::paginate(15);
        } else {
            $data['records'] = Offers::where('created_by', \Auth::user()->id)->paginate(15);
        }
        return view('admin.offers.index', $data);
    }

    public function addEdit($offerId = null)
    {
        $data['menu_active']    = 'offer';
		
		$get_hospitals = Hospital::all();
		$get_doctors = Doctor::all();
		$get_pharmacies = Pharmacy::all();
		
		foreach($get_hospitals as $key => $val)
		{
			$data['hospitals'][$val->id] = $val->name;
		}
		
		foreach($get_doctors as $key => $val)
		{
			$data['doctors'][$val->id] = $val->first_name . ' ' . $val->last_name;
		}
		
		foreach($get_pharmacies as $key => $val)
		{
			$data['pharmacies'][$val->id] = $val->name;
		}

        if( $offerId ) {
            if(\Auth::user()->can('admin')) {
                $data['offer'] = Offers::where(['id' => $offerId])->first();
            } else {
                $data['offer'] = Offers::where('created_by', \Auth::user()->id)->where(['id' => $offerId])->first();
            }
            if( !$data['offer'] ) {
                abort('404');
            }
            $data['title'] = 'Edit '. $data['offer']->name;
        } else {
            $data['title'] = 'Create Offer';
            $data['offer'] = new Offers;
        }

        //$data['listings'] = Gym::pluck_data();

        return view('admin.offers.add_edit', $data);
    }

    public function save(Request $request, $offerId = null )
    {
        if(\Auth::user()->can('admin')) {
            $offer = Offers::where(['id' => $offerId])->first();
        } else {
            $offer = Offers::where('created_by', \Auth::user()->id)->where(['id' => $offerId])->first();
        }

        if( !$offer ) {
            $offer = new Offers;
        }

        $request->request->add(['slug' => str_slug($request->get('name'))]);
        $request->request->add(['status' => $request->get('status') == 'on' ? 1 : 0]);
        $request->request->add(['image_type' => 'base64']);

        $offer = $offer->store($request);

        if( $offer instanceof \App\Offers ) {
            return redirect()->route('admin.offers.edit', $offer->id)->with('success', 'Data has been saved successfully!');
        }
        return redirect()->back()->withInput()->withErrors($offer->errors());
    }

    public function destroy($id)
    {
        if(\Auth::user()->can('admin')) {
            $offer = Offers::where('id', $id)->first();
        } else {
            $offer = Offers::where('created_by', \Auth::user()->id)->where('id', $id)->first();
        }
        $offer->deleteOffer();
        return redirect()->back()->with('success', 'Data has been deleted successfully!');
    }
}
