<?php
namespace App\Http\Controllers\Admin\Patients;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use DB;
use Mail;
use App\Appointment;
use App\Doctor;
use App\Patient;
use App\DoctorTiming;
use App\Hospital;
use App\FamilyMember;
use App\InsurancesPlans;

class AppointmentsController extends Controller{
	function __construct(){
        $this->middleware('auth');
    }
	
	function index(){
        $data['menu_active'] = 'patients appointments';

        $data['title'] = 'Appointments';

        $data['doctors'] = Doctor::where('status', 1)
					        ->orderBy('created_at', 'desc')
		                    ->get();

	 	$data['patients'] = Patient::where('status', 1)
						    ->orderBy('created_at', 'desc')
					        ->get();

        $data['appointments'] = Appointment::with([
			'doctor',
			'hospital',
			'patient',
	  	])
        ->where('type', 'appointment')
		->orderBy('created_at', 'desc')
        ->get();

        return view('admin.patient.appointment.index', $data);
    }

    function getDoctorHospitals(Request $request){
    	$rules = [
            'doctorId' => 'required',
        ];

        $this->validate($request, $rules);

    	$doctorHospitals = Doctor::with('hospitals')
								 ->where([
								 	'id' => $request->doctorId,
							 		'status' => 1,
					 		   	 ])
								 ->first();

	 	$data = [];
	 	$message = "Something wen't wrong";
	 	$status = 0;

	 	if(count($doctorHospitals->hospitals) > 0){
	 		$data = $doctorHospitals->hospitals;
	 		$message = "Success";
	 		$status = 1;
	 	}

	 	return response()->json([
			'message' => $message,
			'data' => $data,
			'status' => $status,
		], 200);
    }

