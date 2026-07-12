<?php

namespace App\Http\Resources;

use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hashid' => HashidsHelper::encode($this->id),
            'deal_id' => $this->deal_id,
            'name' => $this->name ?? $this->title,
            'description' => $this->description,
            'capacity' => $this->capacity ?? $this->max_guests,
            'base_price' => (float) ($this->base_price ?? $this->price ?? 0),
            'amenities' => $this->amenities ?? null,
            'photos' => $this->whenLoaded('photos', fn () => $this->photos->map(fn ($p) => [
                'id' => $p->id,
                'url' => asset('storage/' . ltrim($p->photo ?? $p->path ?? '', '/')),
            ])->values()),
        ];
    }
}
