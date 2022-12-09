<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ProximityPlan */
class ProximityPlanResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'duration' => $this->duration,
            'status' => $this->status,
            'min_distance' => $this->min_distance,
            'max_distance' => $this->max_distance,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'duration_type' => $this->duration_type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

        ];

   }
}