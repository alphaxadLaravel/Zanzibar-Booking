<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DealResource;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Room;
use App\Models\System;
use App\Services\RoomPriceService;
use App\Support\HashidsHelper;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function home()
    {
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
        ];

        return response()->json([
            'brand' => [
                'name' => 'Zanzibar Bookings',
                'logo' => asset('logo.png'),
                'primary_color' => '#1C8A7D',
            ],
            'modules' => $modules,
            'featured' => DealResource::collection($featured),
            'by_type' => $byType,
        ]);
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
                'rooms.photos',
                'tours',
                'car',
                'itineraries',
                'tourIncludes',
                'reviews' => fn ($q) => $q->where('status', 'approved')->with('user')->latest()->limit(20),
            ])
            ->findOrFail($dealId);

        return new DealResource($deal);
    }

    public function categories(Request $request)
    {
        $request->validate([
            'type' => 'nullable|in:hotel,apartment,tour,activity,package,car',
        ]);

        $query = Category::query();

        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }

        $categories = $query->orderBy('name')->get()->map(fn ($c) => [
            'id' => $c->id,
            'hashid' => HashidsHelper::encode($c->id),
            'name' => $c->name,
            'type' => $c->type,
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
