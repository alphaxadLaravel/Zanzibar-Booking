<?php

namespace App\Services\Hotels\Providers;

use App\Contracts\Hotels\HotelProviderInterface;
use App\DTOs\HotelOffer;
use App\DTOs\HotelSearchCriteria;
use App\Services\Hotels\HotelbedsApiService;
use App\Support\HotelOfferMapper;

class HotelbedsProvider implements HotelProviderInterface
{
    public function __construct(
        protected HotelbedsApiService $api,
    ) {}

    public function getName(): string
    {
        return 'hotelbeds';
    }

    /**
     * @return HotelOffer[]
     */
    public function search(HotelSearchCriteria $criteria): array
    {
        $response = $this->api->searchAvailability($criteria);
        $destinationMeta = $criteria->destinationMeta();
        $offers = [];

        foreach ($response['hotels']['hotels'] ?? [] as $hotel) {
            foreach ($hotel['rooms'] ?? [] as $room) {
                foreach ($room['rates'] ?? [] as $rate) {
                    $mapped = HotelOfferMapper::mapRateToArray($hotel, $rate, $criteria, $destinationMeta);
                    $offers[] = HotelOffer::fromArray($mapped);
                }
            }
        }

        usort($offers, fn (HotelOffer $a, HotelOffer $b) => $a->price <=> $b->price);

        return array_slice($offers, 0, $criteria->maxHotels);
    }

    public function checkRate(string $rateKey): array
    {
        return $this->api->checkRates($rateKey);
    }

    public function createBooking(
        string $clientReference,
        array $holder,
        array $rooms,
        ?string $remark = null,
    ): array {
        if (! config('hotels.hotelbeds.create_bookings', true)) {
            throw new \RuntimeException('Hotelbeds booking is disabled. Set HOTELBEDS_CREATE_BOOKINGS=true to enable.');
        }

        return $this->api->createBooking($clientReference, $holder, $rooms, $remark);
    }
}
