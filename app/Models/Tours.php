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
        'child_price',
        'is_group_package',
        'group_max_capacity',
        'group_booking_deadline',
        'group_departure_date',
    ];

    protected $casts = [
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'is_group_package' => 'boolean',
        'group_booking_deadline' => 'date',
        'group_departure_date' => 'date',
    ];

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function isGroupPackage(): bool
    {
        return (bool) $this->is_group_package;
    }
}
