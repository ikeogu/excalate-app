<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
/** @mixin \App\Models\Contact */
class EmergencyContact extends JsonResource
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
            'type' => 'emergency_contact',
            'id' => $this->id,
            'attributes' => [
                'id' => $this->id,
                'name' => $this->name,
                'phone' => $this->phone,
                'email' => $this->email,
                'relationship' => $this->relationship,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
                'user' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id,
                    ]
                ],
            ],
        ];
    }
}