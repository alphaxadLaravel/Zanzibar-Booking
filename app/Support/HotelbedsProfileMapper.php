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

        $images = HotelOfferMapper::hotelbedsGalleryImages($hotel['images'] ?? []);

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
            'facilities' => $facilities,
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
