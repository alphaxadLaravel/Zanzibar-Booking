<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Near extends Model
{
    protected $fillable = [
        'deal_id',
        'near_id',
        'type'
    ];

    // Relationships
    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function nearDeal(): BelongsTo
    {
        return $this->belongsTo(Deal::class, 'near_id');
    }
}
