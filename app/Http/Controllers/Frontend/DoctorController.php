<?php

namespace App\Http\Controllers\Frontend;

use App\Doctor;
use App\Hospital;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\WebController;
use App\Like;
use App\Options;
use App\Patient;
use App\PatientFavourites;
use App\Review;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use View;
use Validator;

/**
 * Class HospitalController
 * @package App\Http\Controllers\Frontend
 */

Class DoctorController extends WebController
{
    public $_data;
    public $_model;

    public function __construct(Request $request)
    {
        $this->_data = [];
        $this->_model = new Doctor();

        if(isset($request->id)) {
            $this->_data['doctor_id'] = $request->id;
            $doctor_model = $this->_model;
            $doctor = $doctor_model->getDetail($request->id);
            $this->_data['doctor'] = json_decode(json_encode($doctor));

            //echo '<pre>'; print_r($doctor); exit;
            $review_model = new Review();
            $this->_data['review_count'] = $review_model->where('doctor_id', $request->id)->where('status', 1)->count();
        }
    }

    private function _chkFavDoctor(){

        if(Auth::guard('user')->check()) {
            $user_id = Auth::guard('user')->user()->id;
            $this->_data['is_fav'] = $this->_model->checkDoctorFav($user_id,$this->_data['doctor_id']);
        }
    }

    public function profile(Request $request,$id)
    {
        $this->loggedInUser();
        $this->_chkFavDoctor();
        return view('site.user.doctor-profile',$this->_data);
    }

    public function reviews(Request $request,$id)
    {
        $this->loggedInUser();
        $this->_chkFavDoctor();

        $review_model = new Review();
        $reviews = $this->_data['reviews'] = $review_model->with('patient','doctor','hospital','replies','replies.patient','replies.doctor','replies.hospital')
            ->where('doctor_id',$id)->get();
       //

        if (count($reviews) > 0) {
            $records = $reviews->toArray();
            $records = translateArray($records);

       // if(Auth::guard('user')->check()) {

            $like_model = new Like();

                $j = 0;
                foreach ($reviews as $review) {

                    $records[$j]['patient'] = translateArr($records[$j]['patient']);

                    if(Auth::guard('user')->check()) {
                        $records[$j]['is_like'] = $like_model->where('review_id', $review->id)
                            ->where('review_reply_id', 0)
                            ->where('patient_id', Auth::guard('user')->user()->id)
                            ->count();
                    }

                    if(count($review['replies'])>0){
                        $i = 0;

                        $records[$j]['replies'] = translateArray($records[$j]['replies']);

                        foreach($review['replies'] as $replies){

                            if(isset($replies->patient) && is_object($replies->patient)){
                                $records[$j]['replies'][$i]['patient'] = translateArr($records[$j]['replies'][$i]['patient']);

                            }

                            if(isset($replies->doctor) && is_object($replies->doctor)){
                                $records[$j]['replies'][$i]['doctor'] = translateArr($records[$j]['replies'][$i]['doctor']);
                            }

                            if(isset($replies->hospital) && is_object($replies->hospital)){
                                $records[$j]['replies'][$i]['hospital'] = translateArr($records[$j]['replies'][$i]['hospital']);
                            }

                            if(Auth::guard('user')->check()) {
                                $records[$j]['replies'][$i]['is_like'] = $like_model->where('review_reply_id', $replies->id)
                                    ->where('review_reply_id', '>', 0)
                                    ->where('patient_id', Auth::guard('user')->user()->id)
                                    ->count();
                            }
                            $i++;
                        }
                    }

                    $j++;
                }
          //  }
            $this->_data['reviews'] = json_decode(json_encode($records));
        }
        else{
            $this->_data['reviews'] = [];
        }

      //  echo '<pre>'; print_r($this->_data['reviews']); exit;
        return view('site.user.doctor-reviews',$this->_data);
    }

    public function submitReview(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required',
            'review' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('patient-experience/'.$id)->withInput()->withErrors($validator->errors());
        }

        $data = array(
            'patient_id' => Auth::guard('user')->user()->id,
            'doctor_id' => $id,
            'rating' => $request->rating,
            'review' => $request->review,
            'added_by' => 'Patient'
        );

        $review_model = new Review();
        $save_record = $review_model->store((object)$data);
        if($save_record){
            return redirect('doctor-reviews/'.$id)->with('success','Review added successfully');
        }
        return redirect('patient-experience/'.$id)->with('error','Something went wrong');

    }

    public function patientExperience(Request $request,$id)
    {
        $this->loggedInUser();
        $this->_chkFavDoctor();
        if(Auth::guard('user')->check()){
            $this->_data['d_review'] = Review::where('patient_id',Auth::guard('user')->user()->id)
                ->where('doctor_id',$id)->first();
        }
        return view('site.user.patient-experience',$this->_data);
    }

    public function affiliatedHospital()
    {
        $this->loggedInUser();
        $this->_chkFavDoctor();

        $this->_data['doctor']->hospitals = translateArray(json_decode(json_encode($this->_data['doctor']->hospitals),true));
       if(count($this->_data['doctor']->hospitals)>0){
           $j= 0;

           $hospital_model = new Hospital();

           foreach($this->_data['doctor']->hospitals as $aff_hosp){

               if(Auth::guard('user')->check()) {
                   $patient_id = Auth::guard('user')->user()->id;
                   $is_doctor_fav = PatientFavourites::where('patient_id', $patient_id)->where('hospital_id', $aff_hosp['id'])->count();
                   $this->_data['doctor']->hospitals[$j]['is_fav'] = ($is_doctor_fav > 0) ? 1 : 0;
               }
                $this->_data['doctor']->hospitals[$j]['speciality'] = $hospital_model->getSpeciality($aff_hosp['id']);
               $j++;
           }
       }

        $this->_data['doctor']->hospitals = json_decode(json_encode($this->_data['doctor']->hospitals));

        //echo '<pre>'; print_r($this->_data['doctor']); exit;
        return view('site.user.affiliated-hospital',$this->_data);
    }

    public function getAll(Request $request)
    {
        $this->loggedInUser();
        $this->getSpeciality();
       // dd($request->path());
        $user_id = false;
        if(Auth::guard('user')->check()){
            $user_id = Auth::guard('user')->user()->id;
        }
        $doctors = $this->_model->getAll($user_id);
        //echo '<pre>'; print_r($doctors); exit;
        $this->_data['doctors'] = json_decode(json_encode($doctors['doctors']));
        $pagination = $doctors['pagination'];

        if(isset($pagination)) {
            $this->_data['paginator'] = $paginator = new LengthAwarePaginator($doctors['doctors'], $pagination['total'],
                $pagination['per_page'], $pagination['current_page']
                , ['path' => url('doctors')]);
        }

        $option = Options::where('option_name','search_title')->first();
        $this->_data['search_title'] = isset($option->option_value) ? $option->option_value : '';
       // echo '<pre>'; print_r($this->_data['doctors']); exit;

        return view('site.user.doctor-list',$this->_data);
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
            ->where('doctor_id','>',0)
            ->get();

        $ids = false;
        if(count($fav_doc)>0){
            foreach($fav_doc as $doc){
                $ids[] = $doc->doctor_id;
            }
        }

        $doctors = $this->_model->getAll($user_id,false,$ids);
        //echo '<pre>'; print_r($doctors); exit;
        $this->_data['doctors'] = json_decode(json_encode($doctors));
        return view('site.user.favourite-doctor',$this->_data);
    }


}
