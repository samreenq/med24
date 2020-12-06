<?php

namespace App\Http\Controllers\Frontend;

use App\Hospital;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\WebController;
use App\Options;
use App\PatientFavourites;
use App\Review;
use App\Speciality;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use View;

/**
 * Class HospitalController
 * @package App\Http\Controllers\Frontend
 */

Class HospitalController extends WebController
{
    public $_model;

    public function __construct()
    {
        $this->_model = new Hospital();
    }

    public function getDetail(Request $request,$id)
    {
        //dd($request->is_book);
        $this->loggedInUser();
        $user_id = false;
        if(Auth::guard('user')->check()) {
            $user_id = Auth::guard('user')->user()->id;
        }
        $hospital = $this->_model->getDetail($id,false,$user_id);
        $this->_data['hospital'] = json_decode(json_encode($hospital));
       // echo '<pre>'; print_r($this->_data['hospital']); exit;
        //AverageRating
        if(Auth::guard('user')->check()) {
           // $user_id = Auth::guard('user')->user()->id;
            $this->_data['is_fav'] = 0;

            if (isset($user_id)) {
                $patient_fav = PatientFavourites::where('patient_id', $user_id)
                    ->where('hospital_id', '>', 0)->get();

                if (count($patient_fav) > 0) {
                    foreach ($patient_fav as $fav) {

                        if ($id == $fav->hospital_id) {
                            $this->_data['is_fav'] = 1;
                            break;
                        }

                    }

                }
            }

            //Get Hospital Review
            $this->_data['h_review'] = Review::where('patient_id',$user_id)->where('hospital_id',$id)->first();
            //dd($user_id);
        }

        $this->_data['is_book'] = isset($request->is_book) ? $request->is_book : 0;

        //echo '<pre>'; print_r($this->_data); exit;

        return view('site.user.hospital-overview',$this->_data);
    }

    public function getAll(Request $request)
    {
        $this->loggedInUser();
        $this->getSpeciality();

        $user_id = false;
        if(Auth::guard('user')->check()){
            $user_id = Auth::guard('user')->user()->id;
        }
        $hospitals = $this->_model->getAll($user_id);
        $pagination = $hospitals['pagination'];

        if(isset($pagination)) {
            $this->_data['paginator'] = $paginator = new LengthAwarePaginator($hospitals['hospitals'], $pagination['total'],
                $pagination['per_page'], $pagination['current_page']
                , ['path' => url('hospitals')]);
        }

        $option = Options::where('option_name','search_title')->first();
        $this->_data['search_title'] = isset($option->option_value) ? $option->option_value : '';

        $this->_data['hospitals'] = json_decode(json_encode($hospitals['hospitals']));
        return view('site.user.hospital-list',$this->_data);
    }

    public function favourite(Request $request)
    {
        $this->loggedInUser();
        $this->getSpeciality();

        $user_id = false;
        if(Auth::guard('user')->check()){
            $user_id = Auth::guard('user')->user()->id;
        }
        $fav_model = new PatientFavourites();
        $fav_doc = $fav_model->where('patient_id',$user_id)
            ->where('hospital_id','>',0)
            ->get();

        $ids = false;
        if(count($fav_doc)>0){
            foreach($fav_doc as $doc){
                $ids[] = $doc->hospital_idr;
            }
        }
        $hospitals = $this->_model->getAll($user_id,false,$ids);

        $this->_data['hospitals'] = json_decode(json_encode($hospitals));
        return view('site.user.favourite-hospital',$this->_data);
    }

}
