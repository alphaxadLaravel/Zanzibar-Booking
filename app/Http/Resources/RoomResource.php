<?php

namespace App\Http\Resources;

use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $photos = [];
        if ($this->relationLoaded('photos')) {
            foreach ($this->photos as $p) {
                $url = $this->mediaUrl($p->photo ?? $p->path ?? null);
                if ($url) {
                    $photos[] = ['id' => $p->id, 'url' => $url];
                }
            }
        }

        $cover = $this->mediaUrl($this->cover_photo)
            ?: ($photos[0]['url'] ?? null);

        $minPrice = null;
        try {
            if (method_exists($this->resource, 'getMinPrice')) {
                $minPrice = (float) $this->resource->getMinPrice();
            }
        } catch (\Throwable) {
            $minPrice = null;
        }

        return [
            'id' => $this->id,
            'hashid' => HashidsHelper::encode((int) $this->id),
            'deal_id' => $this->deal_id,
            'name' => $this->title ?? $this->name,
            'title' => $this->title ?? $this->name,
            'description' => $this->plainText($this->description),
            'people' => $this->people,
            'beds' => $this->beds,
            'capacity' => $this->people ?? $this->capacity,
            'number_of_rooms' => $this->number_of_rooms,
            'price' => (float) ($this->price ?? 0),
            'base_price' => (float) ($minPrice ?? $this->price ?? 0),
            'price_type' => $this->price_type,
            'price_unit' => method_exists($this->resource, 'getPriceUnitLabel')
                ? $this->resource->getPriceUnitLabel()
                : '/night',
            'cover_photo' => $cover,
            'photos' => $photos,
        ];
    }

    protected function mediaUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    protected function plainText(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        return trim(html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    }
}
