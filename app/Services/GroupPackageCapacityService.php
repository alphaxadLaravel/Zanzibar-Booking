<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Tours;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class GroupPackageCapacityService
{
    public function getPaidParticipantCount(int $dealId): int
    {
        return (int) BookingItem::query()
            ->where('deal_id', $dealId)
            ->where('status', 'paid')
            ->selectRaw('COALESCE(SUM(adults + children), 0) as total')
            ->value('total');
    }

    public function getRemainingSpots(Tours $tour): int
    {
        if (!$tour->is_group_package || !$tour->group_max_capacity) {
            return 0;
        }

        return max(0, $tour->group_max_capacity - $this->getPaidParticipantCount($tour->deal_id));
    }

    public function getProgressPercent(Tours $tour): float
    {
        if (!$tour->is_group_package || !$tour->group_max_capacity) {
            return 0;
        }

        $booked = $this->getPaidParticipantCount($tour->deal_id);

        return min(100, round(($booked / $tour->group_max_capacity) * 100, 1));
    }

    public function isBookingOpen(Tours $tour): bool
    {
        if (!$tour->is_group_package) {
            return true;
        }

        if ($tour->group_booking_deadline && Carbon::today()->gt($tour->group_booking_deadline)) {
            return false;
        }

        return !$this->isFull($tour);
    }

    public function isFull(Tours $tour): bool
    {
        if (!$tour->is_group_package || !$tour->group_max_capacity) {
            return false;
        }

        return $this->getRemainingSpots($tour) <= 0;
    }

    /**
     * @return array{allowed: bool, message: string|null}
     */
    public function canBook(Tours $tour, int $participants): array
    {
        if (!$tour->is_group_package) {
            return ['allowed' => true, 'message' => null];
        }

        if ($tour->group_booking_deadline && Carbon::today()->gt($tour->group_booking_deadline)) {
            return [
                'allowed' => false,
                'message' => 'The booking deadline for this group package has passed.',
            ];
        }

        if ($participants < 1) {
            return [
                'allowed' => false,
                'message' => 'Please select at least one participant.',
            ];
        }

        $remaining = $this->getRemainingSpots($tour);

        if ($participants > $remaining) {
            return [
                'allowed' => false,
                'message' => $remaining > 0
                    ? "Only {$remaining} spot(s) remaining for this group package."
                    : 'This group package is fully booked.',
            ];
        }

        return ['allowed' => true, 'message' => null];
    }

    public function statsFor(Tours $tour): array
    {
        $booked = $this->getPaidParticipantCount($tour->deal_id);
        $capacity = (int) ($tour->group_max_capacity ?? 0);
        $remaining = max(0, $capacity - $booked);

        return [
            'booked' => $booked,
            'capacity' => $capacity,
            'remaining' => $remaining,
            'percent' => $capacity > 0 ? min(100, round(($booked / $capacity) * 100, 1)) : 0,
            'is_open' => $this->isBookingOpen($tour),
            'is_full' => $this->isFull($tour),
            'deadline_passed' => $tour->group_booking_deadline
                ? Carbon::today()->gt($tour->group_booking_deadline)
                : false,
        ];
    }

    /**
     * Paid group bookings with guest and booking details for admin display.
     */
    public function paidBookingsForDeal(int $dealId): Collection
    {
        $items = BookingItem::with('user')
            ->where('deal_id', $dealId)
            ->where('status', 'paid')
            ->orderByDesc('updated_at')
            ->get();

        if ($items->isEmpty()) {
            return collect();
        }

        $bookingMap = $this->mapParentBookingsByItemId($items->pluck('id')->all());

        return $items->map(function (BookingItem $item) use ($bookingMap) {
            $parentBooking = $bookingMap[$item->id] ?? null;
            $user = $item->user;

            return [
                'id' => $item->id,
                'name' => $parentBooking?->fullname
                    ?? trim(($user?->firstname ?? '') . ' ' . ($user?->lastname ?? ''))
                    ?: 'Guest',
                'email' => $parentBooking?->email ?? $user?->email,
                'phone' => $parentBooking?->phone ?? $user?->phone,
                'adults' => $item->adults,
                'children' => $item->children,
                'participants' => ($item->adults ?? 0) + ($item->children ?? 0),
                'total_price' => $item->total_price,
                'check_in' => $item->check_in,
                'booked_at' => $item->updated_at,
                'booking_code' => $parentBooking?->booking_code,
                'booking_id' => $parentBooking?->id,
            ];
        });
    }

    /**
     * @param  array<int>  $itemIds
     * @return array<int, Booking>
     */
    protected function mapParentBookingsByItemId(array $itemIds): array
    {
        if (empty($itemIds)) {
            return [];
        }

        $map = [];
        $itemIdSet = array_flip($itemIds);

        Booking::query()
            ->whereIn('status', ['confirmed', 'paid', 'pending'])
            ->orderByDesc('created_at')
            ->chunk(200, function ($bookings) use (&$map, $itemIdSet) {
                foreach ($bookings as $booking) {
                    foreach ($booking->booking_items ?? [] as $item) {
                        $bookingItemId = $item['booking_item_id'] ?? null;
                        if ($bookingItemId && isset($itemIdSet[$bookingItemId]) && !isset($map[$bookingItemId])) {
                            $map[$bookingItemId] = $booking;
                        }
                    }
                }
            });

        return $map;
    }
}
