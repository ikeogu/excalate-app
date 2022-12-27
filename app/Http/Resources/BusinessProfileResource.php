<?php

namespace App\Http\Resources;

use App\Models\User;
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
        /** @var User $user */
        $user = User::findOrFail($this->user_id);
        return [
            'type' => 'busness_profile',
            'attributes'=>[
                'id' => $this->id,
                'name' => $this->name,
                'location' => $this->location,
                'lat' => $this->lat,
                'long' => $this->long,
                'qualifications' => $this->qualifications,
                'min_charge' => $this->min_charge,
                'service_type' => $this->service_type,
            ],

            'relationships' => [
                'users' =>[

                    'data' =>[
                        'type' => 'user',
                        'id' => $user->id,
                    ]
                ],
                'business_category' => [
                    'data' => [
                        'type' => 'business_category',
                        'id' => $this->business_category_id,
                    ]
                ],

            ],

        ];
    }
}
