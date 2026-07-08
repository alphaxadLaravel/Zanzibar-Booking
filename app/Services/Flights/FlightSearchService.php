<?php

namespace App\Services\Flights;

use App\DTOs\FlightSearchCriteria;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class FlightSearchService
{
    public function __construct(
        protected FlightProviderManager $providers,
        protected AffiliateTrackingService $tracking,
    ) {}

    /**
     * Fetch offers from the provider (cached). Does not log or persist session.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fetch(FlightSearchCriteria $criteria): array
    {
        $cacheTtl = (int) config('flights.cache_ttl', 300);
        $cacheKey = $criteria->cacheKey();

        $offers = Cache::remember($cacheKey, $cacheTtl, function () use ($criteria) {
            return $this->providers->driver()->search($criteria);
        });

        return array_map(fn ($offer) => $offer->toArray(), $offers);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function search(FlightSearchCriteria $criteria, bool $logSearch = true): array
    {
        $results = $this->fetch($criteria);

        if ($logSearch) {
            try {
                $search = $this->tracking->logSearch($criteria, count($results));
                session(['last_flight_search_id' => $search->id]);
            } catch (\Throwable $e) {
                Log::warning('Failed to log flight search', ['message' => $e->getMessage()]);
            }
        }

        session([
            'flight_search_results' => $results,
            'flight_search_criteria' => $criteria->toArray(),
        ]);

        return $results;
    }

    /**
     * Load featured flights across priority routes (cached bundle, parallel API calls).
     *
     * @return array<int, array<string, mixed>>
     */
    public function searchFeaturedFlights(?string $departureDate = null): array
    {
        $departureDate = $departureDate ?: now()
            ->addDays((int) config('flights.featured.days_ahead', 7))
            ->format('Y-m-d');

        $perRoute = (int) config('flights.featured.per_route', 2);
        $routes = array_slice(
            config('flights.featured_routes', []),
            0,
            (int) config('flights.featured.max_routes', 8)
        );
        $bundleTtl = (int) config('flights.featured.cache_ttl', 1800);
        $cacheKey = 'flights.featured.v3.' . md5($departureDate . '|' . $perRoute . '|' . json_encode($routes));

        $cached = Cache::get($cacheKey);
        if (is_array($cached) && ! empty($cached)) {
            return $cached;
        }

        $flights = $this->buildFeaturedFlights($departureDate, $perRoute, $routes);
        Cache::put($cacheKey, $flights, empty($flights) ? 60 : $bundleTtl);

        return $flights;
    }

    /**
     * @param  array<int, array{0: string, 1: string}>  $routes
     * @return array<int, array<string, mixed>>
     */
    protected function buildFeaturedFlights(string $departureDate, int $perRoute, array $routes): array
    {
        $cacheTtl = (int) config('flights.cache_ttl', 300);
        $criteriaByRoute = [];
        $cachedByRoute = [];

        foreach ($routes as $route) {
            [$origin, $destination] = $route;
            $routeKey = $origin . '|' . $destination;

            $criteria = FlightSearchCriteria::fromArray([
                'tripType' => 'one_way',
                'origin' => $origin,
                'destination' => $destination,
                'departureDate' => $departureDate,
                'adults' => 1,
                'max' => $perRoute,
            ]);

            $criteriaByRoute[$routeKey] = $criteria;
            $routeCacheKey = $criteria->cacheKey();

            if (Cache::has($routeCacheKey)) {
                $cachedByRoute[$routeKey] = Cache::get($routeCacheKey);
            }
        }

        $uncachedCriteria = collect($criteriaByRoute)
            ->reject(fn ($criteria, $routeKey) => array_key_exists($routeKey, $cachedByRoute))
            ->all();

        if (! empty($uncachedCriteria)) {
            $provider = $this->providers->driver();
            $fetched = $provider->searchParallel($uncachedCriteria);

            foreach ($fetched as $routeKey => $offers) {
                $arrays = array_map(fn ($offer) => $offer->toArray(), $offers);
                Cache::put($uncachedCriteria[$routeKey]->cacheKey(), $arrays, $cacheTtl);
                $cachedByRoute[$routeKey] = $arrays;
            }
        }

        $flights = [];

        foreach ($routes as $route) {
            [$origin, $destination] = $route;
            $routeKey = $origin . '|' . $destination;
            $routeFlights = $cachedByRoute[$routeKey] ?? [];

            foreach ($routeFlights as $flight) {
                $flight['route_label'] = $origin . ' → ' . $destination;
                $flights[] = $flight;
            }
        }

        return collect($flights)->sortBy('price')->values()->all();
    }

    /**
     * @param  array<string, FlightSearchCriteria>  $criteriaByRoute
     * @return array<string, array<int, \App\DTOs\FlightOffer>>
     */
    protected function searchSequential(array $criteriaByRoute): array
    {
        $results = [];

        foreach ($criteriaByRoute as $routeKey => $criteria) {
            try {
                $results[$routeKey] = $this->providers->driver()->search($criteria);
            } catch (\Throwable $e) {
                Log::warning('Featured flight route failed', [
                    'route' => $routeKey,
                    'message' => $e->getMessage(),
                ]);
                $results[$routeKey] = [];
            }
        }

        return $results;
    }

    /** @deprecated Use searchFeaturedFlights() */
    public function searchTanzaniaFeatured(?string $departureDate = null): array
    {
        return $this->searchFeaturedFlights($departureDate);
    }
}
