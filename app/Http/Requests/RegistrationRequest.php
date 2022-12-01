<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;


class RegistrationRequest extends FormRequest
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
    public function rules() : array
    {
        return [

            'data.attributes.first_name' => ['required', 'string', 'max:255'],
            'data.attributes.last_name' => ['required', 'string', 'max:255'],
            'data.attributes.gender' => ['required', 'string'],
            'data.attributes.address' => ['required', 'string'],
            'data.attributes.city' => ['required', 'string'],
            'data.attributes.state' => ['required', 'string'],
            'data.attributes.avatar' => ['sometimes', 'file','mimes:png,jpg,jpeg'],
            'data.attributes.nin' => ['required', 'string', 'max:13'],
            'data.attributes.email' => ['required', 'string', 'email', 'max:255'],
            'data.attributes.password' => ['required', 'string', 'min:8', 'confirmed',
                 Password::min(8)->mixedCase()->symbols()],
            'data.attributes.phone_number' => ['required', 'string', 'max:255'],
        ];
    }
}