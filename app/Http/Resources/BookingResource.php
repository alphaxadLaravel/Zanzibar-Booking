<?php

namespace App\Http\Resources;

use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hashid' => HashidsHelper::encode($this->id),
            'booking_code' => $this->booking_code,
            'fullname' => $this->fullname,
            'email' => $this->email,
            'phone' => $this->phone,
            'country' => $this->country,
            'total_amount' => (float) $this->total_amount,
            'payment_method' => $this->payment_method,
            'payment_status' => (bool) ($this->payment_status ?? false),
            'status' => $this->status,
            'additional_notes' => $this->additional_notes,
            'booking_items' => $this->booking_items,
            'created_at' => optional($this->created_at)?->toIso8601String(),
        ];
    }
}
