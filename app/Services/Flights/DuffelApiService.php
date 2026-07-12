<?php

namespace App\Services\Flights;

use App\DTOs\FlightSearchCriteria;
use App\Support\DuffelOrderBuilder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DuffelApiService
{
    public function headers(): array
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Accept-Encoding' => 'gzip',
            'Duffel-Version' => config('flights.duffel.api_version', 'v2'),
            'Authorization' => 'Bearer ' . $this->token(),
        ];
    }

    public function baseUrl(): string
    {
        return rtrim(config('flights.duffel.api_url', 'https://api.duffel.com'), '/');
    }

    /**
     * @return array<string, mixed>
     */
    public function createOfferRequest(FlightSearchCriteria $criteria, ?int $supplierTimeoutMs = null): array
    {
        $timeout = $supplierTimeoutMs ?? (int) config('flights.duffel.supplier_timeout', 15000);

        $query = http_build_query([
            'return_offers' => 'true',
            'supplier_timeout' => $timeout,
        ]);

        $response = Http::withHeaders($this->headers())
            ->timeout(max(30, (int) ceil($timeout / 1000) + 15))
            ->retry(1, 200)
            ->post($this->baseUrl() . '/air/offer_requests?' . $query, [
                'data' => $this->buildOfferRequestPayload($criteria),
            ]);

        if (! $response->successful()) {
            Log::error('Duffel offer request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'origin' => $criteria->origin,
                'destination' => $criteria->destination,
            ]);

            throw new \RuntimeException($this->parseErrorMessage($response, 'Unable to search flights at this time. Please try again later.'));
        }

        return $response->json() ?? [];
    }

    /**
     * Refresh a single offer before checkout (Duffel recommends this — offers expire quickly).
     *
     * @see https://duffel.com/docs/guides/getting-started-with-flights#selecting-an-offer-from-the-search-results
     *
     * @return array<string, mixed>
     */
    public function getOffer(string $offerId): array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout(20)
            ->get($this->baseUrl() . '/air/offers/' . urlencode($offerId));

        if (! $response->successful()) {
            Log::error('Duffel get offer failed', [
                'status' => $response->status(),
                'offer_id' => $offerId,
                'body' => $response->body(),
            ]);

            throw new \RuntimeException($this->parseErrorMessage($response, 'This flight offer is no longer available. Please search again.'));
        }

        return $response->json('data') ?? [];
    }

    /**
     * Create a Duffel order (instant + balance payment) after checkout.
     *
     * @see https://duffel.com/docs/guides/getting-started-with-flights#creating-a-booking-using-the-selected-offer
     *
     * @param  array<string, mixed>  $offer
     * @param  array<int, array<string, mixed>>  $formPassengers
     * @return array<string, mixed>
     */
    public function createOrder(
        array $offer,
        array $formPassengers,
        string $contactEmail,
        string $contactPhone,
    ): array {
        $passengers = DuffelOrderBuilder::passengers(
            $offer['passengers'] ?? [],
            $formPassengers,
            $contactEmail,
            $contactPhone,
        );

        $payload = [
            'type' => config('flights.duffel.order_type', 'instant'),
            'selected_offers' => [(string) $offer['id']],
            'passengers' => $passengers,
        ];

        if (config('flights.duffel.order_type', 'instant') === 'instant') {
            $payload['payments'] = [[
                'type' => config('flights.duffel.payment_type', 'balance'),
                'currency' => (string) $offer['total_currency'],
                'amount' => (string) $offer['total_amount'],
            ]];
        }

        $response = Http::withHeaders($this->headers())
            ->timeout(60)
            ->retry(0)
            ->post($this->baseUrl() . '/air/orders', [
                'data' => $payload,
            ]);

        if (! $response->successful()) {
            Log::error('Duffel create order failed', [
                'status' => $response->status(),
                'offer_id' => $offer['id'] ?? null,
                'body' => $response->body(),
            ]);

            throw new \RuntimeException($this->parseErrorMessage($response, 'Unable to complete the flight booking. Please try again.'));
        }

        return $response->json('data') ?? [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getOrder(string $orderId): array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout(30)
            ->get($this->baseUrl() . '/air/orders/' . urlencode($orderId));

        if (! $response->successful()) {
            Log::error('Duffel get order failed', [
                'status' => $response->status(),
                'order_id' => $orderId,
                'body' => $response->body(),
            ]);

            throw new \RuntimeException($this->parseErrorMessage($response, 'Unable to retrieve your flight ticket details.'));
        }

        return $response->json('data') ?? [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function searchPlaces(string $keyword, int $limit = 10): array
    {
        $response = Http::withHeaders($this->headers())
            ->timeout(10)
            ->get($this->baseUrl() . '/places/suggestions', [
                'query' => $keyword,
            ]);

        if (! $response->successful()) {
            Log::warning('Duffel place suggestions failed', [
                'status' => $response->status(),
                'keyword' => $keyword,
            ]);

            return [];
        }

        $places = $response->json('data') ?? [];

        return array_slice($this->mapPlaces($places), 0, $limit);
    }

    /**
     * @return array<string, mixed>
     */
    public function offerRequestPayload(FlightSearchCriteria $criteria): array
    {
        return $this->buildOfferRequestPayload($criteria);
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildOfferRequestPayload(FlightSearchCriteria $criteria): array
    {
        $payload = [
            'cabin_class' => $this->mapCabinClass($criteria->cabin),
            'passengers' => $this->buildPassengers($criteria),
            'slices' => $this->buildSlices($criteria),
        ];

        if ($criteria->nonStop) {
            $payload['max_connections'] = 0;
        }

        return $payload;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function buildSlices(FlightSearchCriteria $criteria): array
    {
        $slices = [[
            'origin' => $criteria->origin,
            'destination' => $criteria->destination,
            'departure_date' => $criteria->departureDate,
        ]];

        if ($criteria->tripType === 'round_trip' && $criteria->returnDate) {
            $slices[] = [
                'origin' => $criteria->destination,
                'destination' => $criteria->origin,
                'departure_date' => $criteria->returnDate,
            ];
        }

        return $slices;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function buildPassengers(FlightSearchCriteria $criteria): array
    {
        $passengers = [];

        for ($i = 0; $i < $criteria->adults; $i++) {
            $passengers[] = ['type' => 'adult'];
        }

        for ($i = 0; $i < $criteria->children; $i++) {
            // Duffel: passengers under 18 are described by age only (not type + age).
            $passengers[] = ['age' => 8];
        }

        for ($i = 0; $i < $criteria->infants; $i++) {
            $passengers[] = ['age' => 1];
        }

        if (empty($passengers)) {
            $passengers[] = ['type' => 'adult'];
        }

        return $passengers;
    }

    protected function mapCabinClass(string $cabin): string
    {
        return match (strtoupper($cabin)) {
            'PREMIUM_ECONOMY' => 'premium_economy',
            'BUSINESS' => 'business',
            'FIRST' => 'first',
            default => 'economy',
        };
    }

    /**
     * @param  array<int, array<string, mixed>>  $places
     * @return array<int, array<string, mixed>>
     */
    protected function mapPlaces(array $places): array
    {
        $results = [];

        foreach ($places as $place) {
            $type = strtoupper($place['type'] ?? 'AIRPORT');
            $iata = $place['iata_code'] ?? $place['iata_city_code'] ?? null;

            if (! $iata) {
                continue;
            }

            $results[] = [
                'id' => $place['id'] ?? $iata,
                'iataCode' => strtoupper($iata),
                'subType' => $type === 'CITY' ? 'CITY' : 'AIRPORT',
                'name' => $place['name'] ?? $iata,
                'cityName' => $place['city_name'] ?? $place['name'] ?? null,
                'countryName' => $place['city']['name'] ?? null,
                'countryCode' => $place['iata_country_code'] ?? null,
                'displayName' => trim(($place['name'] ?? $iata) . ' (' . strtoupper($iata) . ')'),
            ];
        }

        return $results;
    }

    protected function parseErrorMessage(\Illuminate\Http\Client\Response $response, string $fallback): string
    {
        $errors = $response->json('errors');

        if (is_array($errors) && ! empty($errors[0]['message'])) {
            return (string) $errors[0]['message'];
        }

        return $fallback;
    }

    protected function token(): string
    {
        $token = config('flights.duffel.access_token');

        if (empty($token)) {
            throw new \RuntimeException('Duffel API access token is not configured. Set DUFFEL_ACCESS_TOKEN in your .env file.');
        }

        return $token;
    }
}
