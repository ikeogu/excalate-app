<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
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
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'email_verified_at' => $this->email_verified_at,
                'phone' => $this->phone,
                'phone_verified_at' => $this->phone_verified_at,
                'role' => $this->role,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
            'relationships' => [
                'business_profile' => [
                    'data' => [
                        'type' => 'business_profile',
                        'id' => $this->business_profile_id,
                    ]
                ],
                'user_proximity_plan' => [
                    'data' => [
                        'type' => 'user_proximity_plan',
                        'id' => $this->user_proximity_plan_id,
                    ]
                ],
                
            ],
        ];
    }
}