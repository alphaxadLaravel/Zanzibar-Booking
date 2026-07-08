<?php

namespace App\Repositories;

use App\Models\FlightSearch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class FlightSearchRepository
{
    public function create(array $data): FlightSearch
    {
        return FlightSearch::create($data);
    }

    public function query(array $filters = []): Builder
    {
        $query = FlightSearch::query()->with('user');

        if (! empty($filters['search'])) {
            $term = '%' . $filters['search'] . '%';
            $query->where(function (Builder $q) use ($term) {
                $q->where('origin_code', 'like', $term)
                    ->orWhere('destination_code', 'like', $term)
                    ->orWhere('origin_name', 'like', $term)
                    ->orWhere('destination_name', 'like', $term)
                    ->orWhere('country', 'like', $term)
                    ->orWhereHas('user', fn (Builder $uq) => $uq->where('name', 'like', $term)->orWhere('email', 'like', $term));
            });
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (! empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        if (! empty($filters['origin'])) {
            $query->where('origin_code', strtoupper($filters['origin']));
        }

        if (! empty($filters['destination'])) {
            $query->where('destination_code', strtoupper($filters['destination']));
        }

        if (($filters['user_type'] ?? '') === 'authenticated') {
            $query->whereNotNull('user_id');
        }

        if (($filters['user_type'] ?? '') === 'guest') {
            $query->whereNull('user_id');
        }

        $sort = $filters['sort'] ?? 'created_at';
        $direction = $filters['direction'] ?? 'desc';
        $allowed = ['created_at', 'origin_code', 'destination_code', 'departure_date', 'country'];

        if (! in_array($sort, $allowed, true)) {
            $sort = 'created_at';
        }

        return $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
    }

    public function paginate(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        return $this->query($filters)->paginate($perPage)->withQueryString();
    }

    public function deleteByIds(array $ids): int
    {
        return FlightSearch::whereIn('id', $ids)->delete();
    }
}
