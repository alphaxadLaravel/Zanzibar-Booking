<?php

namespace App\Services\Hotels;

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
            $images[(string) $code] = $this->imageForHotel($code);
        }

        return $images;
    }

    public function imageForHotel(int|string $hotelCode): string
    {
        $code = (string) $hotelCode;
        $cacheTtl = (int) config('hotels.hotelbeds.content_cache_ttl', 86400);
        $cacheKey = 'hotelbeds.image.' . $code;

        return Cache::remember($cacheKey, $cacheTtl, function () use ($code) {
            try {
                $response = $this->api->getHotelDetails($code);
                $hotelImages = $response['hotel']['images'] ?? $response['images'] ?? [];

                return HotelOfferMapper::pickHotelbedsImage($hotelImages)
                    ?? HotelOfferMapper::defaultHotelImage();
            } catch (\Throwable $e) {
                Log::warning('Hotelbeds image fetch failed', [
                    'hotel_code' => $code,
                    'error' => $e->getMessage(),
                ]);

                return HotelOfferMapper::defaultHotelImage();
            }
        });
    }
}
