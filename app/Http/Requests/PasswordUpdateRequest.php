<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class PasswordUpdateRequest extends FormRequest
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

            'data.attributes.password' => ['required', 'confirmed', 'min:8',
                Password::min(8)->mixedCase()->symbols()->numbers()->uncompromised()
            ],
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

            'data.attributes.password.required' => 'Password is required',
            'data.attributes.password.confirmed' => 'Password confirmation does not match',
            'data.attributes.password.min' => 'Password must be at least 8 characters',
            'data.attributes.password.symbols' => 'Password must contain at least one symbol',
            'data.attributes.password.numbers' => 'Password must contain at least one number',
            'data.attributes.password.uncompromised' => 'Password has been compromised and cannot be used',
           
        ];
    }
}
