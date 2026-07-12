<?php

namespace App\Http\Resources;

use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hashid' => HashidsHelper::encode($this->id),
            'deal_id' => $this->deal_id,
            'room_id' => $this->room_id,
            'type' => $this->type,
            'check_in' => optional($this->check_in)?->format('Y-m-d'),
            'check_out' => optional($this->check_out)?->format('Y-m-d'),
            'number_rooms' => $this->number_rooms,
            'adults' => $this->adults,
            'children' => $this->children,
            'total_price' => (float) $this->total_price,
            'status' => $this->status,
            'deal' => $this->whenLoaded('deal', fn () => new DealResource($this->deal)),
            'room' => $this->whenLoaded('room', fn () => $this->room ? [
                'id' => $this->room->id,
                'name' => $this->room->name ?? $this->room->title,
            ] : null),
        ];
    }
}
