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
     * @return array<string, string>
     */
    public function imagesForHotels(array $hotelCodes): array
    {
        $images = [];

        foreach (array_unique(array_filter($hotelCodes)) as $code) {
            $profile = $this->profileForHotel($code);
            $images[(string) $code] = $profile['images'][0] ?? HotelOfferMapper::defaultHotelImage();
        }

        return $images;
    }

    public function imageForHotel(int|string $hotelCode): string
    {
        $profile = $this->profileForHotel($hotelCode);

        return $profile['images'][0] ?? HotelOfferMapper::defaultHotelImage();
    }

    /**
     * @return array<string, mixed>
     */
    public function profileForHotel(int|string $hotelCode): array
    {
        $code = (string) $hotelCode;
        $cacheTtl = (int) config('hotels.hotelbeds.content_cache_ttl', 86400);
        $cacheKey = 'hotelbeds.profile.' . $code;

        return Cache::remember($cacheKey, $cacheTtl, function () use ($code) {
            try {
                $response = $this->api->getHotelDetails($code);

                return HotelbedsProfileMapper::mapDetails($response, $code);
            } catch (\Throwable $e) {
                Log::warning('Hotelbeds profile fetch failed', [
                    'hotel_code' => $code,
                    'error' => $e->getMessage(),
                ]);

                return [
                    'code' => $code,
                    'name' => 'Hotel',
                    'description' => '',
                    'images' => [HotelOfferMapper::defaultHotelImage()],
                    'facilities' => [],
                    'address' => '',
                    'latitude' => null,
                    'longitude' => null,
                    'category_code' => null,
                    'destination' => '',
                    'email' => null,
                    'website' => null,
                ];
            }
        });
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
