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
        ?array $room = null,
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
            'room_name' => (string) ($rate['roomName'] ?? $rate['name'] ?? $room['name'] ?? 'Room'),
            'room_code' => strtoupper(trim((string) ($room['code'] ?? ''))),
            'board_name' => (string) ($rate['boardName'] ?? $rate['boardCode'] ?? ''),
            'category_code' => isset($hotel['categoryCode']) ? (string) $hotel['categoryCode'] : null,
            'supplier_total' => $pricing['supplier_total'],
            'markup' => $pricing['markup'],
            'price' => $pricing['price'],
            'currency' => strtoupper((string) ($rate['currency'] ?? $criteria->currency)),
            'rate_type' => strtoupper((string) ($rate['rateType'] ?? 'BOOKABLE')),
            'rate_comments' => trim((string) ($rate['rateComments'] ?? '')),
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
     * Hotel-level gallery (general photos, not room-specific).
     *
     * @param  array<int, array<string, mixed>>  $images
     * @return array<int, string>
     */
    public static function hotelGalleryImages(array $images, int $limit = 15): array
    {
        if ($images === []) {
            return [self::defaultHotelImage()];
        }

        $general = array_values(array_filter(
            $images,
            fn (array $image) => self::imageTypeCode($image) === 'GEN' && ! empty($image['path'])
        ));

        if ($general !== []) {
            return self::imagesToUrls($general, $limit);
        }

        return self::hotelbedsGalleryImages($images, $limit);
    }

    /**
     * Room photos from Content API (HAB) matched to availability room code.
     *
     * @param  array<int, array<string, mixed>>  $images
     * @return array{urls: array<int, string>, source: string}
     */
    public static function resolveRoomImages(array $images, ?string $roomCode, int $limit = 8): array
    {
        $roomCode = strtoupper(trim((string) $roomCode));
        $matched = [];
        $genericHab = [];

        foreach ($images as $image) {
            if (! is_array($image) || empty($image['path'])) {
                continue;
            }

            if (self::imageTypeCode($image) !== 'HAB') {
                continue;
            }

            $imageRoomCode = strtoupper(trim((string) ($image['roomCode'] ?? '')));

            if ($imageRoomCode === '') {
                $genericHab[] = $image;

                continue;
            }

            if ($roomCode !== '' && self::roomCodesMatch($roomCode, $imageRoomCode)) {
                $matched[] = $image;
            }
        }

        if ($matched !== []) {
            return ['urls' => self::imagesToUrls($matched, $limit), 'source' => 'room'];
        }

        if ($genericHab !== []) {
            return ['urls' => self::imagesToUrls($genericHab, $limit), 'source' => 'generic_room'];
        }

        return [
            'urls' => self::hotelGalleryImages($images, min($limit, 3)),
            'source' => 'hotel',
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $images
     * @return array<int, string>
     */
    public static function roomImagesForCode(array $images, ?string $roomCode, int $limit = 8): array
    {
        return self::resolveRoomImages($images, $roomCode, $limit)['urls'];
    }

    public static function roomCodeFromRateKey(?string $rateKey): string
    {
        if ($rateKey === null || $rateKey === '') {
            return '';
        }

        $parts = explode('|', $rateKey);
        $candidate = strtoupper(trim((string) ($parts[5] ?? '')));

        if (preg_match('/^[A-Z]{2,4}\.[A-Z0-9]{1,4}$/', $candidate)) {
            return $candidate;
        }

        return '';
    }

    protected static function roomCodesMatch(string $availabilityCode, string $imageRoomCode): bool
    {
        if ($availabilityCode === $imageRoomCode) {
            return true;
        }

        $availabilityType = explode('.', $availabilityCode)[0] ?? '';
        $imageType = explode('.', $imageRoomCode)[0] ?? '';

        return $availabilityType !== ''
            && $availabilityType === $imageType;
    }

    /**
     * @param  array<int, array<string, mixed>>  $images
     */
    protected static function imageTypeCode(array $image): string
    {
        $type = $image['imageTypeCode'] ?? $image['type'] ?? null;

        if (is_string($type)) {
            return strtoupper(trim($type));
        }

        if (is_array($type)) {
            return strtoupper(trim((string) ($type['code'] ?? $type['content'] ?? '')));
        }

        return '';
    }

    /**
     * @param  array<int, array<string, mixed>>  $images
     * @return array<int, string>
     */
    protected static function imagesToUrls(array $images, int $limit): array
    {
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

        return self::imagesToUrls($images, $limit);
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

    public static function hotelbedsImageUrl(?string $path, string $size = 'standard'): ?string
    {
        if (! $path) {
            return null;
        }

        $path = ltrim(trim($path), '/');

        if ($path === '') {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        $configured = rtrim((string) config('hotels.hotelbeds.image_base_url', ''), '/');

        if ($configured !== '') {
            if (str_ends_with($configured, '/giata') || str_contains($configured, '/giata/')) {
                return $configured . '/' . $path;
            }

            return $configured . '/' . $path;
        }

        $sizeFolder = match ($size) {
            'bigger' => 'bigger',
            'small' => 'small',
            'medium' => 'medium',
            default => '',
        };

        if ($sizeFolder !== '') {
            return 'https://photos.hotelbeds.com/giata/' . $sizeFolder . '/' . $path;
        }

        return 'https://photos.hotelbeds.com/giata/' . $path;
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

        $preferredTypes = ['GEN', 'HAB', 'COM', 'DEP', 'RES', 'BAR', 'PIS', 'TER'];

        foreach ($preferredTypes as $type) {
            foreach ($images as $image) {
                if (self::imageTypeCode($image) === $type && ! empty($image['path'])) {
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
