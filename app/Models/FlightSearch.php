<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightSearch extends Model
{
    protected $fillable = [
        'user_id',
        'origin_code',
        'origin_name',
        'destination_code',
        'destination_name',
        'departure_date',
        'return_date',
        'adults',
        'children',
        'infants',
        'travel_class',
        'results_count',
        'session_id',
        'ip_address',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Get the user that performed the search
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include searches by authenticated users
     */
    public function scopeAuthenticated($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope a query to only include guest searches
     */
    public function scopeGuest($query)
    {
        return $query->whereNull('user_id');
    }
}
