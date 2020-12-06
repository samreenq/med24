<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\WebController;
use App\MedicalInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use View;

Class MedicalController extends WebController
{
    public $_data = [];

    public function __construct()
    {
    }


    public function healthRecord()
    {
        $this->loggedInUser();
        $user = $this->_data['login_user'];

        $medical_info = MedicalInfo::where('patient_id',$user->id)
            ->where('type','health_record')
            ->paginate(10);

        $this->_data['record'] = $medical_info;
        // echo '<pre>'; print_r($medical_info);
        return view('site.user.health-record',$this->_data);
    }

    public function medicalCondition()
    {
        $this->loggedInUser();
        $user = $this->_data['login_user'];

        $medical_info = MedicalInfo::where('patient_id',$user->id)
            ->where('type','medical_condition')
            ->paginate(10);

        $this->_data['record'] = $medical_info;
        // echo '<pre>'; print_r($medical_info);
        return view('site.user.medical-info',$this->_data);
    }

    public function addHealthRecord()
    {
        $this->loggedInUser();
        $this->_data['action'] = 'Add';
        return view('site.user.add-health-record',$this->_data);
    }

    public function saveHealthRecord(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('add-health-record')->withInput()->withErrors($validator->errors());
        }

        if($request->id){
            $model = MedicalInfo::where('id',$request->id)
                ->where('patient_id',Auth::user()->id)
                ->first();
        }

        if (!isset($model)) {
            $model = new MedicalInfo();
        }

        $request->patient_id = Auth::guard('user')->user()->id;
        $request->type = 'health_record';
        $save_record = $model->store($request);

        if($save_record){
            return redirect('health-record')->with('success','Health record added successfully');
        }
        return redirect('add-health-record')->with('error','Something went wrong');

    }

    public function addMedicalInfo()
    {
        $this->loggedInUser();
        $this->_data['action'] = 'Add';
        return view('site.user.add-medical-info',$this->_data);
    }

    public function saveMedicalInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('add-medical-info')->withInput()->withErrors($validator->errors());
        }

        if($request->id){
            $model = MedicalInfo::where('id',$request->id)
                ->where('patient_id',Auth::user()->id)
                ->first();
        }

        if (!isset($model)) {
            $model = new MedicalInfo();
        }

        $request->patient_id = Auth::guard('user')->user()->id;
        $request->type = 'medical_condition';
        $save_record = $model->store($request);

        if($save_record){
            return redirect('medical-info')->with('success','Medical Info added successfully');
        }
        return redirect('add-medical-info')->with('error','Something went wrong');

    }





}
