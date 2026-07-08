<?php

namespace App\Services\Flights\Providers;

use App\Contracts\Flights\FlightProviderInterface;
use App\DTOs\FlightOffer;
use App\DTOs\FlightSearchCriteria;
use App\Support\FlightOfferMapper;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TravelPayoutsProvider implements FlightProviderInterface
{
    public function getName(): string
    {
        return 'TravelPayouts';
    }

    public function search(FlightSearchCriteria $criteria): array
    {
        $token = $this->token();

        $response = Http::timeout(15)
            ->retry(1, 150)
            ->get(config('flights.travelpayouts.search_url'), $this->buildQuery($criteria, $token));

        if (! $response->successful()) {
            Log::error('TravelPayouts search failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Unable to search flights at this time. Please try again later.');
        }

        return $this->mapOffers($response->json(), $criteria);
    }

    /**
     * Fetch multiple routes concurrently (used for featured flights).
     *
     * @param  array<string, FlightSearchCriteria>  $criteriaByKey
     * @return array<string, array<int, FlightOffer>>
     */
    public function searchParallel(array $criteriaByKey): array
    {
        if (empty($criteriaByKey)) {
            return [];
        }

        $token = $this->token();
        $searchUrl = config('flights.travelpayouts.search_url');

        $responses = Http::pool(function (Pool $pool) use ($criteriaByKey, $token, $searchUrl) {
            foreach ($criteriaByKey as $key => $criteria) {
                $pool->as($key)
                    ->timeout(12)
                    ->get($searchUrl, $this->buildQuery($criteria, $token));
            }
        });

        $results = [];

        foreach ($criteriaByKey as $key => $criteria) {
            try {
                $response = $responses[$key] ?? null;

                if (! $response || ! $response->successful()) {
                    Log::warning('TravelPayouts parallel route failed', [
                        'route' => $key,
                        'status' => $response?->status(),
                    ]);
                    $results[$key] = [];

                    continue;
                }

                $results[$key] = $this->mapOffers($response->json(), $criteria);
            } catch (\Throwable $e) {
                Log::warning('TravelPayouts parallel route error', [
                    'route' => $key,
                    'message' => $e->getMessage(),
                ]);
                $results[$key] = [];
            }
        }

        return $results;
    }

    public function buildAffiliateUrl(FlightOffer $offer, FlightSearchCriteria $criteria): string
    {
        if (! empty($offer->affiliateUrl)) {
            return $offer->affiliateUrl;
        }

        return $this->buildSearchUrl($criteria);
    }

    /**
     * @return array<int, FlightOffer>
     */
    protected function mapOffers(?array $payload, FlightSearchCriteria $criteria): array
    {
        $items = $payload['data'] ?? [];

        if (empty($items)) {
            return [];
        }

        $marker = config('flights.travelpayouts.marker');
        $offers = [];

        foreach ($items as $index => $item) {
            $availability = FlightOfferMapper::resolveAvailability($item);

            if ($availability['status'] === 'unavailable') {
                continue;
            }

            $offers[] = $this->mapOffer($item, $criteria, $marker, $index, $availability);
        }

        return $offers;
    }

    protected function buildQuery(FlightSearchCriteria $criteria, string $token): array
    {
        $query = [
            'origin' => $criteria->origin,
            'destination' => $criteria->destination,
            'departure_at' => $criteria->departureDate,
            'currency' => $criteria->currency,
            'limit' => $criteria->max,
            'page' => 1,
            'token' => $token,
            'sorting' => 'price',
        ];

        if ($criteria->returnDate) {
            $query['return_at'] = $criteria->returnDate;
        }

        if ($criteria->nonStop) {
            $query['direct'] = 'true';
        }

        // Don't drop fares just because TravelPayouts omits or stale-marks expires_at.
        // The partner site confirms final availability at booking time.

        if ($criteria->tripType === 'one_way' || ! $criteria->returnDate) {
            $query['one_way'] = 'true';
        }

        return $query;
    }

    protected function token(): string
    {
        $token = config('flights.travelpayouts.token');

        if (empty($token)) {
            throw new \RuntimeException('TravelPayouts API token is not configured. Set TRAVELPAYOUTS_TOKEN in your .env file.');
        }

        return $token;
    }

    protected function mapOffer(array $item, FlightSearchCriteria $criteria, ?string $marker, int $index, ?array $availability = null): FlightOffer
    {
        $availability ??= FlightOfferMapper::resolveAvailability($item);
        $airlineCode = strtoupper($item['airline'] ?? 'XX');
        $flightNumber = (string) ($item['flight_number'] ?? $airlineCode);
        $departureAt = $item['departure_at'] ?? $criteria->departureDate . 'T00:00:00';
        $arrivalAt = $item['arrival_at'] ?? $departureAt;
        $origin = strtoupper($item['origin_airport'] ?? $item['origin'] ?? $criteria->origin);
        $destination = strtoupper($item['destination_airport'] ?? $item['destination'] ?? $criteria->destination);
        $stops = (int) ($item['transfers'] ?? $item['return_transfers'] ?? 0);
        $durationMinutes = (int) ($item['duration'] ?? $item['duration_to'] ?? 0);
        $price = (float) ($item['price'] ?? 0);
        $currency = strtoupper($item['currency'] ?? $criteria->currency);
        $affiliateUrl = $this->resolveAffiliateLink($item, $criteria, $marker);

        return new FlightOffer(
            id: md5($airlineCode . $flightNumber . $departureAt . $index),
            flightNumber: $flightNumber,
            airline: FlightOfferMapper::airlineName($airlineCode),
            airlineCode: $airlineCode,
            airlineLogo: FlightOfferMapper::airlineLogoUrl($airlineCode),
            departure: FlightOfferMapper::segment($origin, $departureAt),
            arrival: FlightOfferMapper::segment($destination, $arrivalAt),
            duration: FlightOfferMapper::formatMinutes($durationMinutes),
            stops: $stops,
            cabinClass: $criteria->cabin,
            baggage: $item['baggage'] ?? 'Check airline policy',
            refundable: isset($item['refundable']) ? ($item['refundable'] ? 'Refundable' : 'Non-refundable') : 'Varies',
            price: $price,
            currency: $currency,
            affiliateName: $this->getName(),
            affiliateUrl: $affiliateUrl,
            availabilityStatus: $availability['status'],
            availabilityLabel: $availability['label'],
            priceExpiresAt: $availability['expires_at'] ?? null,
            foundAt: $availability['found_at'] ?? null,
            raw: $item,
        );
    }

    protected function resolveAffiliateLink(array $item, FlightSearchCriteria $criteria, ?string $marker): string
    {
        $baseUrl = rtrim(config('flights.travelpayouts.affiliate_base_url'), '/');

        if (! empty($item['link'])) {
            $link = $item['link'];
            if (! str_starts_with($link, 'http')) {
                $link = $baseUrl . $link;
            }

            return $this->appendMarker($link, $marker);
        }

        return $this->buildSearchUrl($criteria, $marker);
    }

    protected function buildSearchUrl(FlightSearchCriteria $criteria, ?string $marker = null): string
    {
        $marker = $marker ?: config('flights.travelpayouts.marker');
        $baseUrl = rtrim(config('flights.travelpayouts.affiliate_base_url'), '/');
        $departure = date('dmy', strtotime($criteria->departureDate));
        $segment = strtoupper($criteria->origin) . $departure . strtoupper($criteria->destination);

        if ($criteria->returnDate) {
            $segment .= date('dmy', strtotime($criteria->returnDate));
        }

        $url = "{$baseUrl}/search/{$segment}";
        $query = http_build_query(array_filter([
            'marker' => $marker,
            'passengers' => $criteria->adults,
            'children' => $criteria->children ?: null,
            'infants' => $criteria->infants ?: null,
        ]));

        return $url . '?' . $query;
    }

    protected function appendMarker(string $url, ?string $marker): string
    {
        if (empty($marker)) {
            return $url;
        }

        $separator = str_contains($url, '?') ? '&' : '?';

        return $url . $separator . 'marker=' . urlencode($marker);
    }
}
