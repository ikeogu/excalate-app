<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\UserProximityPlan */
class UserProximityPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'type' => 'user_proximity_plan',
            'id' => $this->id,
            'attributes' => [
                'id' => $this->id,
                'user_id' => $this->user_id,
                'proximity_plan_id' => $this->proximity_plan_id,
                //convert status to boolean
                'status' => $this->status == 1 ? true : false,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => strval($this->user_id),
                    ]
                ],
                'proximity_plan' => [
                    'data' => [
                        'type' => 'proximity_plan',
                        'id' => $this->proximity_plan_id,
                    ]
                ],
            ],

        ];
    }
}