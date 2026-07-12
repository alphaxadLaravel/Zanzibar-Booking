<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'email_verified' => (bool) $this->email_verified_at,
            'email_verified_at' => optional($this->email_verified_at)?->toIso8601String(),
            'role' => optional($this->role)->name,
        ];
    }
}
