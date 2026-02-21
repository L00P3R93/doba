<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'profile' => $this->profile ? [
                'id' => $this->profile->id,
                'bio' => $this->profile->bio,
                'mpesa_phone' => $this->profile->mpesa_phone,
                'tier' => $this->profile->tier,
                'created_at' => $this->profile->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->profile->updated_at->format('Y-m-d H:i:s'),
            ] : null,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
