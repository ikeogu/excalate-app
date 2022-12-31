<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            //
            'data.attributes.otp' => ['required', 'integer', 'digits:6', 'exists:email_verifications,otp'],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */

    public function messages()
    {
        return [
            'data.attributes.otp.required' => 'OTP is required',
            'data.attributes.otp.integer' => 'OTP must be an integer',
            'data.attributes.otp.digits' => 'OTP must be 6 digits',
            'data.attributes.otp.exists' => 'OTP does not exist',
        ];
    }
}