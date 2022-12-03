<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'data.attributes.first_name' => 'required|string',
            'data.attributes.last_name' => 'required|string',
            'data.attributes.email' => ['required', 'email'],
            'data.attributes.phone_number' => 'required|string',
            'data.attributes.relationship' => 'required|string',

        ];
    }
}
