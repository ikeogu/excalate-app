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
            'data.attributes.name' => 'required|string|min:3|max:255',
            'data.attributes.phone_number' => 'required|string|min:3|max:255',
            'data.attributes.relationship' => 'required|in:spouse,child,parent,friend,other,sibling,work',
            'data.attributes.type' => 'required|in:emergency,medical,other,mandatory|string',

        ];
    }
}