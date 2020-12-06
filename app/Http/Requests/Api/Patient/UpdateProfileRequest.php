<?php

namespace App\Http\Requests\Api\Patient;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateProfileRequest extends FormRequest
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
            'first_name'=>'nullable',
            'last_name'=>'nullable',
            'email'=>'nullable|email|unique:patients,email,'.auth()->user()->id,
            'gender' => 'nullable|in:male,female',
            'phone_code'=>'nullable',
            'phone_no'=>'nullable|unique:patients,phone_no,'.Auth::user()->id,
            'id_card_front'=>'nullable|mimes:jpeg,jpg,png,gif|max:10000',
            'id_card_back'=>'nullable|mimes:jpeg,jpg,png,gif|max:10000',
            'insurance_card_front'=>'nullable|mimes:jpeg,jpg,png,gif|max:50000',
            'insurance_card_back'=>'nullable|mimes:jpeg,jpg,png,gif|max:50000',
            'image'=>'nullable|mimes:jpeg,jpg,png,gif|max:50000',
        ];
    }
}
