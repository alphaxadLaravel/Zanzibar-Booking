<?php

namespace App\Services\Flights\Providers;

use App\Contracts\Flights\FlightProviderInterface;
use App\DTOs\FlightOffer;
use App\DTOs\FlightSearchCriteria;
use App\Services\Flights\DuffelApiService;
use App\Support\FlightOfferMapper;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuffelProvider implements FlightProviderInterface
{
    public function __construct(
        protected DuffelApiService $api,
    ) {}

    public function getName(): string
    {
        return 'Duffel';
    }

    public function search(FlightSearchCriteria $criteria): array
    {
        $payload = $this->api->createOfferRequest($criteria);

        return $this->mapOffers($payload, $criteria);
    }

    /**
     * @param  array<string, FlightSearchCriteria>  $criteriaByKey
     * @return array<string, array<int, FlightOffer>>
     */
    public function searchParallel(array $criteriaByKey): array
    {
        if (empty($criteriaByKey)) {
            return [];
        }

        $featuredTimeout = (int) config('flights.duffel.featured_supplier_timeout', 8000);
        $baseUrl = $this->api->baseUrl();
        $headers = $this->api->headers();

        $responses = Http::pool(function (Pool $pool) use ($criteriaByKey, $featuredTimeout, $baseUrl, $headers) {
            foreach ($criteriaByKey as $key => $criteria) {
                $query = http_build_query([
                    'return_offers' => 'true',
                    'supplier_timeout' => $featuredTimeout,
                ]);

                $pool->as($key)
                    ->withHeaders($headers)
                    ->timeout(max(20, (int) ceil($featuredTimeout / 1000) + 10))
                    ->post($baseUrl . '/air/offer_requests?' . $query, [
                        'data' => $this->api->offerRequestPayload($criteria),
                    ]);
            }
        });

        $results = [];

        foreach ($criteriaByKey as $key => $criteria) {
            try {
                $response = $responses[$key] ?? null;

                if (! $response || ! $response->successful()) {
                    Log::warning('Duffel parallel route failed', [
                        'route' => $key,
                        'status' => $response?->status(),
                    ]);
                    $results[$key] = [];

                    continue;
                }

                $results[$key] = $this->mapOffers($response->json() ?? [], $criteria);
            } catch (\Throwable $e) {
                Log::warning('Duffel parallel route error', [
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
        return route('flights.checkout', ['offerId' => $offer->id]);
    }

    /**
     * @return array<int, FlightOffer>
     */
    protected function mapOffers(array $payload, FlightSearchCriteria $criteria): array
    {
        $items = $payload['data']['offers'] ?? [];

        if (empty($items)) {
            return [];
        }

        $offers = [];

        foreach ($items as $index => $item) {
            $availability = FlightOfferMapper::resolveDuffelAvailability($item);

            if ($availability['status'] === 'unavailable') {
                continue;
            }

            $offers[] = $this->mapOffer($item, $criteria, $availability, $index);
        }

        usort($offers, fn (FlightOffer $a, FlightOffer $b) => $a->price <=> $b->price);

        if ($criteria->max > 0) {
            $offers = array_slice($offers, 0, $criteria->max);
        }

        return $offers;
    }

    protected function mapOffer(array $item, FlightSearchCriteria $criteria, array $availability, int $index): FlightOffer
    {
        $slice = $item['slices'][0] ?? null;
        $segments = $slice['segments'] ?? [];
        $firstSegment = $segments[0] ?? [];
        $lastSegment = $segments[array_key_last($segments)] ?? $firstSegment;

        $airlineCode = strtoupper($firstSegment['marketing_carrier']['iata_code'] ?? 'XX');
        $operatingCarrier = $firstSegment['operating_carrier']['name'] ?? null;
        $marketingCarrier = $firstSegment['marketing_carrier']['name'] ?? FlightOfferMapper::airlineName($airlineCode);
        $airlineName = $operatingCarrier ?: $marketingCarrier;
        $flightNumber = $this->formatFlightNumbers($segments, $airlineCode, $firstSegment);

        $departureAt = $firstSegment['departing_at'] ?? $criteria->departureDate . 'T00:00:00Z';
        $arrivalAt = $lastSegment['arriving_at'] ?? $departureAt;
        $origin = strtoupper($firstSegment['origin']['iata_code'] ?? $criteria->origin);
        $destination = strtoupper($lastSegment['destination']['iata_code'] ?? $criteria->destination);

        $durationMinutes = FlightOfferMapper::parseIsoDuration($slice['duration'] ?? null);
        if ($durationMinutes <= 0) {
            $durationMinutes = $this->sumSegmentDurations($segments);
        }

        $stops = max(0, count($segments) - 1);
        $price = (float) ($item['total_amount'] ?? 0);
        $currency = strtoupper($item['total_currency'] ?? $criteria->currency);
        $offerId = (string) ($item['id'] ?? md5($airlineCode . $flightNumber . $departureAt . $index));

        $logo = $firstSegment['marketing_carrier']['logo_symbol_url']
            ?? $firstSegment['operating_carrier']['logo_symbol_url']
            ?? FlightOfferMapper::airlineLogoUrl($airlineCode);

        $offer = new FlightOffer(
            id: $offerId,
            flightNumber: $flightNumber,
            airline: $airlineName,
            airlineCode: $airlineCode,
            airlineLogo: $logo,
            departure: FlightOfferMapper::segment($origin, $departureAt),
            arrival: FlightOfferMapper::segment($destination, $arrivalAt),
            duration: FlightOfferMapper::formatMinutes($durationMinutes),
            stops: $stops,
            cabinClass: strtoupper($criteria->cabin),
            baggage: $this->resolveBaggage($item),
            refundable: $this->resolveRefundable($item),
            price: $price,
            currency: $currency,
            affiliateName: $this->getName(),
            affiliateUrl: route('flights.checkout', ['offerId' => $offerId]),
            availabilityStatus: $availability['status'],
            availabilityLabel: $availability['label'],
            priceExpiresAt: $availability['expires_at'] ?? null,
            foundAt: null,
            raw: $item,
        );

        return $offer;
    }

    /**
     * @param  array<int, array<string, mixed>>  $segments
     */
    protected function formatFlightNumbers(array $segments, string $fallbackCode, array $firstSegment): string
    {
        if (empty($segments)) {
            return $fallbackCode;
        }

        $numbers = [];

        foreach ($segments as $segment) {
            $code = strtoupper($segment['marketing_carrier']['iata_code'] ?? $fallbackCode);
            $number = (string) ($segment['marketing_carrier_flight_number'] ?? '');
            $numbers[] = trim($code . $number);
        }

        return implode(' · ', array_filter(array_unique($numbers))) ?: $fallbackCode;
    }

    /**
     * @param  array<int, array<string, mixed>>  $segments
     */
    protected function sumSegmentDurations(array $segments): int
    {
        $total = 0;

        foreach ($segments as $segment) {
            $total += FlightOfferMapper::parseIsoDuration($segment['duration'] ?? null);
        }

        return $total;
    }

    protected function resolveBaggage(array $offer): string
    {
        foreach ($offer['passengers'] ?? [] as $passenger) {
            foreach ($passenger['baggages'] ?? [] as $bag) {
                $type = $bag['type'] ?? '';
                $quantity = (int) ($bag['quantity'] ?? 0);

                if ($quantity > 0) {
                    return $quantity . ' ' . str_replace('_', ' ', $type) . ' bag(s)';
                }
            }
        }

        return 'Check fare details';
    }

    protected function resolveRefundable(array $offer): string
    {
        $refund = $offer['conditions']['refund_before_departure'] ?? null;

        if (is_array($refund)) {
            if (! ($refund['allowed'] ?? false)) {
                return 'Non-refundable';
            }

            $penalty = (float) ($refund['penalty_amount'] ?? 0);

            return $penalty > 0 ? 'Refundable (fee may apply)' : 'Refundable';
        }

        $change = $offer['conditions']['change_before_departure'] ?? null;

        if (is_array($change) && ($change['allowed'] ?? false)) {
            return 'Changes allowed (conditions apply)';
        }

        return 'Varies by fare';
    }
}
