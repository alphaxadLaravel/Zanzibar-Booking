<?php

namespace App\Services\Hotels;

use App\Support\HotelbedsProfileMapper;
use App\Support\HotelOfferMapper;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HotelbedsContentService
{
    public function __construct(
        protected HotelbedsApiService $api,
    ) {}

    /**
     * @param  array<int, int|string>  $hotelCodes
     * @param  array<string, string>  $prefilled  code => url already known (e.g. from browse list)
     * @return array<string, string>
     */
    public function imagesForHotels(array $hotelCodes, array $prefilled = []): array
    {
        $images = [];
        $default = HotelOfferMapper::defaultHotelImage();

        foreach ($prefilled as $code => $url) {
            if (is_string($url) && $url !== '' && $url !== $default) {
                $images[(string) $code] = $url;
            }
        }

        $missing = array_values(array_filter(
            array_unique(array_map('strval', $hotelCodes)),
            fn (string $code) => ! isset($images[$code])
        ));

        if ($missing !== []) {
            $batch = $this->batchImagesForCodes($missing);

            foreach ($missing as $code) {
                $images[$code] = $batch[$code] ?? $this->imageForHotel($code);
            }
        }

        return $images;
    }

    /**
     * One Content API call for multiple hotel thumbnails (avoids N detail requests).
     *
     * @param  array<int, int|string>  $codes
     * @return array<string, string>
     */
    protected function batchImagesForCodes(array $codes): array
    {
        $codes = array_values(array_unique(array_filter(array_map('strval', $codes))));

        if ($codes === []) {
            return [];
        }

        sort($codes, SORT_STRING);
        $cacheKey = 'hotelbeds.images.v1.' . md5(implode(',', $codes));
        $cacheTtl = (int) config('hotels.hotelbeds.content_cache_ttl', 86400);

        $cached = Cache::get($cacheKey);

        if (is_array($cached)) {
            return $cached;
        }

        try {
            $response = $this->api->listHotelsByCodes($codes, 'code,images');
            $map = [];

            foreach ($response['hotels'] ?? [] as $hotel) {
                if (! is_array($hotel)) {
                    continue;
                }

                $code = (string) ($hotel['code'] ?? '');
                $rawImages = is_array($hotel['images'] ?? null) ? $hotel['images'] : [];
                $url = HotelOfferMapper::pickHotelbedsImage($rawImages);

                if ($code !== '' && $url) {
                    $map[$code] = $url;
                }
            }

            Cache::put($cacheKey, $map, $cacheTtl);

            return $map;
        } catch (\Throwable $e) {
            Log::warning('Hotelbeds batch image fetch failed', [
                'codes' => $codes,
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    public function imageForHotel(int|string $hotelCode): string
    {
        return $this->imageFromProfile($this->profileForHotel($hotelCode));
    }

    /**
     * @param  array<int, array<string, mixed>>  $rawImages
     */
    public function imageFromRawImages(array $rawImages): ?string
    {
        return HotelOfferMapper::pickHotelbedsImage($rawImages);
    }

    /**
     * @param  array<string, mixed>  $profile
     */
    protected function imageFromProfile(array $profile): string
    {
        $url = HotelOfferMapper::pickHotelbedsImage($profile['images_raw'] ?? []);

        if ($url) {
            return $url;
        }

        $first = $profile['images'][0] ?? null;

        if (is_string($first) && $first !== '' && $first !== HotelOfferMapper::defaultHotelImage()) {
            return $first;
        }

        return HotelOfferMapper::defaultHotelImage();
    }

    /**
     * @return array<string, mixed>
     */
    protected function emptyProfile(string $code): array
    {
        return [
            'code' => $code,
            'name' => 'Hotel',
            'description' => '',
            'images' => [],
            'images_raw' => [],
            'facilities' => [],
            'reviews' => [],
            'issues' => [],
            'interest_points' => [],
            'nearby_locations' => [],
            'phones' => [],
            'check_in_time' => null,
            'check_out_time' => null,
            'address' => '',
            'latitude' => null,
            'longitude' => null,
            'category_code' => null,
            'destination' => '',
            'email' => null,
            'website' => null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function profileForHotel(int|string $hotelCode): array
    {
        $code = (string) $hotelCode;
        $cacheTtl = (int) config('hotels.hotelbeds.content_cache_ttl', 86400);
        $cacheKey = 'hotelbeds.profile.v3.' . $code;

        $cached = Cache::get($cacheKey);

        if (is_array($cached)) {
            return $cached;
        }

        try {
            $response = $this->api->getHotelDetails($code);
            $profile = HotelbedsProfileMapper::mapDetails($response, $code);
            Cache::put($cacheKey, $profile, $cacheTtl);

            return $profile;
        } catch (\Throwable $e) {
            Log::warning('Hotelbeds profile fetch failed', [
                'hotel_code' => $code,
                'error' => $e->getMessage(),
            ]);

            return $this->emptyProfile($code);
        }
    }

    /**
     * @return array<string, string> code => name
     */
    public function tanzaniaDestinationMap(): array
    {
        $cacheTtl = (int) config('hotels.hotelbeds.content_cache_ttl', 86400);

        return Cache::remember('hotelbeds.destinations.TZ.v1', $cacheTtl, function () {
            try {
                $map = [];
                $from = 1;
                $pageSize = 1000;

                do {
                    $response = $this->api->getDestinations('TZ', $from, $from + $pageSize - 1);
                    $batch = $response['destinations'] ?? [];

                    foreach ($batch as $dest) {
                        $code = strtoupper(trim((string) ($dest['code'] ?? '')));

                        if ($code === '') {
                            continue;
                        }

                        $country = strtoupper(trim((string) ($dest['countryCode'] ?? 'TZ')));

                        if ($country !== '' && $country !== 'TZ') {
                            continue;
                        }

                        $name = $dest['name']['content'] ?? $dest['name'] ?? $code;

                        if (is_array($name)) {
                            $name = $name['content'] ?? $code;
                        }

                        $map[$code] = trim((string) $name) ?: $code;
                    }

                    $from += $pageSize;
                } while (count($batch) >= $pageSize);

                return $map !== [] ? $map : $this->fallbackTanzaniaDestinations();
            } catch (\Throwable $e) {
                Log::warning('Hotelbeds Tanzania destinations fetch failed', [
                    'error' => $e->getMessage(),
                ]);

                return $this->fallbackTanzaniaDestinations();
            }
        });
    }

    /**
     * @return array<int, string>
     */
    public function tanzaniaDestinationCodes(): array
    {
        return array_keys($this->tanzaniaDestinationMap());
    }

    /**
     * @return array<string, array<string, string>>
     */
    public function destinationOptionsForSearch(): array
    {
        $map = $this->tanzaniaDestinationMap();
        $zanzibar = [];

        if (isset($map['ZNZ'])) {
            $zanzibar['ZNZ'] = $map['ZNZ'];
        }

        $mainland = collect($map)
            ->except(['ZNZ'])
            ->sortBy(fn (string $name) => $name, SORT_NATURAL | SORT_FLAG_CASE)
            ->all();

        return array_filter([
            'Tanzania & Zanzibar' => array_merge(
                ['TZ_ALL' => 'All Tanzania & Zanzibar'],
                $zanzibar
            ),
            'Tanzania (Mainland)' => $mainland,
        ], fn (array $options) => $options !== []);
    }

    public function syncAllowedDestinationCodes(): void
    {
        config(['hotels.allowed_destination_codes' => $this->tanzaniaDestinationCodes()]);
    }

    /**
     * Static hotel directory (no live rates) from Content API.
     *
     * @return array<int, array<string, mixed>>
     */
    public function browseHotels(?string $destination, int $limit = 200): array
    {
        $destination = strtoupper(trim((string) $destination));
        $cacheKey = 'hotelbeds.browse.v3.' . md5($destination . '.' . $limit);
        $cacheTtl = 3600;

        return Cache::remember($cacheKey, $cacheTtl, function () use ($destination, $limit) {
            try {
                if ($destination === '' || $destination === 'TZ_ALL') {
                    return $this->mapBrowseHotels(
                        $this->api->listHotels([
                            'countryCode' => 'TZ',
                            'from' => 1,
                            'to' => min($limit, 1000),
                        ])
                    );
                }

                return $this->mapBrowseHotels(
                    $this->api->listHotels([
                        'destinationCode' => $destination,
                        'from' => 1,
                        'to' => min($limit, 1000),
                    ])
                );
            } catch (\Throwable $e) {
                Log::warning('Hotelbeds browse hotels failed', [
                    'destination' => $destination,
                    'error' => $e->getMessage(),
                ]);

                return [];
            }
        });
    }

    /**
     * @param  array<string, mixed>  $response
     * @return array<int, array<string, mixed>>
     */
    protected function mapBrowseHotels(array $response): array
    {
        $mapped = [];

        foreach ($response['hotels'] ?? [] as $hotel) {
            if (! is_array($hotel)) {
                continue;
            }

            $code = (string) ($hotel['code'] ?? '');

            if ($code === '') {
                continue;
            }

            $destinationName = $hotel['destination']['name']['content']
                ?? $hotel['destination']['name']
                ?? $hotel['destination']['code']
                ?? '';

            if (is_array($destinationName)) {
                $destinationName = $destinationName['content'] ?? '';
            }

            $latitude = $hotel['coordinates']['latitude'] ?? $hotel['latitude'] ?? null;
            $longitude = $hotel['coordinates']['longitude'] ?? $hotel['longitude'] ?? null;
            $rawImages = is_array($hotel['images'] ?? null) ? $hotel['images'] : [];
            $imageUrl = HotelOfferMapper::pickHotelbedsImage($rawImages);

            $mapped[] = [
                'hotel_code' => $code,
                'hotel_name' => trim((string) ($hotel['name']['content'] ?? $hotel['name'] ?? 'Hotel')) ?: 'Hotel',
                'destination_name' => trim((string) $destinationName),
                'category_code' => isset($hotel['categoryCode']) ? (string) $hotel['categoryCode'] : null,
                'latitude' => $latitude !== null ? (string) $latitude : null,
                'longitude' => $longitude !== null ? (string) $longitude : null,
                'image_url' => $imageUrl,
                'price' => 0,
                'currency' => 'USD',
                'browse_only' => true,
            ];
        }

        return $mapped;
    }

    /**
     * @return array<string, string>
     */
    protected function fallbackTanzaniaDestinations(): array
    {
        $options = config('hotels.destination_options', []);
        $flat = [];

        foreach ($options as $group) {
            foreach ($group as $code => $label) {
                if ($code !== 'TZ_ALL') {
                    $flat[$code] = $label;
                }
            }
        }

        return $flat !== [] ? $flat : ['ZNZ' => 'Zanzibar', 'DAR' => 'Dar es Salaam'];
    }
}
