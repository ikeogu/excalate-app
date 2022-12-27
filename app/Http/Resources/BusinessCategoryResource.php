<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\BusinessCategory */
class BusinessCategoryResource extends JsonResource
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
            'type' => 'business_category',
            'id' => $this->id,
            'attributes' =>[
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description
            ]

        ];
    }
}