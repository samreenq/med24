<?php

namespace App\Http\Controllers\Frontend;
use App\Appointment;
use App\Doctor;
use App\FamilyMember;
use App\Hospital;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Frontend\WebController;
use App\Insurance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Mail;
use Validator;
use View;
use Mail;

Class AppointmentController extends WebController
{

    public function bookAppointment(Request $request)
    {
        $this->loggedInUser();

        if(!isset($request->hospital_id)) {

            $doctor_modal = new Doctor();
            $doctor = $doctor_modal->with('hospitals')
                ->where('id', $request->doctor_id)
                ->first();

            if ($doctor) {
                $doctor = $doctor->toArray();
            }

            if (isset($doctor) && count($doctor) > 0) {
                $this->_data['doctor'] = translateArr($doctor);
                $this->_data['doctor']['hospitals'] = translateArray($doctor['hospitals']);

                $this->_data['doctor'] = json_decode(json_encode($this->_data['doctor']));
            }
        }

            if(!isset($request->doctor_id)){

                $hospital_modal = new Hospital();
                $hospital =  $hospital_modal->with('doctors')
                    ->where('id',$request->hospital_id)
                    ->first();

                if($hospital){
                    $hospital = $hospital->toArray();
                }

                if(isset($hospital) && count($hospital)>0){
                    $this->_data['hospital'] = translateArr($hospital);
                    $this->_data['hospital']['doctors'] = translateArray($hospital['doctors']);
                    $this->_data['hospital'] = json_decode(json_encode($this->_data['hospital']));
                }

          //
        }
        //echo '<pre>'; print_r(Auth::guard('user')->user()); exit;
            $user_id = Auth::guard('user')->user()->id;
            $family_members = FamilyMember::where('patient_id',$user_id)->get();
         if(count($family_members)>0){
             $family_members = translateArray($family_members->toArray());
             $this->_data['family_members'] = json_decode(json_encode($family_members));
         }

         $insurance = Insurance::where('status',1)->get();
        if(count($insurance)>0){
            $insurance = translateArray($insurance->toArray());
            $this->_data['insurances'] = json_decode(json_encode($insurance));
        }

        $this->_data['doctor_id'] = $request->doctor_id;
        $this->_data['hospital_id'] = isset($request->hospital_id) ? $request->hospital_id : '';

        return view('site.user.book-appointment',$this->_data);
    }

    public function saveAppointment(Request $request)
    {
       // print_r($request->all()); exit;
        $rules = [
            'doctor_id'=>'required',
            'appointment_time'=>'required',
            'appointment_date'=>'required',
            //'family_member_id'=>'required',
            'hospital_id'=>'required'
        ];

        $validator = Validator::make($request->all(),$rules);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator->errors());
        }

        $appointment = new Appointment();
        $request->patient_id = Auth::guard('user')->user()->id;

        $request->request->add([
            'appointment_date' => date('Y-m-d',strtotime($request->appointment_date)),
            'appointment_time' => date('H:i:s',strtotime($request->appointment_time))
        ]);

        $appointment->store($request);

        if($appointment instanceof  Appointment){
            //dd($appointment->patient);
            /*try {
                Mail::send('emailTemplates.appointmentDetails', [
                    'record' => $appointment
                ], function ($message) use ($appointment){
                    $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                        ->to($appointment->patient->email, ($appointment->patient->first_name ?? '').' '.($appointment->patient->last_name ?? ''))
                        ->subject('Appointment Details');
                });
            } catch (Exception $e) {
                return redirect()->back()->withInput()->with('error',$e->getMessage());
            }*/
            return redirect('current-appointment')->with('success','Appointment Saved');

        }
        return redirect()->back()->withInput()->with('error','Something went wrong.');

    }


}
