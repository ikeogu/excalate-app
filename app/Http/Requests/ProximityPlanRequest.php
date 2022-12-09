<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProximityPlanRequest extends FormRequest
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
            'data.attributes.name' => ['required', 'string', 'max:255'],
            'data.attributes.description' => ['required', 'string', 'max:255'],
            'data.attributes.duration' => ['required', 'integer', 'min:1'],
            'data.attributes.duration_type' => ['required', 'string', 'max:255'],
    
            'data.attributes.min_distance' => ['required', 'integer', 'min:1'],
            'data.attributes.max_distance' => ['required', 'integer', 'min:1'],
            'data.attributes.min_duration' => ['required', 'integer', 'min:1'],
            'data.attributes.max_duration' => ['required', 'integer', 'min:1'],
            'data.attributes.min_price' => ['required', 'integer', 'min:0'],
            'data.attributes.max_price' => ['required', 'integer', 'min:1'],

        ];
    }
}