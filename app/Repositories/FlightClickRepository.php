<?php

namespace App\Repositories;

use App\Models\FlightClick;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FlightClickRepository
{
    public function query(array $filters = []): Builder
    {
        $query = FlightClick::query()->with(['user', 'flightSearch']);

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('airline', 'like', $term)
                    ->orWhere('flight_number', 'like', $term)
                    ->orWhere('origin', 'like', $term)
                    ->orWhere('destination', 'like', $term)
                    ->orWhere('affiliate_name', 'like', $term)
                    ->orWhereHas('user', fn (Builder $uq) => $uq->where('name', 'like', $term)->orWhere('email', 'like', $term));
            });
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('clicked_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('clicked_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        if (! empty($filters['airline'])) {
            $query->where('airline', 'like', '%' . $filters['airline'] . '%');
        }

        if (! empty($filters['origin'])) {
            $query->where('origin', strtoupper($filters['origin']));
        }

        if (! empty($filters['destination'])) {
            $query->where('destination', strtoupper($filters['destination']));
        }

        if (($filters['user_type'] ?? '') === 'authenticated') {
            $query->whereNotNull('user_id');
        }

        if (($filters['user_type'] ?? '') === 'guest') {
            $query->whereNull('user_id');
        }

        $sort = $filters['sort'] ?? 'clicked_at';
        $direction = $filters['direction'] ?? 'desc';
        $allowed = ['clicked_at', 'airline', 'origin', 'destination', 'price', 'country'];

        if (! in_array($sort, $allowed, true)) {
            $sort = 'clicked_at';
        }

        return $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
    }

    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->query($filters)->paginate($perPage)->withQueryString();
    }

    public function deleteByIds(array $ids): int
    {
        return FlightClick::whereIn('id', $ids)->delete();
    }

    public function dashboardStats(array $filters = []): array
    {
        $searchQuery = app(FlightSearchRepository::class)->query($filters);
        $clickQuery = $this->query($filters);

        $totalSearches = (clone $searchQuery)->count();
        $todaySearches = (clone $searchQuery)->whereDate('created_at', today())->count();
        $totalClicks = (clone $clickQuery)->count();
        $todayClicks = (clone $clickQuery)->whereDate('clicked_at', today())->count();

        $conversionRate = $totalSearches > 0 ? round(($totalClicks / $totalSearches) * 100, 2) : 0;

        $topDestination = (clone $searchQuery)
            ->select('destination_code', DB::raw('COUNT(*) as total'))
            ->groupBy('destination_code')
            ->orderByDesc('total')
            ->first();

        $topOrigin = (clone $searchQuery)
            ->select('origin_code', DB::raw('COUNT(*) as total'))
            ->groupBy('origin_code')
            ->orderByDesc('total')
            ->first();

        $topAirline = (clone $clickQuery)
            ->select('airline', DB::raw('COUNT(*) as total'))
            ->whereNotNull('airline')
            ->groupBy('airline')
            ->orderByDesc('total')
            ->first();

        return [
            'total_searches' => $totalSearches,
            'today_searches' => $todaySearches,
            'total_clicks' => $totalClicks,
            'today_clicks' => $todayClicks,
            'conversion_rate' => $conversionRate,
            'top_destination' => $topDestination?->destination_code ?? 'N/A',
            'top_origin' => $topOrigin?->origin_code ?? 'N/A',
            'top_airline' => $topAirline?->airline ?? 'N/A',
        ];
    }

    public function dailySeries(string $table, string $dateColumn, array $filters = [], int $days = 30): Collection
    {
        $repo = $table === 'flight_searches'
            ? app(FlightSearchRepository::class)->query($filters)
            : $this->query($filters);

        return $repo
            ->select(DB::raw('DATE(' . $dateColumn . ') as day'), DB::raw('COUNT(*) as total'))
            ->whereDate($dateColumn, '>=', now()->subDays($days - 1)->toDateString())
            ->groupBy('day')
            ->orderBy('day')
            ->get();
    }

    public function topGrouped(string $column, array $filters = [], int $limit = 10): Collection
    {
        return $this->query($filters)
            ->select($column, DB::raw('COUNT(*) as total'))
            ->whereNotNull($column)
            ->groupBy($column)
            ->orderByDesc('total')
            ->limit($limit)
            ->get();
    }

    public function monthlyTrends(array $filters = [], int $months = 12): Collection
    {
        return app(FlightSearchRepository::class)->query($filters)
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('COUNT(*) as searches'))
            ->where('created_at', '>=', now()->subMonths($months - 1)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}
