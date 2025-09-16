<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DealPhotos extends Model
{
    protected $fillable = [
        'deal_id',
        'photo'
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }
}
