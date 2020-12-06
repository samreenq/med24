<?php

namespace App\Http\Controllers\Frontend;

use App\PatientFavourites;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

Class AjaxController extends WebController
{
    public $_data = [];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function addToFav(Request $request)
    {
       // echo '<pre>'; print_r($request->all()); exit;
        if($request->type == 1) {
            $data = array(
                'patient_id' => Auth::guard('user')->user()->id,
            );

            if (isset($request->doctor_id)) {
                $data['doctor_id'] = $request->doctor_id;
            }

            if (isset($request->hospital_id)) {
                $data['hospital_id'] = $request->hospital_id;
            }

            $patient_fav_model = new PatientFavourites();
            $store = $patient_fav_model->store((object)$data);
        }else{

           $del = PatientFavourites::where('patient_id',Auth::guard('user')->user()->id);
            if (isset($request->doctor_id)) {
                $del->where('doctor_id',$request->doctor_id);
            }

            if (isset($request->hospital_id)) {
                $del->where('hospital_id',$request->hospital_id);
            }

           $store = $del->delete();
        }

        if($store){
            return json_encode(array(
                'error' => 0,
                'message' => 'Success',
            ));
        }
        return json_encode(array(
            'error' => 1,
            'message' => 'There is some issue while saving record',
        ));
    }

    public function reviewHospital(Request $request)
    {
        if(!Auth::guard('user')->check()){
            $return = array(
                'error' => 1,
                'message' => 'Please login to add review'
            );
            echo json_encode($return); exit;
        }

        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'review' => 'required|string',
        ]);

        if ($validator->fails()) {
            $return = array(
                'error' => 1,
                'message' => $validator->errors()->first()
            );
            echo json_encode($return); exit;
        }

        $data = array(
            'patient_id' => Auth::guard('user')->user()->id,
            'hospital_id' => $request->hospital_id,
            'rating' => $request->rating,
            'review' => $request->review,
            'added_by' => 'Patient'
        );

        $review_model = new Review();
        $save_record = $review_model->store((object)$data);
        if($save_record){
            $return = array(
              'error' => 0,
              'message' =>   'Review added successfully'
            );
            echo json_encode($return); exit;
        }
        $return = array(
            'error' => 1,
            'message' => 'Something went wrong'
        );
        echo json_encode($return); exit;
    }


}
