<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Mail\BookingConfirmation;
use App\Mail\BookingNotificationAdmin;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Deal;
use App\Models\Room;
use App\Services\GroupPackageCapacityService;
use App\Support\HashidsHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BookingApiController extends Controller
{
    public function process(Request $request)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'payment_method' => 'required|in:pesapal,pay_offline',
            'special_requests' => 'nullable|string|max:1000',
            'selected_items' => 'required|array|min:1',
            'selected_items.*' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $selectedItemIds = [];
            foreach ($validated['selected_items'] as $raw) {
                $id = HashidsHelper::resolveId($raw);
                if ($id) {
                    $selectedItemIds[] = $id;
                } elseif (is_numeric($raw)) {
                    $selectedItemIds[] = (int) $raw;
                }
            }

            $cartItems = BookingItem::where('user_id', $request->user()->id)
                ->where('status', 'cart')
                ->whereIn('id', $selectedItemIds)
                ->with(['deal.tours', 'room'])
                ->get();

            if ($cartItems->isEmpty()) {
                DB::rollBack();
                return response()->json(['message' => 'No valid cart items found.'], 422);
            }

            $hasGroupPackage = $cartItems->contains(
                fn ($item) => $item->deal?->tours?->is_group_package
            );

            if ($hasGroupPackage && $validated['payment_method'] !== 'pesapal') {
                DB::rollBack();
                return response()->json([
                    'message' => 'Group packages require online payment via Pesapal.',
                ], 422);
            }

            $capacityService = app(GroupPackageCapacityService::class);
            foreach ($cartItems as $cartItem) {
                $tour = $cartItem->deal?->tours;
                if (!$tour || !$tour->is_group_package) {
                    continue;
                }
                $participants = ($cartItem->adults ?? 0) + ($cartItem->children ?? 0);
                $check = $capacityService->canBook($tour, $participants);
                if (!$check['allowed']) {
                    DB::rollBack();
                    return response()->json(['message' => $check['message']], 422);
                }
            }

            $bookedItems = [];
            $totalAmount = 0;
            foreach ($cartItems as $cartItem) {
                $deal = $cartItem->deal;
                $bookedItems[] = [
                    'deal_id' => $cartItem->deal_id,
                    'room_id' => $cartItem->room_id,
                    'number_rooms' => ($cartItem->type === 'hotel') ? $cartItem->number_rooms : null,
                    'type' => $cartItem->type,
                    'title' => $deal?->title ?? ucfirst((string) $cartItem->type),
                    'location' => $deal?->location,
                    'cover_photo' => $deal?->cover_photo
                        ? (str_starts_with((string) $deal->cover_photo, 'http')
                            ? $deal->cover_photo
                            : asset('storage/' . ltrim((string) $deal->cover_photo, '/')))
                        : null,
                    'room_name' => $cartItem->room?->title ?? $cartItem->room?->name,
                    'check_in' => $cartItem->check_in,
                    'check_out' => $cartItem->check_out,
                    'total_price' => $cartItem->total_price,
                    'adults' => $cartItem->adults,
                    'children' => $cartItem->children,
                    'booking_item_id' => $cartItem->id,
                ];
                $totalAmount += $cartItem->total_price;
            }

            $booking = Booking::create([
                'booking_code' => Booking::generateBookingCode(),
                'fullname' => $validated['fullname'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'country' => $validated['country'],
                'user_id' => $request->user()->id,
                'booking_items' => $bookedItems,
                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'additional_notes' => $validated['special_requests'] ?? null,
            ]);

            try {
                Mail::to($booking->email)->send(new BookingConfirmation($booking));
                $adminEmail = env('ADMIN_EMAIL', 'sales-reservations@zanzibarbookings.com');
                Mail::to($adminEmail)->send(new BookingNotificationAdmin($booking));
            } catch (\Throwable $e) {
                Log::error('API booking email failed', ['error' => $e->getMessage()]);
            }

            DB::commit();

            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => new BookingResource($booking),
                'next' => $validated['payment_method'] === 'pesapal' ? 'pesapal' : 'offline',
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API process booking failed', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Could not process booking.'], 500);
        }
    }

    public function index(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return BookingResource::collection($bookings);
    }

    public function show(Request $request, string $id)
    {
        $bookingId = HashidsHelper::resolveId($id) ?? (is_numeric($id) ? (int) $id : null);
        $booking = Booking::where('user_id', $request->user()->id)
            ->findOrFail($bookingId);

        return new BookingResource($booking);
    }

    public function cancel(Request $request, string $id)
    {
        $bookingId = HashidsHelper::resolveId($id) ?? (is_numeric($id) ? (int) $id : null);
        $booking = Booking::where('user_id', $request->user()->id)->findOrFail($bookingId);

        if ($booking->payment_status || $booking->status === 'cancelled') {
            return response()->json(['message' => 'This booking cannot be cancelled.'], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'message' => 'Booking cancelled',
            'booking' => new BookingResource($booking),
        ]);
    }

    public function lookup(Request $request)
    {
        $validated = $request->validate([
            'booking_code' => 'required|string|max:20',
        ]);

        $booking = Booking::where('booking_code', $validated['booking_code'])->first();

        if (!$booking) {
            return response()->json(['message' => 'No booking found with this code.'], 404);
        }

        $items = [];
        foreach ($booking->booking_items ?? [] as $item) {
            $deal = isset($item['deal_id']) ? Deal::with('photos')->find($item['deal_id']) : null;
            $room = isset($item['room_id']) ? Room::find($item['room_id']) : null;
            $items[] = [
                'item' => $item,
                'deal_title' => $deal?->title,
                'deal_cover' => $deal?->cover_photo
                    ? asset('storage/' . ltrim($deal->cover_photo, '/'))
                    : null,
                'room_name' => $room?->name ?? $room?->title,
            ];
        }

        return response()->json([
            'booking' => new BookingResource($booking),
            'items' => $items,
        ]);
    }
}
