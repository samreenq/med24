<?php

namespace App\Http\Controllers\Frontend;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Patient\UpdateProfileRequest;
use App\Http\Resources\Api\Patient\PatientProfile;
use App\MedicalInfo;
use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Frontend\WebController;
use Illuminate\Support\Facades\Validator;

Class PatientController extends WebController
{
    public $_data = [];

    public function __construct()
    {

    }

    public function edit_profile()
    {
        $this->loggedInUser();
        return view('site.user.edit-profile',$this->_data);
    }

    public function updateProfile(Request $request){

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string',
            'email' => (!$request->patient_id) ? 'string|email|unique:patients' : 'string|email|unique:patients,email,'.$request->patient_id,
            'phone_code' => 'string',
            'phone_no' => (!$request->patient_id) ? 'numeric|unique:patients|digits_between:9,14' : 'numeric|unique:patients,phone_no,'.$request->patient_id,
        ]);

        if ($validator->fails()) {
            return redirect('edit-profile')->withInput()->withErrors($validator->errors());
        }

        $patient = Patient::find($request->patient_id);
        //echo '<pre>'; print_r($request->all()); exit;
        if($patient){
            $patient->store($request);
            if($patient instanceof Patient) {
                $patient = new PatientProfile($patient);

                return redirect('edit-profile')->with('success', 'Profile Updated Successfully');
            }
            return redirect('edit-profile')->withInput()->with('error','Something Went Wrong!');
        }
        return redirect('edit-profile')->withInput()->with('error','No Records Found');

    }

    public function changePassword()
    {
        $this->loggedInUser();
        return view('site.user.change-password',$this->_data);
    }

    public function e__prescriptions()
    {
        $this->loggedInUser();
        return view('site.e-prescriptions',$this->_data);
    }
    public function add__prescriptions()
    {
        $this->loggedInUser();
        return view('site.add-prescriptions',$this->_data);
    }

    public function current__appointment()
    {
        $this->loggedInUser();
        $current_appointments    =  Appointment::with('hospital','patient','doctor','insurance')
            ->whereIn('status',['pending','scheduled'])
            //->where('patient_id',229)
            ->where('patient_id',Auth::guard('user')->user()->id)
            ->paginate(5);

        $appointments = $current_appointments->toArray();

        $appointments['data'] = translateArray($appointments['data']);

        if(count($appointments['data'])>0){
            foreach ($appointments['data'] as $key => $app){
                $appointments['data'][$key]['doctor'] = translateArr($app['doctor']);
                $appointments['data'][$key]['patient'] = translateArr($app['patient']);
                $appointments['data'][$key]['hospital'] = translateArr($app['hospital']);
            }
        }


        if(count($appointments['data'])>0){
            $this->_data['paginator'] = $paginator = new LengthAwarePaginator($appointments['data'], $appointments['total'],
                $appointments['per_page'], $appointments['current_page']
                , ['path' => url('current-appointment')]);

           // echo '<pre>'; print_r($this->_data['paginator']); exit;
        }

        $this->_data['appointments'] = json_decode(json_encode($appointments['data']));
        //echo '<pre>'; print_r($appointments); exit;
       //
        return view('site.user.current-appointment',$this->_data);
    }
    public function past__appointment()
    {
        $this->loggedInUser();

        $past_appointments=Appointment::with('hospital','patient','doctor','insurance')
            ->whereIn('status',['completed','cancelled','rejected'])
            ->where('patient_id',102)
            ->orderBy('id','ASC')
            ->paginate(5);

        $appointments = $past_appointments->toArray();

        $appointments['data'] = translateArray($appointments['data']);

        if(count($appointments['data'])>0){
            foreach ($appointments['data'] as $key => $app){
                $appointments['data'][$key]['doctor'] = translateArr($app['doctor']);
                $appointments['data'][$key]['patient'] = translateArr($app['patient']);
                $appointments['data'][$key]['hospital'] = translateArr($app['hospital']);
            }

            $this->_data['paginator'] = $paginator = new LengthAwarePaginator($appointments['data'], $appointments['total'],
                $appointments['per_page'], $appointments['current_page']
                , ['path' => url('past-appointment')]);

            // echo '<pre>'; print_r($this->_data['paginator']); exit;
        }
        $this->_data['appointments'] = json_decode(json_encode($appointments['data']));
        //echo '<pre>'; print_r($appointments); exit;

        return view('site.user.past-appointment',$this->_data);
    }

    public function insuranceCard()
    {
        $this->loggedInUser();

        //echo '<pre>'; print_r($this->_data['login_user']); exit;
        return view('site.user.insurance-card',$this->_data);
    }

    public function saveInsuranceCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'insurance_card_front' => 'required|file',
            'insurance_card_back' => 'required|file',
        ]);

        if ($validator->fails()) {
            return redirect('insurance-card')->withInput()->withErrors($validator->errors());
        }

        if(Auth::guard('user')->check()) {

            $patient_id = Auth::guard('user')->user()->id;

            $patient = Patient::find($patient_id);
            $request->merge(['id'=>$patient_id]);
            //echo '<pre>'; print_r($request->all()); exit;
            if ($patient) {
               $save = $patient->store($request);
               if($save)
                return redirect('insurance-card')->with('success','Insurance Card save successfully');
            }
        }

        return redirect('insurance-card')->with('error','Sorry There is something went wrong');

    }

    public function emirate()
    {
        $this->loggedInUser();
        return view('site.user.emirate',$this->_data);
    }

    public function saveEmirate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_card_front' => 'required|file',
            'id_card_back' => 'required|file',
        ]);

        if ($validator->fails()) {
            return redirect('emirate')->withInput()->withErrors($validator->errors());
        }

        if(Auth::guard('user')->check()) {

            $patient_id = Auth::guard('user')->user()->id;

            $patient = Patient::find($patient_id);
            $request->merge(['id'=>$patient_id]);
            //echo '<pre>'; print_r($request->all()); exit;
            if ($patient) {
                $patient->store($request);
                return redirect('emirate')->with('success','Insurance Card save successfully');
            }
        }

        return redirect('emirate')->with('error','Sorry There is something went wrong');

    }

}
