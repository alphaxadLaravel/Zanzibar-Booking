<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightClick extends Model
{
    protected $fillable = [
        'flight_search_id',
        'user_id',
        'airline',
        'flight_number',
        'origin',
        'destination',
        'price',
        'currency',
        'affiliate_name',
        'affiliate_url',
        'clicked_at',
        'ip_address',
        'country',
        'device',
        'browser',
        'operating_system',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'clicked_at' => 'datetime',
    ];

    public function flightSearch(): BelongsTo
    {
        return $this->belongsTo(FlightSearch::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
