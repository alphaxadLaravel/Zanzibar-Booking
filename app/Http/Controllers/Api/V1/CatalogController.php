<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DealResource;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Near;
use App\Models\Room;
use App\Models\System;
use App\Services\RoomPriceService;
use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CatalogController extends Controller
{
    public function home()
    {
        try {
            $featured = Deal::query()
                ->active()
                ->featured()
                ->with(['category', 'photos'])
                ->latest()
                ->take(8)
                ->get();

            $byType = [];
            foreach (['hotel', 'apartment', 'activity', 'package', 'car', 'tour'] as $type) {
                $byType[$type] = DealResource::collection(
                    Deal::query()
                        ->active()
                        ->where('type', $type)
                        ->with(['category', 'photos'])
                        ->latest()
                        ->take(6)
                        ->get()
                );
            }

            $modules = [
                ['key' => 'hotel', 'label' => 'Hotels', 'route' => 'hotels'],
                ['key' => 'apartment', 'label' => 'Apartments', 'route' => 'apartments'],
                ['key' => 'tour', 'label' => 'Tours', 'route' => 'tours'],
                ['key' => 'activity', 'label' => 'Activities', 'route' => 'activities'],
                ['key' => 'package', 'label' => 'Packages', 'route' => 'packages'],
                ['key' => 'car', 'label' => 'Cars', 'route' => 'cars'],
                ['key' => 'flight', 'label' => 'Flights', 'route' => 'flights'],
                ['key' => 'blog', 'label' => 'Blog', 'route' => 'blog'],
            ];

            $fallbackImage = asset('images/banner.jpg');
            $mapCategory = function (Category $c) use ($fallbackImage) {
                $imagePath = $c->image ? ltrim((string) $c->image, '/') : null;

                return [
                    'id' => $c->id,
                    'hashid' => HashidsHelper::encode((int) $c->id),
                    'name' => $c->category ?? $c->name ?? 'Category',
                    'type' => $c->type === 'resort' ? 'hotel' : $c->type,
                    'image' => $imagePath ? asset('storage/' . $imagePath) : $fallbackImage,
                ];
            };

            // Mirror website index: Hotel Types / Package & Activity Types (same order & limits)
            $propertyTypeItems = Category::query()
                ->whereIn('type', ['hotel', 'apartment', 'package', 'activity', 'resort'])
                ->orderBy('id')
                ->limit(6)
                ->get()
                ->map($mapCategory)
                ->values();

            $tourTypeItems = Category::query()
                ->where('type', 'tour')
                ->orderBy('id')
                ->limit(6)
                ->get();
            if ($tourTypeItems->isEmpty()) {
                $tourTypeItems = Category::query()
                    ->where('type', 'activity')
                    ->orderBy('id')
                    ->limit(6)
                    ->get();
            }
            $tourTypeItems = $tourTypeItems->map($mapCategory)->values();

            $types = [
                [
                    'key' => 'property',
                    'title' => 'Hotel Types',
                    'subtitle' => 'Stays, packages, and activities',
                    'items' => $propertyTypeItems,
                    'deals_title' => 'Hotels',
                    'deals_type' => 'hotel',
                    'deals' => DealResource::collection(
                        Deal::query()
                            ->active()
                            ->where('type', 'hotel')
                            ->with(['category', 'photos'])
                            ->latest()
                            ->take(8)
                            ->get()
                    )->resolve(),
                ],
                [
                    'key' => 'tour',
                    'title' => 'Package & Activity Types',
                    'subtitle' => 'Explore island experiences',
                    'items' => $tourTypeItems,
                    'deals_title' => 'Activities',
                    'deals_type' => 'activity',
                    'deals' => DealResource::collection(
                        Deal::query()
                            ->active()
                            ->where('type', 'activity')
                            ->with(['category', 'photos'])
                            ->latest()
                            ->take(8)
                            ->get()
                    )->resolve(),
                ],
            ];

            $blogs = Blog::query()
                ->where('status', 1)
                ->with(['user', 'category'])
                ->latest()
                ->take(6)
                ->get()
                ->map(function (Blog $b) {
                    $coverPath = $b->cover_photo ?? null;
                    $excerptSource = $b->preview_text ?: ($b->description ?? '');
                    $author = $b->user
                        ? trim(($b->user->firstname ?? '') . ' ' . ($b->user->lastname ?? ''))
                        : 'Zanzibar Bookings';

                    return [
                        'id' => $b->id,
                        'hashid' => HashidsHelper::encode((int) $b->id),
                        'title' => $b->title,
                        'excerpt' => trim(html_entity_decode(
                            strip_tags(Str::limit(strip_tags((string) $excerptSource), 140)),
                            ENT_QUOTES | ENT_HTML5,
                            'UTF-8'
                        )),
                        'cover' => $coverPath ? asset('storage/' . ltrim($coverPath, '/')) : null,
                        'author' => $author !== '' ? $author : 'Zanzibar Bookings',
                        'category' => $b->category?->category ?? $b->category?->name,
                        'created_at' => optional($b->created_at)?->toIso8601String(),
                    ];
                });

            return response()->json([
                'brand' => [
                    'name' => 'Zanzibar Bookings',
                    'logo' => asset('logo.png'),
                    'primary_color' => '#1C8A7D',
                ],
                'modules' => $modules,
                'featured' => DealResource::collection($featured),
                'by_type' => $byType,
                'types' => $types,
                'blogs' => $blogs,
                'hero' => [
                    'video' => asset('images/zanzibar.mp4'),
                    'poster' => asset('images/banner.jpg'),
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('API home failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'message' => 'Home API failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error',
            ], 500);
        }
    }

    public function deals(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:hotel,apartment,tour,activity,package,car',
            'location' => 'nullable|string|max:255',
            'category' => 'nullable|string',
            'name' => 'nullable|string|max:255',
            'sort' => 'nullable|in:new,price_asc,price_desc,name_a_z,name_z_a',
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $query = Deal::query()->active()->with(['category', 'photos']);

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        if ($location = $request->query('location')) {
            $query->where('location', 'like', '%' . $location . '%');
        }

        if ($name = $request->query('name')) {
            $query->where('title', 'like', '%' . $name . '%');
        }

        if ($category = $request->query('category')) {
            $categoryId = HashidsHelper::resolveId($category);
            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }
        }

        match ($request->query('sort', 'new')) {
            'price_asc' => $query->orderBy('base_price', 'asc'),
            'price_desc' => $query->orderBy('base_price', 'desc'),
            'name_a_z' => $query->orderBy('title', 'asc'),
            'name_z_a' => $query->orderBy('title', 'desc'),
            default => $query->latest(),
        };

        $perPage = (int) $request->query('per_page', 12);
        $paginator = $query->paginate($perPage);

        return DealResource::collection($paginator)->additional([
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    public function showDeal(string $id)
    {
        $dealId = HashidsHelper::resolveId($id);
        if (!$dealId) {
            return response()->json(['message' => 'Deal not found'], 404);
        }

        $deal = Deal::query()
            ->active()
            ->with([
                'category',
                'photos',
                'features',
                'rooms' => fn ($q) => $q->where('status', 1)->with('photos'),
                'tours',
                'car',
                'itineraries',
                'tourIncludes.feature',
                'nearbyLocations',
                'reviews' => fn ($q) => $q->where('status', 'approved')->with('user')->latest()->limit(20),
            ])
            ->findOrFail($dealId);

        $nearby = $this->resolveNearbyDeals($deal);
        $payload = (new DealResource($deal))->resolve();
        $payload['nearby_deals'] = DealResource::collection($nearby)->resolve();

        return response()->json(['data' => $payload]);
    }

    /**
     * Nearby deals from the `nears` table (same as website), with type fallbacks.
     */
    protected function resolveNearbyDeals(Deal $deal): Collection
    {
        $linked = Near::query()
            ->where('deal_id', $deal->id)
            ->with(['nearDeal' => fn ($q) => $q->active()->with(['category', 'photos'])])
            ->get()
            ->map(fn (Near $near) => $near->nearDeal)
            ->filter()
            ->unique('id')
            ->values();

        if ($linked->isNotEmpty()) {
            return $linked->take(8)->values();
        }

        $fallbackTypes = match ($deal->type) {
            'hotel', 'apartment' => ['hotel', 'apartment'],
            'car' => ['car'],
            'tour', 'activity', 'package' => ['tour', 'activity', 'package'],
            default => [$deal->type],
        };

        return Deal::query()
            ->active()
            ->whereIn('type', $fallbackTypes)
            ->where('id', '!=', $deal->id)
            ->with(['category', 'photos'])
            ->inRandomOrder()
            ->take(6)
            ->get();
    }

    public function dealFilters(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:hotel,apartment,tour,activity,package,car',
        ]);

        $type = $request->query('type');

        // Match website listing filters (AllDealsListing / SearchResults).
        $categoryQuery = Category::query()->orderBy('category');
        if ($type) {
            // Hotel listing also surfaces resort categories used on the home type cards.
            $categoryQuery->whereIn(
                'type',
                $type === 'hotel' ? ['hotel', 'resort'] : [$type]
            );
        } else {
            $categoryQuery->whereIn('type', ['hotel', 'apartment', 'tour', 'car', 'activity', 'package', 'resort']);
        }

        $categories = $categoryQuery->get()->map(fn (Category $c) => [
            'id' => $c->id,
            'hashid' => HashidsHelper::encode((int) $c->id),
            'name' => $c->category ?? 'Category',
            'type' => $c->type === 'resort' ? 'hotel' : $c->type,
            'image' => $c->image ? asset('storage/' . ltrim((string) $c->image, '/')) : asset('images/banner.jpg'),
        ])->values();

        $locationQuery = Deal::query()->active()->whereNotNull('location');
        if ($type) {
            $locationQuery->where('type', $type);
        }

        $locations = $locationQuery
            ->distinct()
            ->orderBy('location')
            ->pluck('location')
            ->filter(fn ($v) => filled($v))
            ->values();

        return response()->json([
            'data' => [
                'categories' => $categories,
                'locations' => $locations,
            ],
        ]);
    }

    public function categories(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:hotel,apartment,tour,activity,package,car,resort',
        ]);

        $query = Category::query()->orderBy('id');

        if ($type = $request->query('type')) {
            if ($type === 'hotel') {
                $query->whereIn('type', ['hotel', 'resort']);
            } else {
                $query->where('type', $type);
            }
        }

        $categories = $query->get()->map(fn ($c) => [
            'id' => $c->id,
            'hashid' => HashidsHelper::encode((int) $c->id),
            'name' => $c->category ?? 'Category',
            'type' => $c->type === 'resort' ? 'hotel' : $c->type,
            'image' => $c->image ? asset('storage/' . ltrim((string) $c->image, '/')) : asset('images/banner.jpg'),
        ]);

        return response()->json(['data' => $categories]);
    }

    public function system()
    {
        $system = System::query()->first();

        return response()->json([
            'brand' => [
                'name' => 'Zanzibar Bookings',
                'logo' => asset('logo.png'),
                'primary_color' => '#1C8A7D',
            ],
            'contact' => [
                'email' => $system?->email,
                'phone' => $system?->phone,
                'secondary_phone' => $system?->secondary_phone,
                'address' => $system?->address,
                'about_text' => $system?->about_text,
            ],
            'social' => [
                'facebook' => $system?->facebook_url,
                'whatsapp' => $system?->whatsapp_url,
                'instagram' => $system?->instagram_url,
                'tripadvisor' => $system?->tripadvisor_url,
                'youtube' => $system?->youtube_url,
            ],
        ]);
    }

    public function roomPrice(Request $request, string $roomId)
    {
        $id = HashidsHelper::resolveId($roomId);
        $room = Room::with('priceIntervals')->find($id);

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        $validated = $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_rooms' => 'nullable|integer|min:1',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
        ]);

        $service = new RoomPriceService();
        $total = $service->calculateTotalPrice(
            $room,
            $validated['check_in'],
            $validated['check_out'],
            (int) ($validated['number_rooms'] ?? 1),
            (int) ($validated['adults'] ?? 1),
            (int) ($validated['children'] ?? 0),
        );

        return response()->json([
            'room_id' => $room->id,
            'total_price' => $total,
        ]);
    }

    public function roomCalendar(Request $request, string $roomId)
    {
        $id = HashidsHelper::resolveId($roomId);
        $room = Room::with('priceIntervals')->find($id);

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        $year = (int) $request->query('year', date('Y'));
        $month = (int) $request->query('month', date('n'));
        $adults = (int) $request->query('adults', 1);
        $children = (int) $request->query('children', 0);

        $service = new RoomPriceService();
        $prices = $service->getPricesForMonth($room, $year, $month, $adults, $children);

        return response()->json([
            'year' => $year,
            'month' => $month,
            'prices' => $prices,
        ]);
    }
}
