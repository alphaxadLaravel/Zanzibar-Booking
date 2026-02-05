<?php

namespace App\Services;

use App\Models\Room;
use Carbon\Carbon;

class RoomPriceService
{
    /**
     * Calculate total price for a room booking based on dates, guests, and number of rooms.
     * Handles both per_night and per_person_per_night pricing.
     * Applies seasonal price intervals when applicable.
     */
    public function calculateTotalPrice(
        Room $room,
        string $checkIn,
        string $checkOut,
        int $numberRooms,
        int $adults,
        int $children = 0
    ): float {
        $checkInDate = Carbon::parse($checkIn)->startOfDay();
        $checkOutDate = Carbon::parse($checkOut)->startOfDay();

        if ($checkOutDate->lte($checkInDate)) {
            return 0;
        }

        $totalGuests = $adults + $children;
        $totalPrice = 0;

        // Load intervals for this room
        $intervals = $room->priceIntervals()
            ->where('end_date', '>=', $checkInDate)
            ->where('start_date', '<=', $checkOutDate)
            ->orderBy('start_date')
            ->get();

        // Iterate each night of the stay
        $currentDate = $checkInDate->copy();
        while ($currentDate->lt($checkOutDate)) {
            $pricePerNight = $this->getPriceForDate($room, $currentDate, $intervals);

        if (($room->price_type ?? 'per_night') === 'per_person_per_night') {
            $pricePerNight *= max(1, $totalGuests);
        }

            $totalPrice += $pricePerNight * $numberRooms;
            $currentDate->addDay();
        }

        return round($totalPrice, 2);
    }

    /**
     * Get the effective price for a single night on a given date.
     * Uses interval price if date falls within an interval, otherwise room base price.
     */
    public function getPriceForDate(Room $room, Carbon $date, $intervals = null): float
    {
        if ($intervals === null) {
            $intervals = $room->priceIntervals()
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->orderBy('start_date')
                ->get();
        }

        foreach ($intervals as $interval) {
            if ($interval->containsDate($date)) {
                return (float) $interval->price;
            }
        }

        // Use per-person price for per_person_per_night type, or base price for per_night
        if (($room->price_type ?? 'per_night') === 'per_person_per_night' && $room->price_per_person !== null) {
            return (float) $room->price_per_person;
        }

        return (float) $room->price;
    }

    /**
     * Get the display price (base or from interval) for a given date.
     * Used for showing "from $X" on the frontend.
     */
    public function getDisplayPriceForDate(Room $room, ?Carbon $date = null): float
    {
        $date = $date ?? Carbon::today();

        $intervals = $room->priceIntervals()
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->first();

        if ($intervals) {
            return (float) $intervals->price;
        }

        if ($room->price_type === 'per_person_per_night' && $room->price_per_person !== null) {
            return (float) $room->price_per_person;
        }

        return (float) $room->price;
    }

    /**
     * Get minimum price across all intervals and base price.
     */
    public function getMinPrice(Room $room): float
    {
        $basePrice = ($room->price_type ?? 'per_night') === 'per_person_per_night' && $room->price_per_person !== null
            ? (float) $room->price_per_person
            : (float) $room->price;

        $minInterval = $room->priceIntervals()->orderBy('price')->first();

        if (!$minInterval) {
            return $basePrice;
        }

        return min($basePrice, (float) $minInterval->price);
    }
}
