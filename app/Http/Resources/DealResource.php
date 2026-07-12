<?php

namespace App\Http\Resources;

use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class DealResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $cover = $this->cover_photo;
        if (!$cover && $this->relationLoaded('photos')) {
            $cover = optional($this->photos->first())->photo;
        }

        $includes = [];
        $excludes = [];
        if ($this->relationLoaded('tourIncludes')) {
            foreach ($this->tourIncludes as $row) {
                $name = $row->feature?->name ?? $row->name ?? null;
                if (!$name) {
                    continue;
                }
                $item = [
                    'id' => $row->id,
                    'name' => $name,
                    'icon' => $row->feature?->icon ?? null,
                    'type' => $row->type,
                ];
                if ($row->type === 'exclude') {
                    $excludes[] = $item;
                } else {
                    $includes[] = $item;
                }
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
            'video_link' => $this->video_link,
            'category' => $this->whenLoaded('category', fn () => [
                'id' => $this->category?->id,
                'hashid' => $this->category ? HashidsHelper::encode($this->category->id) : null,
                'name' => $this->category?->name ?? $this->category?->category,
            ]),
            'description' => $this->plainText($this->description),
            'description_html' => $this->description,
            'policies' => $this->plainText($this->policies),
            'policies_html' => $this->policies,
            'photos' => $this->whenLoaded('photos', fn () => $this->photos->map(fn ($p) => [
                'id' => $p->id,
                'url' => $this->mediaUrl($p->photo),
            ])->filter(fn ($p) => !empty($p['url']))->values()),
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
                'max_people' => $this->tours->max_people ?? null,
                'is_group_package' => (bool) ($this->tours->is_group_package ?? false),
                'group_capacity' => $this->tours->group_capacity ?? null,
                'group_max_capacity' => $this->tours->group_max_capacity ?? $this->tours->group_capacity ?? null,
                'group_departure_date' => optional($this->tours->group_departure_date)?->format('Y-m-d'),
                'spots_remaining' => $this->tours->spots_remaining ?? null,
            ] : null),
            'car' => $this->whenLoaded('car', fn () => $this->car ? [
                'id' => $this->car->id,
                'transmission' => $this->car->transmission ?? null,
                'seats' => $this->car->seats ?? $this->car->people ?? null,
                'fuel_type' => $this->car->fuel_type ?? null,
            ] : null),
            'itineraries' => $this->whenLoaded('itineraries', fn () => $this->itineraries->map(fn ($i) => [
                'id' => $i->id,
                'day' => $i->day ?? null,
                'title' => $this->plainText($i->title),
                'description' => $this->plainText($i->description),
                'description_html' => $i->description,
                'location' => $i->location ?? null,
                'time' => $i->time ?? null,
            ])->values()),
            'includes' => $this->when($this->relationLoaded('tourIncludes'), $includes),
            'excludes' => $this->when($this->relationLoaded('tourIncludes'), $excludes),
            'nearby_locations' => $this->whenLoaded('nearbyLocations', fn () => $this->nearbyLocations->map(fn ($n) => [
                'id' => $n->id,
                'title' => $n->title,
                'category' => $n->category,
                'distance' => $n->formatted_distance ?? $n->distance,
            ])->values()),
            'reviews' => $this->whenLoaded('reviews', fn () => $this->reviews->map(function ($r) {
                $user = $r->user;
                $name = $user
                    ? trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''))
                    : '';
                if ($name === '') {
                    $name = $user?->full_name ?: 'Guest';
                }

                return [
                    'id' => $r->id,
                    'rating' => $r->rating,
                    'title' => $this->plainText($r->review_title),
                    'comment' => $this->plainText($r->review_content),
                    'user_name' => $name,
                    'created_at' => optional($r->created_at)?->toIso8601String(),
                ];
            })->values()),
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

    protected function plainText(?string $value): ?string
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $text = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }
}
