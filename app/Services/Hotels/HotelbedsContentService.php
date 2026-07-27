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
}
