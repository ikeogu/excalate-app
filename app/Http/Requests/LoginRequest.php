<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'data.email' => ['required', 'email', 'exists:users,email'],
            'data.password' => ['required']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */

    public function messages(){
        return [
            'data.email.required' => 'Email is required',
            'data.email.email' => 'Email must be a valid email address',
            'data.email.exists' => 'Email does not exist',
            'data.password.required' => 'Password is required',
        ];
    }
}
