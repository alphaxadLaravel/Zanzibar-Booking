<?php

namespace App\Services\Flights;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TravelPayoutsApiService
{
    /**
     * Autocomplete airports and cities via TravelPayouts (no token required).
     */
    public function searchLocations(
        string $keyword,
        ?string $countryCode = null,
        array $subTypes = ['AIRPORT', 'CITY'],
        int $limit = 10,
    ): array {
        if (strlen(trim($keyword)) < 2) {
            return [];
        }

        $types = $this->mapSubTypes($subTypes);
        $url = config('flights.travelpayouts.autocomplete_url');
        $query = 'term=' . urlencode(trim($keyword))
            . '&locale=' . urlencode(config('flights.travelpayouts.locale', 'en'));
        foreach ($types as $type) {
            $query .= '&types[]=' . urlencode($type);
        }

        try {
            $response = Http::timeout(15)->get($url . '?' . $query);

            if (! $response->successful()) {
                Log::warning('TravelPayouts autocomplete failed', [
                    'status' => $response->status(),
                    'keyword' => $keyword,
                ]);

                return [];
            }

            $places = $response->json();
            if (! is_array($places)) {
                return [];
            }

            $locations = [];
            foreach (array_slice($places, 0, $limit) as $place) {
                if ($countryCode && strtoupper($place['country_code'] ?? '') !== strtoupper($countryCode)) {
                    continue;
                }

                $subType = strtoupper($place['type'] ?? 'airport');
                $iataCode = $place['code'] ?? null;

                if (! $iataCode) {
                    continue;
                }

                $locations[] = [
                    'id' => $iataCode,
                    'type' => 'location',
                    'subType' => $subType === 'AIRPORT' ? 'AIRPORT' : 'CITY',
                    'iataCode' => $iataCode,
                    'name' => $place['name'] ?? '',
                    'detailedName' => trim(($place['name'] ?? '') . ', ' . ($place['country_name'] ?? '')),
                    'cityName' => $place['city_name'] ?? ($place['name'] ?? ''),
                    'countryName' => $place['country_name'] ?? '',
                    'countryCode' => $place['country_code'] ?? '',
                    'displayName' => $this->formatDisplayName($place),
                ];
            }

            return $locations;
        } catch (\Throwable $e) {
            Log::error('TravelPayouts autocomplete error', [
                'keyword' => $keyword,
                'message' => $e->getMessage(),
            ]);

            return [];
        }
    }

    protected function mapSubTypes(array $subTypes): array
    {
        $types = [];
        foreach ($subTypes as $subType) {
            $types[] = match (strtoupper($subType)) {
                'AIRPORT' => 'airport',
                'CITY' => 'city',
                'COUNTRY' => 'country',
                default => 'airport',
            };
        }

        return array_unique($types ?: ['airport', 'city']);
    }

    protected function formatDisplayName(array $place): string
    {
        $code = $place['code'] ?? '';
        $name = $place['name'] ?? '';
        $country = $place['country_name'] ?? '';
        $type = strtoupper($place['type'] ?? '');

        $label = $type === 'CITY' ? ($place['city_name'] ?? $name) : $name;

        return trim(implode(' — ', array_filter([$code, $label, $country])));
    }
}
