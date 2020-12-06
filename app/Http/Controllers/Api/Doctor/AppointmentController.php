<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Appointment;
use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Api\Appointment\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends ApiController
{

    public function index(Request $request){

        $appointments=Appointment::with('patient','doctor','hospital', 'familyMember')
        ->where('type', 'appointment')
        ->skip($request->offset)
        ->take($request->limit)
        ->where('doctor_id',Auth::user()->id);
        if($request->type=='current'){
            $appointments= $appointments->whereIn('status',['pending','scheduled']);
        }elseif ($request->type=='past'){
            $appointments=  $appointments->whereIn('status',['completed','cancelled','rejected']);
        }
        if (isset($request->fromDate) && $request->fromDate != null) 
        {
            $appointments->where('appointment_date', date('Y-m-d', strtotime($request->fromDate)));
        }
        $appointments=AppointmentResource::collection($appointments->get());
        
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
