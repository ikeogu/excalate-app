<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserProximityPlanRequest extends FormRequest
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
            'data.attributes.proximity_plan_id' => ['required', 'integer', 'exists:proximity_plans,id'],
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
            'data.attributes.proximity_plan_id.required' => 'Proximity plan is required',
            'data.attributes.proximity_plan_id.integer' => 'Proximity plan must be an integer',
            'data.attributes.proximity_plan_id.exists' => 'Proximity plan does not exist',
        ];
    }
}
