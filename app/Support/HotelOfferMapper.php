<?php

namespace App\Support;

use App\DTOs\HotelSearchCriteria;

class HotelOfferMapper
{
    /**
     * @return array{supplier_total: float, markup: float, price: float}
     */
    public static function applyMarkup(float $supplierTotal): array
    {
        $percent = (float) config('hotels.markup.percent', 0);
        $fixed = (float) config('hotels.markup.fixed', 0);
        $price = round($supplierTotal * (1 + ($percent / 100)) + $fixed, 2);
        $markup = round(max(0, $price - $supplierTotal), 2);

        return [
            'supplier_total' => round($supplierTotal, 2),
            'markup' => $markup,
            'price' => $price,
        ];
    }

    /**
     * @param  array<string, mixed>  $hotel
     * @param  array<string, mixed>  $rate
     * @return array<string, mixed>
     */
    public static function mapRateToArray(
        array $hotel,
        array $rate,
        HotelSearchCriteria $criteria,
        array $destinationMeta = [],
    ): array {
        $supplierTotal = (float) ($rate['net'] ?? $rate['sellingRate'] ?? 0);
        $pricing = self::applyMarkup($supplierTotal);
        $rateKey = (string) ($rate['rateKey'] ?? '');
        $locationLabel = self::formatHotelLocation($hotel);
        $destinationCode = strtoupper(trim((string) ($hotel['destinationCode'] ?? '')));
        $destinationName = $locationLabel !== ''
            ? $locationLabel
            : (string) ($destinationMeta['name'] ?? $criteria->destination);

        return [
            'id' => md5($rateKey),
            'rate_key' => $rateKey,
            'hotel_code' => (string) ($hotel['code'] ?? ''),
            'hotel_name' => (string) ($hotel['name'] ?? 'Hotel'),
            'destination_code' => $destinationCode !== '' ? $destinationCode : (string) ($destinationMeta['code'] ?? $criteria->destination),
            'destination_name' => $destinationName,
            'zone_name' => trim((string) ($hotel['zoneName'] ?? '')),
            'check_in' => $criteria->checkIn,
            'check_out' => $criteria->checkOut,
            'room_name' => (string) ($rate['roomName'] ?? $rate['name'] ?? 'Room'),
            'board_name' => (string) ($rate['boardName'] ?? $rate['boardCode'] ?? ''),
            'category_code' => isset($hotel['categoryCode']) ? (string) $hotel['categoryCode'] : null,
            'supplier_total' => $pricing['supplier_total'],
            'markup' => $pricing['markup'],
            'price' => $pricing['price'],
            'currency' => strtoupper((string) ($rate['currency'] ?? $criteria->currency)),
            'rate_type' => strtoupper((string) ($rate['rateType'] ?? 'BOOKABLE')),
            'rooms' => $criteria->rooms,
            'adults' => $criteria->adults,
            'children' => $criteria->children,
            'cancellation_policies' => $rate['cancellationPolicies'] ?? [],
            'latitude' => isset($hotel['latitude']) ? (string) $hotel['latitude'] : null,
            'longitude' => isset($hotel['longitude']) ? (string) $hotel['longitude'] : null,
        ];
    }

    /**
     * @param  array<string, mixed>  $hotel
     */
    public static function formatHotelLocation(array $hotel): string
    {
        $destination = trim((string) ($hotel['destinationName'] ?? ''));
        $zone = trim((string) ($hotel['zoneName'] ?? ''));

        if ($zone !== '' && $destination !== '' && strcasecmp($zone, $destination) !== 0) {
            return $zone . ', ' . $destination;
        }

        return $destination !== '' ? $destination : $zone;
    }

    public static function isInTanzania(?string $lat, ?string $lng, ?string $destinationCode = null): bool
    {
        if ($lat !== null && $lng !== null && $lat !== '' && $lng !== '') {
            $latF = (float) $lat;
            $lngF = (float) $lng;

            if ($latF === 0.0 && $lngF === 0.0) {
                return self::isAllowedTanzaniaDestinationCode($destinationCode);
            }

            $bounds = config('hotels.tanzania_bounds', []);

            return $latF >= (float) ($bounds['lat_min'] ?? -12.5)
                && $latF <= (float) ($bounds['lat_max'] ?? -0.5)
                && $lngF >= (float) ($bounds['lng_min'] ?? 29.0)
                && $lngF <= (float) ($bounds['lng_max'] ?? 40.9);
        }

        return self::isAllowedTanzaniaDestinationCode($destinationCode);
    }

    public static function isAllowedTanzaniaDestinationCode(?string $code): bool
    {
        $code = strtoupper(trim((string) $code));

        if ($code === '') {
            return false;
        }

        $allowed = config('hotels.allowed_destination_codes');

        if (! is_array($allowed) || $allowed === []) {
            $allowed = collect(config('hotels.destinations', []))
                ->reject(fn (array $meta, string $key) => $key === 'TZ_ALL')
                ->keys()
                ->all();
        }

        return in_array($code, array_map('strtoupper', $allowed), true);
    }

    /**
     * @param  array<int, array<string, mixed>>  $images
     * @return array<int, string>
     */
    public static function hotelbedsGalleryImages(array $images, int $limit = 15): array
    {
        if ($images === []) {
            return [self::defaultHotelImage()];
        }

        usort($images, function (array $a, array $b) {
            $orderA = (int) ($a['visualOrder'] ?? $a['order'] ?? 999);
            $orderB = (int) ($b['visualOrder'] ?? $b['order'] ?? 999);

            return $orderA <=> $orderB;
        });

        $urls = [];

        foreach ($images as $image) {
            $url = self::hotelbedsImageUrl((string) ($image['path'] ?? ''));

            if ($url) {
                $urls[] = $url;
            }

            if (count($urls) >= $limit) {
                break;
            }
        }

        return $urls !== [] ? array_values(array_unique($urls)) : [self::defaultHotelImage()];
    }

    public static function categoryStars(?string $categoryCode): ?int
    {
        if (! $categoryCode) {
            return null;
        }

        if (preg_match('/(\d)/', $categoryCode, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    public static function defaultHotelImage(): string
    {
        return 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=360&h=240&fit=crop&crop=center';
    }

    public static function hotelbedsImageUrl(?string $path, string $size = 'bigger'): ?string
    {
        if (! $path) {
            return null;
        }

        $path = ltrim($path, '/');
        $base = rtrim(config('hotels.hotelbeds.image_base_url', 'https://photos.hotelbeds.com/giata/bigger/'), '/');

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_contains($base, '/giata/')) {
            return $base . '/' . $path;
        }

        return 'https://photos.hotelbeds.com/giata/' . $size . '/' . $path;
    }

    /**
     * @param  array<int, array<string, mixed>>  $images
     */
    public static function pickHotelbedsImage(array $images): ?string
    {
        if ($images === []) {
            return null;
        }

        usort($images, function (array $a, array $b) {
            $orderA = (int) ($a['visualOrder'] ?? $a['order'] ?? 999);
            $orderB = (int) ($b['visualOrder'] ?? $b['order'] ?? 999);

            return $orderA <=> $orderB;
        });

        $preferredTypes = ['GEN', 'HAB', 'COM'];

        foreach ($preferredTypes as $type) {
            foreach ($images as $image) {
                if (($image['imageTypeCode'] ?? '') === $type && ! empty($image['path'])) {
                    return self::hotelbedsImageUrl((string) $image['path']);
                }
            }
        }

        foreach ($images as $image) {
            if (! empty($image['path'])) {
                return self::hotelbedsImageUrl((string) $image['path']);
            }
        }

        return null;
    }
}
