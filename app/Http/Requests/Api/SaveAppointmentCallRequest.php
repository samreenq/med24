<?php
namespace App\Http\Requests\Api;
use Illuminate\Foundation\Http\FormRequest;

class SaveAppointmentCallRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'doctor_id' => 'required|exists:doctors,id',
            'hospital_id' => 'required|exists:hospitals,id',
            'appointment_date' => 'required|date|date_format:Y-m-d',
            'appointment_time' => 'required',
            'appointment_date_2' => 'required|date|date_format:Y-m-d',
            'appointment_time_2' => 'required'
        ];
    }
}
