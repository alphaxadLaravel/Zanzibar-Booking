<?php

namespace App\Services\Hotels\Providers;

use App\Contracts\Hotels\HotelProviderInterface;
use App\DTOs\HotelOffer;
use App\DTOs\HotelSearchCriteria;
use App\Services\Hotels\HotelbedsApiService;
use App\Services\Hotels\HotelbedsContentService;
use App\Support\HotelOfferMapper;
use Illuminate\Support\Facades\Log;

class HotelbedsProvider implements HotelProviderInterface
{
    public function __construct(
        protected HotelbedsApiService $api,
        protected HotelbedsContentService $content,
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
        $this->content->syncAllowedDestinationCodes();
        $codes = $this->resolveSearchCodes($criteria);

        if (count($codes) <= 1) {
            $searchCriteria = count($codes) === 1
                ? $criteria->withDestination($codes[0])
                : $criteria;

            return $this->searchSingle($searchCriteria);
        }

        $offers = [];
        $perDestination = max(20, (int) ceil($criteria->maxHotels / count($codes)));

        foreach ($codes as $code) {
            try {
                $subCriteria = $criteria->withDestination($code, $perDestination);
                $offers = array_merge($offers, $this->searchSingle($subCriteria));
            } catch (\Throwable $e) {
                Log::warning('Hotelbeds search skipped destination', [
                    'destination' => $code,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        usort($offers, fn (HotelOffer $a, HotelOffer $b) => $a->price <=> $b->price);

        return $offers;
    }

    /**
     * @return array<int, string>
     */
    protected function resolveSearchCodes(HotelSearchCriteria $criteria): array
    {
        if ($criteria->destination === 'TZ_ALL') {
            $codes = $this->content->tanzaniaDestinationCodes();

            return $codes !== [] ? $codes : $criteria->destinationCodes();
        }

        return [strtoupper($criteria->destination)];
    }

    /**
     * @return HotelOffer[]
     */
    protected function searchSingle(HotelSearchCriteria $criteria): array
    {
        $response = $this->api->searchAvailability($criteria);
        $destinationMeta = $criteria->destinationMeta();
        $offers = [];

        foreach ($response['hotels']['hotels'] ?? [] as $hotel) {
            $lat = isset($hotel['latitude']) ? (string) $hotel['latitude'] : null;
            $lng = isset($hotel['longitude']) ? (string) $hotel['longitude'] : null;
            $destinationCode = (string) ($hotel['destinationCode'] ?? '');

            if (! HotelOfferMapper::isInTanzania($lat, $lng, $destinationCode)) {
                continue;
            }

            foreach ($hotel['rooms'] ?? [] as $room) {
                foreach ($room['rates'] ?? [] as $rate) {
                    $mapped = HotelOfferMapper::mapRateToArray($hotel, $rate, $criteria, $destinationMeta);
                    $offers[] = HotelOffer::fromArray($mapped);
                }
            }
        }

        usort($offers, fn (HotelOffer $a, HotelOffer $b) => $a->price <=> $b->price);

        return $offers;
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
