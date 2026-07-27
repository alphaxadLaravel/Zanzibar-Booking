<?php

namespace App\Services\Hotels;

use App\DTOs\HotelSearchCriteria;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HotelbedsApiService
{
    public function headers(): array
    {
        return [
            'Accept' => 'application/json',
            'Accept-Encoding' => 'gzip',
            'Content-Type' => 'application/json',
            'Api-key' => $this->apiKey(),
            'X-Signature' => $this->signature(),
        ];
    }

    public function baseUrl(): string
    {
        return rtrim(config('hotels.hotelbeds.api_url', 'https://api.test.hotelbeds.com'), '/');
    }

    /**
     * @return array<string, mixed>
     */
    public function searchAvailability(HotelSearchCriteria $criteria): array
    {
        $destination = $criteria->destinationMeta();

        $occupancy = [
            'rooms' => $criteria->rooms,
            'adults' => $criteria->adults,
            'children' => $criteria->children,
        ];

        if ($criteria->children > 0) {
            $occupancy['paxes'] = array_map(
                fn (int $age) => ['type' => 'CH', 'age' => $age],
                $criteria->childAges
            );
        }

        $payload = [
            'stay' => [
                'checkIn' => $criteria->checkIn,
                'checkOut' => $criteria->checkOut,
            ],
            'occupancies' => [$occupancy],
            'destination' => [
                'code' => $destination['code'],
            ],
            'filter' => [
                'maxHotels' => min($criteria->maxHotels, 200),
            ],
        ];

        return $this->post('/hotel-api/1.0/hotels', $payload, 'Hotelbeds availability search failed');
    }

    /**
     * @return array<string, mixed>
     */
    public function checkRates(string $rateKey): array
    {
        return $this->post('/hotel-api/1.0/checkrates', [
            'rooms' => [
                ['rateKey' => $rateKey],
            ],
        ], 'Hotel rate is no longer available. Please search again.');
    }

    /**
     * @param  array<int, array<string, mixed>>  $rooms
     * @return array<string, mixed>
     */
    public function createBooking(
        string $clientReference,
        array $holder,
        array $rooms,
        ?string $remark = null,
    ): array {
        $payload = [
            'holder' => [
                'name' => $holder['name'],
                'surname' => $holder['surname'],
            ],
            'rooms' => $rooms,
            'clientReference' => $clientReference,
        ];

        if ($remark) {
            $payload['remark'] = $remark;
        }

        return $this->post('/hotel-api/1.0/bookings', $payload, 'Unable to confirm hotel booking with supplier.');
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function post(string $path, array $payload, string $fallbackMessage): array
    {
        $timeout = (int) config('hotels.hotelbeds.timeout', 30);

        $response = Http::withHeaders($this->headers())
            ->timeout($timeout)
            ->retry(1, 300)
            ->post($this->baseUrl() . $path, $payload);

        if (! $response->successful()) {
            Log::error('Hotelbeds API request failed', [
                'path' => $path,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException($this->parseErrorMessage($response, $fallbackMessage));
        }

        return $response->json() ?? [];
    }

    protected function parseErrorMessage(Response $response, string $fallback): string
    {
        $json = $response->json();

        if (is_array($json)) {
            $message = $json['error']['message']
                ?? $json['message']
                ?? ($json['error'] ?? null);

            if (is_string($message) && $message !== '') {
                return $message;
            }
        }

        return $fallback;
    }

    protected function apiKey(): string
    {
        $key = trim((string) config('hotels.hotelbeds.api_key', ''));

        if ($key === '') {
            throw new \RuntimeException('Hotelbeds API key is not configured. Set HOTELBEDS_API_KEY in your .env file.');
        }

        return $key;
    }

    protected function signature(): string
    {
        $secret = trim((string) config('hotels.hotelbeds.secret', ''));

        if ($secret === '') {
            throw new \RuntimeException('Hotelbeds API secret is not configured. Set HOTELBEDS_SECRET in your .env file.');
        }

        $timestamp = time();

        return hash('sha256', $this->apiKey() . $secret . $timestamp);
    }
}
