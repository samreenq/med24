<?php
namespace App\Http\Controllers\Api\Patient;
use App\Appointment;
use App\InsurancesPlans;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\SaveAppointmentRequest;
use App\Http\Requests\Api\SaveAppointmentCallRequest;
use App\Http\Resources\Api\Appointment\AppointmentResource;
use App\Http\Resources\Api\Appointment\AppointmentCallRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;

class AppointmentController extends ApiController{
    public function index(Request $request){

        $appointments=Appointment::with('doctor', 'hospital', 'familyMember', 'insurance');
        if($request->type == 'past'){
            $appointments = $appointments->whereIn('status', ['completed', 'cancelled', 'rejected']);
        }

        if($request->type=='current'){
           $appointments->whereIn('status', ['pending', 'scheduled']);
        }
        
        if (isset($request->fromDate) && $request->fromDate != null) 
        {
            $appointments->where('appointment_date', date('Y-m-d', strtotime($request->fromDate)));
        }

        $appointments = $appointments->where('type', 'appointment')
                            ->where('patient_id', Auth::user()->id)
                            ->orderBy('created_at', 'desc')
                            ->skip($request->offset ?? 0)
                            ->take($request->limit ?? 10)
                            ->get();

        $appointments=AppointmentResource::collection($appointments);

        if (count($appointments) > 0)
        {
            return $this->apiResponse('success', 1, $appointments, 200);
        }
        else
        {
            return $this->apiResponse('success', 1, [], 200);
        }
    }

    public function addAppointment(SaveAppointmentRequest $request){
        $appointment=new Appointment();
        $request->patient_id=Auth::user()->id;
        $appointment->store($request);

        if($appointment instanceof  Appointment){
            try {
                Mail::send('emailTemplates.appointmentDetails', [
                    'record' => $appointment
                ], function ($message) use ($appointment){
                    $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                            ->to($appointment->patient->email, ($appointment->patient->first_name ?? '').' '.($appointment->patient->last_name ?? ''))
                            ->subject('Appointment Details');
                });
            } catch (Exception $e) {
                return $this->apSuccessResponse('Appointment Saved');
            }

            return $this->apiSuccessResponse('Appointment Saved');
        }
        return $this->apiErrorResponse('Failed To Add Appointment');
    }

    function rescheduleAppointment(Request $request){
        $appointment = Appointment::find($request->id);

        $response = $this->apiErrorResponse('Invalid appointment id');

        if($appointment){
            if($request->insurance_id != null){
                $insurance = InsurancesPlans::find($request->insurance_id);

                if(!$insurance){
                    $response = $this->apiErrorResponse('Invalid insurance id');

                    return $response;
                }
            }

            $appointment->family_member_id = $request->family_member_id;
            $appointment->insurance_id = $request->insurance_id;
            $appointment->appointment_date = $request->appointment_date;
            $appointment->appointment_time = date('H:i:s', strtotime($request->appointment_time));
            $appointment->on_waiting = $request->on_waiting;
            $appointment->extraDetails = $request->extraDetails;

            $response = $this->apiSuccessResponse('Appointment reschedule');

            if(!$appointment->save()){
                $response = $this->apiErrorResponse('Something wen\'t wrong !!');
            }

            try {
                Mail::send('emailTemplates.appointmentDetails', [
                    'record' => $appointment
                ], function ($message) use ($appointment){
                    $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                            ->to($appointment->patient->email, ($appointment->patient->first_name ?? '').' '.($appointment->patient->last_name ?? ''))
                            ->subject('Appointment Details');
                });
            } catch (Exception $e) {
                return $response;
            }
        }

        return $response;
    }

    public function addCallBackRequest(SaveAppointmentCallRequest $request)
    {
        $appointment = new Appointment();

        $request->patient_id = Auth::user()->id;
        $request->type = 'call_back_request';

        $appointment->store($request);

        if ($appointment instanceof Appointment)
        {
            try 
            {
                Mail::send('emailTemplates.appointmentCallRequestDetails', 
                    [
                        'record' => $appointment
                    ], 
                    function ($message) use ($appointment)
                    {
                        $message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'))
                        ->to($appointment->patient->email, ($appointment->patient->first_name ?? '').' '.($appointment->patient->last_name ?? ''))
                        ->subject('Appointment Details');
                    }
                );
            } 
            catch (Exception $e) 
            {
                return $this->apSuccessResponse('Appointment Saved');
            }

            return $this->apiSuccessResponse('Appointment Saved');
        }

        return $this->apiErrorResponse('Failed To Add Appointment');
    }

    public function viewCallBackRequest(Request $request)
    {
        $appointments = Appointment::with(
            'doctor', 
            'hospital', 
            'familyMember', 
            'insurance'
        )
        ->where('type', 'call_back_request')
        ->where('patient_id', Auth::user()->id)
        ->orderBy('created_at', 'desc')
        ->skip($request->offset ?? 0)
        ->take($request->limit ?? 10)
        ->get();

        $appointments = AppointmentCallRequest::collection($appointments);

        if (count($appointments) > 0)
        {
            return $this->apiResponse('success', 1, $appointments, 200);
        }
        else
        {
            return $this->apiResponse('success', 1, [], 200);
        }
    }
}