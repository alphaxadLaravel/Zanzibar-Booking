<?php

namespace App\Support;

class HotelbedsProfileMapper
{
    /**
     * @param  array<string, mixed>  $response
     * @return array<string, mixed>
     */
    public static function mapDetails(array $response, string $fallbackCode): array
    {
        $hotel = $response['hotel'] ?? $response;
        $rawImages = is_array($hotel['images'] ?? null) ? $hotel['images'] : [];
        $images = HotelOfferMapper::hotelGalleryImages($rawImages);

        $facilities = [];
        foreach ($hotel['facilities'] ?? [] as $facility) {
            $label = self::textContent($facility['description'] ?? $facility['facilityName'] ?? '');

            if ($label === '') {
                continue;
            }

            if (isset($facility['indFee']) && $facility['indFee'] === true) {
                $label .= ' (extra charge)';
            }

            $facilities[] = $label;
        }

        $facilities = array_values(array_unique($facilities));

        $reviews = self::mapReviews($hotel);
        $issues = self::mapIssues($hotel);
        $interestPoints = self::mapInterestPoints($hotel);
        $nearbyLocations = self::mapNearbyLocations($hotel);
        $phones = self::mapPhones($hotel);
        [$checkInTime, $checkOutTime] = self::extractCheckTimes($hotel, $facilities);

        $latitude = $hotel['coordinates']['latitude']
            ?? $hotel['latitude']
            ?? null;
        $longitude = $hotel['coordinates']['longitude']
            ?? $hotel['longitude']
            ?? null;

        return [
            'code' => (string) ($hotel['code'] ?? $fallbackCode),
            'name' => self::textContent($hotel['name'] ?? '') ?: 'Hotel',
            'description' => self::textContent($hotel['description'] ?? ''),
            'images' => $images,
            'images_raw' => $rawImages,
            'facilities' => $facilities,
            'reviews' => $reviews,
            'issues' => $issues,
            'interest_points' => $interestPoints,
            'nearby_locations' => $nearbyLocations,
            'phones' => $phones,
            'check_in_time' => $checkInTime,
            'check_out_time' => $checkOutTime,
            'address' => self::buildAddress($hotel),
            'latitude' => $latitude !== null ? (string) $latitude : null,
            'longitude' => $longitude !== null ? (string) $longitude : null,
            'category_code' => isset($hotel['categoryCode']) ? (string) $hotel['categoryCode'] : null,
            'destination' => self::textContent($hotel['destination']['name'] ?? $hotel['city']['content'] ?? ''),
            'email' => $hotel['email'] ?? null,
            'website' => $hotel['web'] ?? null,
        ];
    }

    /**
     * @param  array<string, mixed>  $hotel
     */
    protected static function buildAddress(array $hotel): string
    {
        $parts = array_filter([
            self::textContent($hotel['address']['content'] ?? $hotel['address'] ?? ''),
            self::textContent($hotel['city']['content'] ?? $hotel['city'] ?? ''),
            self::textContent($hotel['destination']['name'] ?? ''),
            self::textContent($hotel['country']['description']['content'] ?? $hotel['countryCode'] ?? ''),
        ]);

        return implode(', ', array_unique($parts));
    }

    /**
     * @param  array<string, mixed>  $hotel
     * @return array<int, array<string, mixed>>
     */
    protected static function mapReviews(array $hotel): array
    {
        $reviews = [];

        foreach ($hotel['reviews'] ?? [] as $review) {
            if (! is_array($review)) {
                continue;
            }

            $rate = isset($review['rate']) ? (float) $review['rate'] : null;

            if ($rate === null) {
                continue;
            }

            $reviews[] = [
                'type' => (string) ($review['type'] ?? ''),
                'rate' => $rate,
                'review_count' => isset($review['reviewCount']) ? (int) $review['reviewCount'] : null,
            ];
        }

        return $reviews;
    }

