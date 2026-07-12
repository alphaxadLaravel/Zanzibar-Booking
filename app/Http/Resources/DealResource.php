<?php

namespace App\Http\Resources;

use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DealResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $cover = $this->cover_photo;
        if (!$cover && $this->relationLoaded('photos')) {
            $cover = optional($this->photos->first())->photo;
        } elseif (!$cover) {
            try {
                $cover = optional($this->photos()->first())->photo;
            } catch (\Throwable) {
                $cover = null;
            }
        }

        return [
            'id' => $this->id,
            'hashid' => HashidsHelper::encode((int) $this->id),
            'title' => $this->title,
            'type' => $this->type,
            'location' => $this->location,
            'lat' => $this->lat,
            'long' => $this->long,
            'map_location' => $this->map_location,
            'base_price' => (float) ($this->base_price ?? 0),
            'price_unit' => $this->priceUnit(),
            'ratings' => $this->ratings,
            'star_rating' => $this->star_rating,
            'is_featured' => (bool) $this->is_featured,
            'cover_photo' => $this->mediaUrl($cover),
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category?->id,
                'hashid' => $this->category ? HashidsHelper::encode($this->category->id) : null,
                'name' => $this->category?->name,
            ]),
            'description' => $this->description,
            'policies' => $this->policies,
            'video_link' => $this->video_link,
            'photos' => $this->whenLoaded('photos', fn () => $this->photos->map(fn ($p) => [
                'id' => $p->id,
                'url' => $this->mediaUrl($p->photo),
            ])->values()),
            'features' => $this->whenLoaded('features', fn () => $this->features->map(fn ($f) => [
                'id' => $f->id,
                'name' => $f->name,
                'icon' => $f->icon ?? null,
            ])->values()),
            'rooms' => $this->whenLoaded('rooms', fn () => RoomResource::collection($this->rooms)),
            'tour' => $this->whenLoaded('tours', fn () => $this->tours ? [
                'adult_price' => (float) ($this->tours->adult_price ?? 0),
                'child_price' => (float) ($this->tours->child_price ?? 0),
                'duration' => $this->tours->duration ?? null,
                'is_group_package' => (bool) ($this->tours->is_group_package ?? false),
                'group_capacity' => $this->tours->group_capacity ?? null,
                'group_departure_date' => optional($this->tours->group_departure_date)?->format('Y-m-d'),
                'spots_remaining' => $this->tours->spots_remaining ?? null,
            ] : null),
            'car' => $this->whenLoaded('car', fn () => $this->car ? [
                'id' => $this->car->id,
                'transmission' => $this->car->transmission ?? null,
                'seats' => $this->car->seats ?? null,
                'fuel_type' => $this->car->fuel_type ?? null,
            ] : null),
            'itineraries' => $this->whenLoaded('itineraries', fn () => $this->itineraries->map(fn ($i) => [
                'id' => $i->id,
                'day' => $i->day ?? $i->title,
                'title' => $i->title,
                'description' => $i->description,
            ])->values()),
            'includes' => $this->whenLoaded('tourIncludes', fn () => $this->tourIncludes->map(fn ($i) => [
                'id' => $i->id,
                'name' => $i->name ?? $i->include,
                'included' => $i->included ?? true,
            ])->values()),
            'reviews' => $this->whenLoaded('reviews', fn () => $this->reviews->map(fn ($r) => [
                'id' => $r->id,
                'rating' => $r->rating,
                'title' => $r->review_title,
                'comment' => $r->review_content,
                'user_name' => optional($r->user)->full_name ?? 'Guest',
                'created_at' => optional($r->created_at)?->toIso8601String(),
            ])->values()),
        ];
    }

    protected function priceUnit(): string
    {
        return match ($this->type) {
            'hotel', 'apartment' => '/night',
            'car' => '/day',
            'activity', 'package', 'tour' => '/person',
            default => '',
        };
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
}
