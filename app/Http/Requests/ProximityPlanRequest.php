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

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */

    public function messages()
    {
        return [
            'data.attributes.name.required' => 'Name is required',
            'data.attributes.name.max' => 'Name must be less than 255 characters',
            'data.attributes.description.required' => 'Description is required',
            'data.attributes.description.max' => 'Description must be less than 255 characters',
            'data.attributes.duration.required' => 'Duration is required',
            'data.attributes.duration.integer' => 'Duration must be an integer',
            'data.attributes.duration.min' => 'Duration must be at least 1',
            'data.attributes.duration_type.required' => 'Duration type is required',
            'data.attributes.duration_type.max' => 'Duration type must be less than 255 characters',

            'data.attributes.min_distance.required' => 'Minimum distance is required',
            'data.attributes.min_distance.integer' => 'Minimum distance must be an integer',
            'data.attributes.min_distance.min' => 'Minimum distance must be at least 1',
            'data.attributes.max_distance.required' => 'Maximum distance is required',
            'data.attributes.max_distance.integer' => 'Maximum distance must be an integer',
            'data.attributes.max_distance.min' => 'Maximum distance must be at least 1',
            'data.attributes.min_duration.required' => 'Minimum duration is required',
            'data.attributes.min_duration.integer' => 'Minimum duration must be an integer',
            'data.attributes.min_duration.min' => 'Minimum duration must be at least 1',
            'data.attributes.max_duration.required' => 'Maximum duration is required',
            'data.attributes.max_duration.integer' => 'Maximum duration must be an integer',
            'data.attributes.max_duration.min' => 'Maximum duration must be at least 1',
            'data.attributes.min_price.required' => 'Minimum price is required',
            'data.attributes.min_price.integer' => 'Minimum price must be an integer',
            'data.attributes.min_price.min' => 'Minimum price must be at least 0',
            'data.attributes.max_price.required' => 'Maximum price is required',
            'data.attributes.max_price.integer' => 'Maximum price must be an integer',
            'data.attributes.max_price.min' => 'Maximum price must be at least 1',
        ];
    }
}