    /**
     * @param  array<string, mixed>  $hotel
     * @return array<int, array<string, mixed>>
     */
    protected static function mapIssues(array $hotel): array
    {
        $issues = [];

        foreach ($hotel['issues'] ?? [] as $issue) {
            if (! is_array($issue)) {
                continue;
            }

            $description = self::textContent($issue['description'] ?? '');

            if ($description === '') {
                continue;
            }

            $issues[] = [
                'type' => (string) ($issue['issueType'] ?? $issue['issueCode'] ?? ''),
                'description' => $description,
                'date_from' => $issue['dateFrom'] ?? null,
                'date_to' => $issue['dateTo'] ?? null,
                'alternative' => filter_var($issue['alternative'] ?? false, FILTER_VALIDATE_BOOL),
            ];
        }

        return $issues;
    }

    /**
     * @param  array<string, mixed>  $hotel
     * @return array<int, array<string, mixed>>
     */
    protected static function mapInterestPoints(array $hotel): array
    {
        $points = [];

        foreach ($hotel['interestPoints'] ?? [] as $point) {
            if (! is_array($point)) {
                continue;
            }

            $name = self::textContent($point['poiName'] ?? $point['name'] ?? '');

            if ($name === '') {
                continue;
            }

            $points[] = [
                'name' => $name,
                'distance' => isset($point['distance']) ? (int) $point['distance'] : null,
                'category' => self::inferCategoryFromName($name),
            ];
        }

        return $points;
    }

    /**
     * Terminals (airports, ports, etc.) + interest points as unified nearby locations.
     *
     * @param  array<string, mixed>  $hotel
     * @return array<int, array<string, mixed>>
     */
    protected static function mapNearbyLocations(array $hotel): array
    {
        $locations = [];

        foreach ($hotel['terminals'] ?? [] as $terminal) {
            if (! is_array($terminal)) {
                continue;
            }

            $name = self::textContent($terminal['name'] ?? '');

            if ($name === '') {
                continue;
            }

            $category = self::textContent($terminal['description'] ?? '');

            if ($category === '') {
                $category = self::terminalTypeCategory((string) ($terminal['terminalType'] ?? ''));
            }

            $distanceKm = isset($terminal['distance']) ? (float) $terminal['distance'] : null;
            $distanceMeters = $distanceKm !== null ? (int) round($distanceKm * 1000) : null;

            $locations[] = [
                'name' => $name,
                'category' => $category !== '' ? $category : 'Other',
                'distance_meters' => $distanceMeters,
                'formatted_distance' => self::formatDistance($distanceMeters),
            ];
        }

        foreach ($hotel['interestPoints'] ?? [] as $point) {
            if (! is_array($point)) {
                continue;
            }

            $name = self::textContent($point['poiName'] ?? $point['name'] ?? '');

            if ($name === '') {
                continue;
            }

            $distanceMeters = isset($point['distance']) ? (int) $point['distance'] : null;

            $locations[] = [
                'name' => $name,
                'category' => self::inferCategoryFromName($name),
                'distance_meters' => $distanceMeters,
                'formatted_distance' => self::formatDistance($distanceMeters),
            ];
        }

        $unique = [];

        foreach ($locations as $location) {
            $key = strtolower(trim((string) $location['name']));

            if ($key === '') {
                continue;
            }

            if (! isset($unique[$key])) {
                $unique[$key] = $location;

                continue;
            }

            $existingDistance = $unique[$key]['distance_meters'];
            $newDistance = $location['distance_meters'];

            if ($existingDistance === null || ($newDistance !== null && $newDistance < $existingDistance)) {
                $unique[$key] = $location;
            }
        }

        $sorted = array_values($unique);

        usort($sorted, function (array $a, array $b): int {
            $distanceA = $a['distance_meters'] ?? PHP_INT_MAX;
            $distanceB = $b['distance_meters'] ?? PHP_INT_MAX;

            if ($distanceA === $distanceB) {
                return strcasecmp((string) $a['name'], (string) $b['name']);
            }

            return $distanceA <=> $distanceB;
        });

        return $sorted;
    }

