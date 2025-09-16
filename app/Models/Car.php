<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    protected $fillable = [
        'deal_id',
        'capacity',
        'transmission',
        'fuel',
        'air_condition',
        'gps',
        'terms'
    ];

    protected $casts = [
        'air_condition' => 'boolean',
        'gps' => 'boolean'
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}
