<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\WebController;
use App\Like;
use App\Reply;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use View;

/**
 * Class HospitalController
 * @package App\Http\Controllers\Frontend
 */

Class ReviewController extends WebController
{
    public $_model;

    public function __construct()
    {
        $this->_model = new Review();
    }

    public function likeReview(Request $request)
    {
       // echo '<pre>'; print_r($request->id);
        if($request->type == 1){
            $model = new Like();
            $data = array(
                'review_id' => $request->id,
                'patient_id' => Auth::guard('user')->user()->id
            );
            $model->create($data);
        }
        else{
          $dd =  Like::where('review_id',$request->id)
                ->where('review_reply_id',0)
                ->delete();

         // dd($dd);
        }

        if(isset($request->doctor_id)){
            return redirect('doctor-reviews/'.$request->doctor_id);
        }else{
            return redirect('hospital/'.$request->hospital_id);
        }

    }

    public function likeReviewReply(Request $request)
    {
        // echo '<pre>'; print_r($request->id);
        if($request->type == 1) {
            $model = new Like();
            $data = array(
                'review_id' => $request->id,
                'review_reply_id' => $request->reply_id,
                'patient_id' => Auth::guard('user')->user()->id
            );

            $model->create($data);
        }
        else{
            $dd =  Like::where('review_id',$request->id)
                ->where('review_reply_id',$request->reply_id)
                ->delete();

            // dd($dd);
        }

        if(isset($request->doctor_id)){
            return redirect('doctor-reviews/'.$request->doctor_id);
        }else{
            return redirect('hospital/'.$request->hospital_id);
        }
    }

    public function addReviewReply(Request $request)
    {
        if(!empty($request->reply)){
            $model = new Reply();
            $data = array(
                'review_id' => $request->review_id,
                'reply' => $request->reply,
                'patient_id' => Auth::guard('user')->user()->id
            );

            $model->create($data);

            if(isset($request->doctor_id)){
                return redirect('doctor-reviews/'.$request->doctor_id);
            }else{
                return redirect('hospital/'.$request->hospital_id);
            }
        }
    }


}