    protected static function terminalTypeCategory(string $type): string
    {
        return match (strtoupper($type)) {
            'A' => 'Airport',
            'P' => 'Ferry Port',
            'T' => 'Train Station',
            'B' => 'Bus Station',
            default => 'Other',
        };
    }

    protected static function inferCategoryFromName(string $name): string
    {
        $lower = strtolower($name);

        $rules = [
            'Airport' => ['airport', 'airstrip'],
            'Ferry Port' => ['ferry', 'port', 'harbour', 'harbor'],
            'Beach' => ['beach', 'coast'],
            'Tourist Attraction' => ['stone town', 'museum', 'park', 'ruins', 'palace', 'fort', 'market', 'island', 'mnemba', 'party', 'entertainment', 'attraction'],
            'Restaurant' => ['restaurant', 'cafe', 'bar'],
            'Shopping Center' => ['mall', 'shopping'],
            'Hospital' => ['hospital', 'clinic'],
            'Bus Station' => ['bus station', 'bus stop'],
            'Train Station' => ['train', 'railway'],
        ];

        foreach ($rules as $category => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($lower, $keyword)) {
                    return $category;
                }
            }
        }

        return 'Tourist Attraction';
    }

    protected static function formatDistance(?int $meters): ?string
    {
        if ($meters === null || $meters < 0) {
            return null;
        }

        if ($meters < 1000) {
            return $meters . ' m';
        }

        $km = $meters / 1000;

        if ($km < 10) {
            return number_format($km, 1) . ' km';
        }

        return number_format($km, 0) . ' km';
    }

    /**
     * @param  array<string, mixed>  $hotel
     * @return array<int, string>
     */
    protected static function mapPhones(array $hotel): array
    {
        $phones = [];

        foreach ($hotel['phones'] ?? [] as $phone) {
            if (is_string($phone)) {
                $value = trim($phone);
            } elseif (is_array($phone)) {
                $value = trim((string) ($phone['phoneNumber'] ?? $phone['phone'] ?? ''));
            } else {
                $value = '';
            }

            if ($value !== '') {
                $phones[] = $value;
            }
        }

        return array_values(array_unique($phones));
    }

    /**
     * @param  array<int, string>  $facilities
     * @return array{0: ?string, 1: ?string}
     */
    protected static function extractCheckTimes(array $hotel, array $facilities): array
    {
        $checkIn = null;
        $checkOut = null;

        foreach ($hotel['wildcards'] ?? [] as $wildcard) {
            if (! is_array($wildcard)) {
                continue;
            }

            $code = strtoupper((string) ($wildcard['wildcardCode'] ?? ''));
            $text = self::textContent($wildcard['description'] ?? $wildcard['name'] ?? '');

            if ($text === '') {
                continue;
            }

            if (in_array($code, ['CHECKIN', 'HIN', 'CI'], true) || str_contains(strtolower($text), 'check-in')) {
                $checkIn = $text;
            }

            if (in_array($code, ['CHECKOUT', 'HOUT', 'CO'], true) || str_contains(strtolower($text), 'check-out')) {
                $checkOut = $text;
            }
        }

        foreach ($facilities as $facility) {
            $lower = strtolower($facility);

            if ($checkIn === null && str_contains($lower, 'check-in')) {
                $checkIn = $facility;
            }

            if ($checkOut === null && str_contains($lower, 'check-out')) {
                $checkOut = $facility;
            }
        }

        return [$checkIn, $checkOut];
    }

    protected static function textContent(mixed $value): string
    {
        if (is_string($value)) {
            return trim($value);
        }

        if (is_array($value)) {
            return trim((string) ($value['content'] ?? $value['name'] ?? ''));
        }

        return '';
    }
}
