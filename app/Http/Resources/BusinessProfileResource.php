<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\BusinessProfile */
class BusinessProfileResource extends JsonResource
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
            'type' => 'business profile',
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'lat' => $this->lat,
            'long' => $this->long,
            'qualifications' => $this->qualifications,
            'min_charge' => $this->min_charge,
            'service_type' => $this->service_type,
            'user' => new UserResource($this->user),
            'busness_category' => new BusinessCategoryResource($this->business_category),

        ];
    }
}
