<?php

namespace App\Http\Requests\Api\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email|unique:doctors,email',
            'password'=>'required|min:8',
            'phone'=>'nullable|unique:doctors,phone',
            'medical_licence'=>'required',
            'hospitals'=>'required|array',
            'specialities'=>'required|array',
            'languages'=>'required|array',
            'gender' => 'required|in:male,female,other'
        ];
    }
}
