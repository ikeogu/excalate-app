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
            'data.attributes.gender' => ['nullable', 'string'],
            'data.attributes.address' => ['nullable', 'string'],
            'data.attributes.city' => ['nullable', 'string'],
            'data.attributes.state' => ['nullable', 'string'],
            'data.attributes.avatar' => ['nullable', 'file','mimes:png,jpg,jpeg'],
            'data.attributes.nin' => ['nullable', 'string', 'max:13'],
            'data.attributes.email' => ['required', 'string', 'email','unique:users,email'],
            'data.attributes.password' => ['required', 'string', 'min:8',
                 Password::min(8)->mixedCase()->symbols()->numbers()->uncompromised()],
            'data.attributes.phone_number' => ['required', 'string', 'max:255', 'unique:users,phone_number'],
            'data.relationships.business_profile.data.name' => ['nullable', 'string', 'max:255'],
            'data.relationships.business_profile.data.category_id' => ['nullable', 'string', 'exists:business_categories,id'],
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

            'data.attributes.email.unique' => 'Email already exists',
            'data.attributes.phone_number.unique' => 'Phone number already exists',
            'data.attributes.password' => 'Password must be at least 8 characters long and must contain at least one uppercase letter, one lowercase letter, one number and one special character',
            'data.attributes.first_name.required' => 'First name is required',
            'data.attributes.last_name.required' => 'Last name is required',
            'data.attributes.phone_number.required' => 'Phone number is required',
            'data.attributes.password.required' => 'Password is required',
            'data.attributes.email.required' => 'Email is required',
            'data.attributes.first_name.max' => 'First name must not be more than 255 characters',
            'data.attributes.last_name.max' => 'Last name must not be more than 255 characters',
            'data.attributes.phone_number.max' => 'Phone number must not be more than 255 characters',
            'data.attributes.password.min' => 'Password must be at least 8 characters long',
            'data.attributes.email.email' => 'A valid email is required',
            'data.attributes.avatar.mimes' => 'Avatar must be a file of type: png, jpg, jpeg',
            'data.attributes.avatar.file' => 'Avatar must be a file of type: png, jpg, jpeg',

        ];
    }
}
