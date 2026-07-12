<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingItemResource;
use App\Models\BookingItem;
use App\Models\Deal;
use App\Models\Room;
use App\Services\GroupPackageCapacityService;
use App\Services\RoomPriceService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $items = BookingItem::where('user_id', $request->user()->id)
            ->where('status', 'cart')
            ->with(['deal.category', 'deal.photos', 'deal.tours', 'room'])
            ->latest()
            ->get();

        $requiresOnlinePayment = $items->contains(
            fn ($item) => $item->deal?->tours?->is_group_package
        );

        return response()->json([
            'data' => BookingItemResource::collection($items),
            'total_amount' => (float) $items->sum('total_price'),
            'items_count' => $items->count(),
            'requires_online_payment' => $requiresOnlinePayment,
        ]);
    }

    public function destroy(Request $request, string $itemId)
    {
        $item = BookingItem::where('user_id', $request->user()->id)
            ->where('status', 'cart')
            ->where('id', $itemId)
            ->firstOrFail();

        $item->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    public function clear(Request $request)
    {
        BookingItem::where('user_id', $request->user()->id)
            ->where('status', 'cart')
            ->delete();

        return response()->json(['message' => 'Cart cleared']);
    }

    public function bookRoom(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'deal_id' => 'required|exists:deals,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'number_of_rooms' => 'required|integer|min:1',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'action' => 'required|in:book_now,add_cart',
        ]);

        DB::beginTransaction();
        try {
            $room = Room::with('priceIntervals')->findOrFail($validated['room_id']);
            $deal = Deal::findOrFail($validated['deal_id']);

            if ($room->deal_id !== $deal->id) {
                DB::rollBack();
                return response()->json(['message' => 'Room does not belong to this hotel.'], 422);
            }

            $totalPrice = (new RoomPriceService())->calculateTotalPrice(
                $room,
                $validated['check_in'],
                $validated['check_out'],
                $validated['number_of_rooms'],
                $validated['adults'],
                $validated['children'] ?? 0
            );

            $bookingItem = BookingItem::create([
                'user_id' => $request->user()->id,
                'deal_id' => $deal->id,
                'room_id' => $room->id,
                'number_rooms' => $validated['number_of_rooms'],
                'type' => 'hotel',
                'check_in' => $validated['check_in'],
                'check_out' => $validated['check_out'],
                'total_price' => $totalPrice,
                'adults' => $validated['adults'],
                'children' => $validated['children'] ?? 0,
                'status' => 'cart',
            ]);

            DB::commit();

            $bookingItem->load(['deal.category', 'deal.photos', 'room']);

            return response()->json([
                'message' => $validated['action'] === 'book_now'
                    ? 'Room ready for checkout'
                    : 'Room added to cart',
                'action' => $validated['action'],
                'item' => new BookingItemResource($bookingItem),
                'selected_items' => [(string) $bookingItem->id],
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API bookRoom failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Could not book room. Please try again.'], 500);
        }
    }

    public function bookDeal(Request $request)
    {
        $type = $request->input('type');
        $validated = $this->validateDealBooking($request, $type);
        $validated['children'] = (int) ($validated['children'] ?? 0);
        $validated['action'] = $request->validate(['action' => 'required|in:book_now,add_cart'])['action'];

        DB::beginTransaction();
        try {
            $deal = Deal::with(['tours', 'car'])->findOrFail($validated['deal_id']);

            if (in_array($validated['type'], ['activity', 'package']) && !$deal->tours) {
                throw new \RuntimeException('Activity/package pricing information is missing.');
            }

            if ($validated['type'] === 'package' && $deal->tours?->is_group_package) {
                $participants = ($validated['adults'] ?? 1) + ($validated['children'] ?? 0);
                $check = app(GroupPackageCapacityService::class)->canBook($deal->tours, $participants);
                if (!$check['allowed']) {
                    throw new \RuntimeException($check['message']);
                }
                if ($deal->tours->group_departure_date) {
                    $validated['check_in'] = $deal->tours->group_departure_date->format('Y-m-d');
                }
            }

            $totalPrice = $this->calculateDealPrice($deal, $validated);

            $bookingItem = BookingItem::create([
                'user_id' => $request->user()->id,
                'deal_id' => $deal->id,
                'room_id' => $validated['room_id'] ?? null,
                'type' => $validated['type'],
                'check_in' => $validated['check_in'] ?? $validated['pickup_date'] ?? null,
                'check_out' => $validated['check_out'] ?? $validated['return_date'] ?? null,
                'total_price' => $totalPrice,
                'adults' => $validated['adults'] ?? 1,
                'children' => $validated['children'] ?? 0,
                'number_rooms' => in_array($validated['type'], ['hotel', 'apartment'])
                    ? ($validated['number_rooms'] ?? 1)
                    : null,
                'status' => 'cart',
            ]);

            DB::commit();
            $bookingItem->load(['deal.category', 'deal.photos', 'deal.tours', 'room']);

            return response()->json([
                'message' => $validated['action'] === 'book_now'
                    ? 'Item ready for checkout'
                    : 'Item added to cart',
                'action' => $validated['action'],
                'item' => new BookingItemResource($bookingItem),
                'selected_items' => [(string) $bookingItem->id],
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API bookDeal failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    private function validateDealBooking(Request $request, ?string $type): array
    {
        return match ($type) {
            'package', 'activity' => $request->validate([
                'deal_id' => 'required|exists:deals,id',
                'type' => 'required|in:package,activity',
                'check_in' => 'required|date|after_or_equal:today',
                'adults' => 'required|integer|min:1',
                'children' => 'nullable|integer|min:0',
            ]),
            'apartment' => $request->validate([
                'deal_id' => 'required|exists:deals,id',
                'type' => 'required|in:apartment',
                'check_in' => 'required|date|after_or_equal:today',
                'check_out' => 'required|date|after:check_in',
                'adults' => 'required|integer|min:1',
                'children' => 'nullable|integer|min:0',
            ]),
            'car' => $request->validate([
                'deal_id' => 'required|exists:deals,id',
                'type' => 'required|in:car',
                'pickup_date' => 'required|date|after_or_equal:today',
                'return_date' => 'required|date|after:pickup_date',
            ]),
            default => throw \Illuminate\Validation\ValidationException::withMessages([
                'type' => ['Invalid deal type'],
            ]),
        };
    }

    private function calculateDealPrice(Deal $deal, array $validated): float
    {
        return match ($validated['type']) {
            'package', 'activity' => (($validated['adults'] ?? 1) * (float) ($deal->tours->adult_price ?? 0))
                + (($validated['children'] ?? 0) * (float) ($deal->tours->child_price ?? 0)),
            'apartment' => (float) $deal->base_price * max(1, Carbon::parse($validated['check_in'])->diffInDays(Carbon::parse($validated['check_out']))),
            'car' => (float) $deal->base_price * max(1, Carbon::parse($validated['pickup_date'])->diffInDays(Carbon::parse($validated['return_date']))),
            default => (float) $deal->base_price,
        };
    }
}
