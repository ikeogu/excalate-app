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

            'id' => $this->id,
            'user_id' => $this->user_id,
            'proximity_plan_id' => $this->proximity_plan_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'proximity_plan' => new ProximityPlanResource($this->whenLoaded('proximityPlan')),
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}