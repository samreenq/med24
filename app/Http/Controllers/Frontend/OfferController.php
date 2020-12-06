<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\WebController;
use App\Offers;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use View;

/**
 * Class HospitalController
 * @package App\Http\Controllers\Frontend
 */

Class OfferController extends WebController
{
    public $_model;

    public function __construct()
    {
        $this->_model = new Offers();
    }

    public function getDetail(Request $request,$id)
    {
        $this->loggedInUser();
        $this->_data['offer'] = Offers::find($id);
        return view('site.special-offer-description',$this->_data);
    }
    public function getAllSpecial()
    {
        $this->loggedInUser();
        $this->_data['offers_by_categories'] = $this->_model->getOffersCategory();
         $this->_data['featured_offers'] =  $this->_model->getFeatured();
        $this->_data['new_offers'] =  $this->_model->getLatest();


        //echo '<pre>'; print_r($this->_data['new_offers']); exit;
        return view('site.special-offer',$this->_data);
    }

    public function getAll(Request $request)
    {
        if(isset($request->category_id)){
            $query = Offers::where('speciality_id',$request->category_id)
                ->where('status',1);

        }else{
            $query = $this->_model->where('status',1)
                ->orderBy('id','desc');
        }
        $offers = $query->paginate(5);

       // echo '<pre>'; print_r($offers); exit;
        if(count($offers)>0) {
            $offers = $offers->toArray();
            $this->_data['paginator'] = new LengthAwarePaginator($offers['data'], $offers['total'],
                $offers['per_page'], $offers['current_page']
                , ['path' => url('offers')]);

            $this->_data['offers'] = json_decode(json_encode($offers['data']));
        }else{
            $this->_data['offers'] = [];
        }

        return view('site.special-offer-list',$this->_data);
    }

}
