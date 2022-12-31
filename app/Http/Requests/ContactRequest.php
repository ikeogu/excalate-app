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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'data.attributes.name.required' => 'Name is required',
            'data.attributes.name.min' => 'Name must be at least 3 characters',
            'data.attributes.name.max' => 'Name must be less than 255 characters',
            'data.attributes.phone_number.required' => 'Phone number is required',
            'data.attributes.phone_number.min' => 'Phone number must be at least 3 characters',
            'data.attributes.phone_number.max' => 'Phone number must be less than 255 characters',
            'data.attributes.relationship.required' => 'Relationship is required',
            'data.attributes.relationship.in' => 'Relationship must be one of the following: spouse, child, parent, friend, other, sibling, work',
            'data.attributes.type.required' => 'Type is required',
            'data.attributes.type.in' => 'Type must be one of the following: emergency, medical, other, mandatory',
        ];
    }
}
