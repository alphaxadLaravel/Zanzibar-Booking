<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourInclude extends Model
{
    protected $fillable = [
        'deal_id',
        'feature_id',
        'type'
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function feature(): BelongsTo
    {
        return $this->belongsTo(Features::class);
    }
}
