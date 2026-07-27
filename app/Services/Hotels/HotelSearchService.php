<?php

namespace App\Services\Hotels;

use App\DTOs\HotelOffer;
use App\DTOs\HotelSearchCriteria;
use Illuminate\Support\Facades\Cache;

class HotelSearchService
{
    public function __construct(
        protected HotelProviderManager $providers,
    ) {}

    /**
     * @return array<int, array<string, mixed>>
     */
    public function fetch(HotelSearchCriteria $criteria): array
    {
        if (! config('hotels.enabled', true)) {
            throw new \RuntimeException('Partner hotel search is currently unavailable.');
        }

        $cacheTtl = (int) config('hotels.cache_ttl', 300);
        $cacheKey = $criteria->cacheKey();

        $offers = Cache::remember($cacheKey, $cacheTtl, function () use ($criteria) {
            return $this->providers->driver()->search($criteria);
        });

        return array_map(fn (HotelOffer $offer) => $offer->toArray(), $offers);
    }

    /**
     * Search and persist results in session for checkout flows.
     *
     * @return array<int, array<string, mixed>>
     */
    public function search(HotelSearchCriteria $criteria): array
    {
        $results = $this->fetch($criteria);

        session([
            'hotel_search_results' => $results,
            'hotel_search_criteria' => $criteria->toArray(),
        ]);

        return $results;
    }

    /**
     * Group flat rate offers into one card per hotel (cheapest rate).
     *
     * @param  array<int, array<string, mixed>>  $offers
     * @return array<int, array<string, mixed>>
     */
    public function groupByHotel(array $offers): array
    {
        $grouped = [];

        foreach ($offers as $offer) {
            $code = (string) ($offer['hotel_code'] ?? '');

            if ($code === '') {
                continue;
            }

            if (! isset($grouped[$code])) {
                $grouped[$code] = array_merge($offer, ['rates_count' => 1]);

                continue;
            }

            $grouped[$code]['rates_count'] = ($grouped[$code]['rates_count'] ?? 1) + 1;

            if ((float) $offer['price'] < (float) $grouped[$code]['price']) {
                $count = $grouped[$code]['rates_count'];
                $grouped[$code] = array_merge($offer, ['rates_count' => $count]);
            }
        }

        return collect($grouped)
            ->sortBy('price')
            ->values()
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function ratesForHotel(string $hotelCode): array
    {
        $results = session('hotel_search_results', []);

        return collect($results)
            ->filter(fn (array $offer) => (string) ($offer['hotel_code'] ?? '') === $hotelCode)
            ->sortBy('price')
            ->values()
            ->all();
    }
}
