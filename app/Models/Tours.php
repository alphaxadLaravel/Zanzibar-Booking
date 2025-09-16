<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tours extends Model
{
    protected $fillable = [
        'deal_id',
        'period',
        'max_people',
        'adult_price',
        'child_price'
    ];

    protected $casts = [
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2'
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}
