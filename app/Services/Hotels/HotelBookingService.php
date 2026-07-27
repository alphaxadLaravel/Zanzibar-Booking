<?php

namespace App\Services\Hotels;

use App\Models\SupplierHotelBooking;
use App\Support\HotelOfferMapper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class HotelBookingService
{
    public function __construct(
        protected HotelProviderManager $providers,
        protected HotelbedsApiService $hotelbedsApi,
    ) {}

    /**
     * @param  array<string, mixed>  $offer
     * @param  array<string, mixed>  $validated
     */
    public function createPendingBooking(array $offer, array $validated): SupplierHotelBooking
    {
        return DB::transaction(function () use ($offer, $validated) {
            $booking = SupplierHotelBooking::create([
                'booking_reference' => SupplierHotelBooking::generateBookingReference(),
                'user_id' => auth()->id(),
                'supplier' => config('hotels.provider', 'hotelbeds'),
                'hotel_code' => (string) ($offer['hotel_code'] ?? ''),
                'hotel_name' => (string) ($offer['hotel_name'] ?? 'Hotel'),
                'destination_code' => (string) ($offer['destination_code'] ?? ''),
                'destination_name' => (string) ($offer['destination_name'] ?? ''),
                'check_in' => $offer['check_in'] ?? now()->toDateString(),
                'check_out' => $offer['check_out'] ?? now()->addDay()->toDateString(),
                'room_name' => (string) ($offer['room_name'] ?? ''),
                'board_name' => (string) ($offer['board_name'] ?? ''),
                'rooms' => (int) ($offer['rooms'] ?? 1),
                'adults' => (int) ($validated['adults'] ?? $offer['adults'] ?? 1),
                'children' => (int) ($validated['children'] ?? $offer['children'] ?? 0),
                'supplier_cost' => (float) ($offer['supplier_total'] ?? 0),
                'markup_amount' => (float) ($offer['markup'] ?? 0),
                'total_price' => (float) ($offer['price'] ?? 0),
                'currency' => strtoupper((string) ($offer['currency'] ?? 'USD')),
                'rate_key' => (string) ($offer['rate_key'] ?? ''),
                'status' => 'pending',
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'] ?? null,
                'supplier_payload' => [
                    'offer' => $offer,
                    '_pricing' => [
                        'supplier_total' => (float) ($offer['supplier_total'] ?? 0),
                        'markup' => (float) ($offer['markup'] ?? 0),
                        'customer_total' => (float) ($offer['price'] ?? 0),
                    ],
                    '_checkout' => [
                        'holder' => [
                            'name' => $validated['holder_name'],
                            'surname' => $validated['holder_surname'],
                        ],
                        'guests' => $validated['guests'] ?? [],
                        'contact_email' => $validated['contact_email'],
                        'contact_phone' => $validated['contact_phone'] ?? null,
                        'child_ages' => $validated['child_ages'] ?? [],
                    ],
                ],
            ]);

            return $booking;
        });
    }

    /**
     * Store checkout offer in cache and return token for URL.
     *
     * @param  array<string, mixed>  $offer
     */
    public function storeCheckoutOffer(array $offer): string
    {
        $token = (string) Str::uuid();
        $ttl = (int) config('hotels.checkout_ttl', 1800);
        Cache::put('hotel_checkout:' . $token, $offer, $ttl);

        return $token;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getCheckoutOffer(string $token): ?array
    {
        $offer = Cache::get('hotel_checkout:' . $token);

        return is_array($offer) ? $offer : null;
    }

    /**
     * @param  array<string, mixed>  $offer
     */
    public function updateCheckoutOffer(string $token, array $offer): void
    {
        $ttl = (int) config('hotels.checkout_ttl', 1800);
        Cache::put('hotel_checkout:' . $token, $offer, $ttl);
    }

    /**
     * Re-check rate with supplier before checkout if required.
     *
     * @param  array<string, mixed>  $offer
     * @return array<string, mixed>
     */
    public function refreshOfferRate(array $offer): array
    {
        $rateKey = (string) ($offer['rate_key'] ?? '');

        if ($rateKey === '') {
            throw new \RuntimeException('Invalid hotel rate. Please search again.');
        }

        if (strtoupper((string) ($offer['rate_type'] ?? 'BOOKABLE')) !== 'RECHECK') {
            return $offer;
        }

        $response = $this->providers->driver()->checkRate($rateKey);
        $hotel = $response['hotels']['hotels'][0] ?? null;
        $rate = $hotel['rooms'][0]['rates'][0] ?? null;

        if (! is_array($hotel) || ! is_array($rate)) {
            throw new \RuntimeException('This room rate is no longer available. Please search again.');
        }

        $criteria = \App\DTOs\HotelSearchCriteria::fromArray(session('hotel_search_criteria', [
            'destination' => $offer['destination_code'] ?? 'ZNZ',
            'checkIn' => $offer['check_in'] ?? now()->format('Y-m-d'),
            'checkOut' => $offer['check_out'] ?? now()->addDay()->format('Y-m-d'),
            'rooms' => $offer['rooms'] ?? 1,
            'adults' => $offer['adults'] ?? 2,
            'children' => $offer['children'] ?? 0,
        ]));

        $refreshed = HotelOfferMapper::mapRateToArray(
            $hotel,
            $rate,
            $criteria,
            $criteria->destinationMeta(),
        );

        return array_merge($offer, $refreshed);
    }

    public function fulfillAfterPayment(SupplierHotelBooking $booking): SupplierHotelBooking
    {
        if ($booking->status === 'confirmed' && $booking->supplier_booking_ref) {
            return $booking;
        }

        $payload = $booking->supplier_payload ?? [];
        $checkout = $payload['_checkout'] ?? null;
        $offer = $payload['offer'] ?? null;

        if (! $checkout || ! $offer) {
            throw new \RuntimeException('Hotel booking data is incomplete. Please contact support.');
        }

        $offer = $this->refreshOfferRate($offer);
        $rateKey = (string) ($offer['rate_key'] ?? $booking->rate_key);

        $paxes = $this->buildPaxes(
            $checkout['guests'] ?? [],
            (int) $booking->adults,
            (int) $booking->children,
            $checkout['child_ages'] ?? [],
            $checkout['holder'] ?? [],
        );

        $rooms = [[
            'rateKey' => $rateKey,
            'paxes' => $paxes,
        ]];

        try {
            $response = $this->providers->driver()->createBooking(
                $booking->booking_reference,
                $checkout['holder'],
                $rooms,
                'Booking via Zanzibar Bookings',
            );
        } catch (\Throwable $e) {
            Log::error('Hotelbeds booking failed after payment', [
                'supplier_hotel_booking_id' => $booking->id,
                'error' => $e->getMessage(),
            ]);

            $booking->markAsFailed(['error' => $e->getMessage()]);

            throw $e;
        }

        $supplierRef = (string) ($response['booking']['reference'] ?? $response['booking']['clientReference'] ?? '');
        $supplierCost = (float) ($response['booking']['totalNet'] ?? $offer['supplier_total'] ?? $booking->supplier_cost);
        $customerTotal = (float) $booking->total_price;
        $markup = round(max(0, $customerTotal - $supplierCost), 2);

        $booking->update([
            'status' => 'confirmed',
            'rate_key' => $rateKey,
            'supplier_cost' => $supplierCost,
            'markup_amount' => $markup,
            'supplier_booking_ref' => $supplierRef ?: null,
            'supplier_response' => $response,
            'supplier_payload' => array_merge($payload, [
                'offer' => $offer,
                '_pricing' => array_merge($payload['_pricing'] ?? [], [
                    'supplier_total' => $supplierCost,
                    'markup' => $markup,
                    'customer_total' => $customerTotal,
                ]),
            ]),
            'confirmed_at' => now(),
        ]);

        return $booking->fresh();
    }

    /**
     * @param  array<int, array<string, mixed>>  $guests
     * @param  array<int, int>  $childAges
     * @return array<int, array<string, mixed>>
     */
    protected function buildPaxes(
        array $guests,
        int $adults,
        int $children,
        array $childAges,
        array $holder,
    ): array {
        $paxes = [];
        $roomId = 1;

        foreach ($guests as $index => $guest) {
            $type = strtoupper((string) ($guest['type'] ?? 'AD'));
            $paxes[] = [
                'roomId' => $roomId,
                'type' => in_array($type, ['AD', 'CH'], true) ? $type : 'AD',
                'name' => (string) ($guest['name'] ?? $holder['name'] ?? 'Guest'),
                'surname' => (string) ($guest['surname'] ?? $holder['surname'] ?? 'Traveler'),
            ];

            if ($type === 'CH' && isset($childAges[$index])) {
                $paxes[count($paxes) - 1]['age'] = (int) $childAges[$index];
            }
        }

        if (empty($paxes)) {
            for ($i = 0; $i < $adults; $i++) {
                $paxes[] = [
                    'roomId' => $roomId,
                    'type' => 'AD',
                    'name' => (string) ($holder['name'] ?? 'Guest'),
                    'surname' => (string) ($holder['surname'] ?? 'Traveler'),
                ];
            }

            foreach ($childAges as $age) {
                $paxes[] = [
                    'roomId' => $roomId,
                    'type' => 'CH',
                    'age' => (int) $age,
                    'name' => (string) ($holder['name'] ?? 'Child'),
                    'surname' => (string) ($holder['surname'] ?? 'Traveler'),
                ];
            }
        }

        return $paxes;
    }
}