    function getDoctorInsurances(Request $request){
        $rules = [
            'doctorId' => 'required',
        ];

        $this->validate($request, $rules);

        $doctorInsurances = Doctor::with('doctors_insurances')
                                 ->where([
                                    'id' => $request->doctorId,
                                    'status' => 1,
                                 ])
                                 ->first();

        $data = [];
        $message = "Something wen't wrong";
        $status = 0;

        if(count($doctorInsurances->doctors_insurances) > 0){
            $data = $doctorInsurances->doctors_insurances;
            $message = "Success";
            $status = 1;
        }

        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $status,
        ], 200);
    }

    function getPatientFamilyMembers(Request $request){
    	$rules = [
            'patientId' => 'required',
        ];

        $this->validate($request, $rules);

    	$familyMembers = Patient::with('patientFamilyMembers')
								 ->where([
								 	'id' => $request->patientId,
							 		'status' => 1,
					 		   	 ])
								 ->first();

	 	$data = [];
	 	$message = "Something wen't wrong";
	 	$status = 0;

	 	if(count($familyMembers->patientFamilyMembers) > 0){
            $filteredFamilyMembers = [];

            foreach($familyMembers->patientFamilyMembers as $familyMember){
                $filteredFamilyMembers[] = [
                    'id' => $familyMember->id,
                    'name' => $familyMember->first_name.' '.$familyMember->last_name,
                ];
            }

	 		$data = $filteredFamilyMembers;
	 		$message = "Success";
	 		$status = 1;
	 	}

	 	return response()->json([
			'message' => $message,
			'data' => $data,
			'status' => $status,
		], 200);
    }

    function getAvailableDays(Request $request){
        $rules = [
            'doctorId' => 'required',
            'hospitalId' => 'required',
        ];

        $this->validate($request, $rules);

        $totalDays = DoctorTiming::select('day')
        					->where([
        						'doctor_id' => $request->doctorId,
        						'hospital_id' => $request->hospitalId,
        					])
            				->distinct('day')
            				->get()
            				->pluck('day');

        $cancelledDates = DB::table('cancelled_appointment_dates')
				            ->select('date')
				            ->where([
				            	'doctor_id' => $request->doctorId,
        						'hospital_id' => $request->hospitalId,
				            ])
				            ->where('date', '>=', date('Y-m-d'))
				            ->get()
				            ->pluck('date');

    	$data = [];
        $message = "No available days found";
        $status = 0;

        if(count($totalDays) > 0){
	        $availableDays = [];
	        $cancelledDays = [];

	        foreach($totalDays as $day){
	        	$availableDays[date('w', strtotime($day))] = date('w', strtotime($day));	
	        }

	        if(count($cancelledDates) > 0){
	        	foreach($cancelledDates as $cancelledDate){
		        	$cancelledDays[date('w', strtotime($cancelledDate))] = date('w', strtotime($cancelledDate));
		        }
	        }	

	        $data = array(
	            'availableDays' => $availableDays,
	            'cancelledDays' => $cancelledDays,
	        );
            $message = "Available days found";
	        $status = 1;
        }

        return response()->json([
			'data' => $data,
            'message' => $message,
			'status' => $status,
		], 200);
    }

    function getAvailableTimeSlots(Request $request){
        $rules = [
            'doctorId' => 'required',
            'hospitalId' => 'required',
            'appointmentDate' => 'required|date_format:d-m-Y|after_or_equal:'.date('d-m-Y'),
        ];

        $this->validate($request, $rules);

        $doctor = Doctor::find($request->doctorId);

        if(!$doctor){
        	return response()->json([
				'data' => [],
				'message' => 'Invalid doctor id.',
				'status' => 0,
			], 200);
        }

        $hospital = Hospital::find($request->hospitalId);

        if(!$hospital){
        	return response()->json([
				'data' => [],
				'message' => 'Invalid hospital id.',
				'status' => 0,
			], 200);
        }

        $availableTimeSlots = $doctor->timeSlots(date('Y-m-d', strtotime($request->appointmentDate)), $request->hospitalId);

        return response()->json([
			'data' => $availableTimeSlots,
			'message' => 'Available time slots',
			'status' => 1,
		], 200);
    }

    function saveAppointment(Request $request){
        $rules = [
            'doctor' => 'required',
            'hospital' => 'required',
            'patient' => 'required',
            'familyMember' => 'required',
            'appointmentDate' => 'required|date_format:d-m-Y',
            'appointmentTime' => 'required',
        ];

        $this->validate($request, $rules);

        $doctor = Doctor::find($request->doctor);

        if(!$doctor){
        	return response()->json([
				'data' => [],
				'message' => 'Invalid doctor id.',
				'status' => 0,
			], 200);
        }

        $hospital = Hospital::find($request->hospital);

        if(!$hospital){
        	return response()->json([
				'data' => [],
				'message' => 'Invalid hospital id.',
				'status' => 0,
			], 200);
        }

        $patient = Patient::find($request->patient);

        if(!$patient){
        	return response()->json([
				'data' => [],
				'message' => 'Invalid patient id.',
				'status' => 0,
			], 200);
        }

        if($request->insurance != 0){
            $insurance = InsurancesPlans::find($request->insurance);

            if(!$insurance){
                return response()->json([
                    'data' => [],
                    'message' => 'Invalid insurance id.',
                    'status' => 0,
                ], 200);
            }
        }

        if($request->familyMember != 0){
	        $familyMember = FamilyMember::find($request->familyMember);

	        if(!$patient){
	        	return response()->json([
					'data' => [],
					'message' => 'Invalid family member id.',
					'status' => 0,
				], 200);
	        }
        }

        if($request->recordId){
            $appointment = Appointment::find($request->recordId);

            if(!$appointment){
                return response()->json([
                    'data' => [],
                    'message' => 'Invalid appointment id.',
                    'status' => 0,
                ], 200);
            }
        }

        $appointment = Appointment::find($request->recordId);

        if(!$appointment){
        	$appointment = new Appointment();
        }

        $appointment->doctor_id = $request->doctor;
        $appointment->hospital_id = $request->hospital;
        $appointment->patient_id = $request->patient;
        $appointment->family_member_id = $request->familyMember;
        $appointment->insurance_id = $request->insurance;
        $appointment->appointment_date = date('Y-m-d', strtotime($request->appointmentDate));
        $appointment->appointment_time = date('H:i', strtotime($request->appointmentTime));
        $appointment->on_waiting = $request->onWaiting ? true : false;
        $appointment->extraDetails = $request->extraDetails;
        $appointment->status = 'pending';

        $message = 'Appoint has been schedule successfully.';
        $status = 1;

        if(!$appointment->save()){
        	$message = 'Something wen\'t wrong !!';
        	$status = 0;
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
            return response()->json([
                'message' => $message,
                'status' => $status,
            ], 200);
        }

        return response()->json([
			'message' => $message,
			'status' => $status,
		], 200);
    }

    function patientAppointments(Request $request, $id){
        $data['menu_active'] = 'patients appointments';

        $data['title'] = 'Appointments';

        $data['doctors'] = Doctor::where('status', 1)
                                 ->orderBy('created_at', 'desc')
                                 ->get();

        $data['patients'] = Patient::where('status', 1)
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        $data['appointments'] = Appointment::with([
                                    'doctor',
                                    'hospital',
                                    'patient',
                                ])
                                ->where('patient_id', $id)
                                ->orderBy('created_at', 'desc')
                                ->get();

        return view('admin.patient.appointment.index', $data);
    }

    function updateStatus(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|in:pending,cancelled,rejected,completed,scheduled'
        ]);

        if($validator->fails()){
            return redirect()->back()->withErrors($validator->errors());
        }        

        $appointment = Appointment::find($request->id);

        if(!$appointment){
            return redirect()->back()->with('error', 'Invalid appointment id');
        }

        $appointment->status = $request->status;

        if(!$appointment->save()){
            return redirect()->back()->with('error', 'Something wen\'t wrong');   
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
            return redirect()->back()->with('success', 'Record has been updated successfully');
        }

        return redirect()->back()->with('success', 'Record has been updated successfully');
    }

    function edit(Request $request){
        $appointment = Appointment::find($request->recordId);

        $status = 1;
        $message = 'Record found';
        $data = $appointment;

        if(!$appointment){
            $status = 0;
            $message = 'Record not found';
            $data = [];
        }

        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $status,
        ], 200);
    }

    public function viewCallbackRequest(Request $request)
    {
        $data['menu_active'] = 'callbackRequest';

        $data['title'] = 'Manage Callback Request';

        $data['records'] = Appointment::with([
            'doctor',
            'hospital',
            'patient',
        ])
        ->where('type', 'call_back_request')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('admin.patient.appointment.viewCallbackRequest', $data);
    }
}