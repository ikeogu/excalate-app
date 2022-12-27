<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusinessProfileRequest extends FormRequest
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

            'data.attributes.title' => 'required|string',
            'data.attributes.location' => 'required|string',
            'data.attributes.lat' => 'nullable|numeric',
            'data.attributes.long' => 'nullable|numeric',
            'data.attributes.qualifications' => 'required|string',
            'data.attributes.min_charge' => 'required|numeric',
            'data.attributes.service_type' => 'required|string',
            'data.relationships.business_category.category_id' => [
                'required',
                'exists:business_categories,id'],

        ];
    }
}
