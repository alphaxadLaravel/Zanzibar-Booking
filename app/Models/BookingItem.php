<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingItem extends Model
{
    protected $fillable = [
        'user_id',
        'deal_id',
        'room_id',
        'number_rooms',
        'type',
        'check_in',
        'check_out',
        'total_price',
        'adults',
        'children',
        'status'
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'total_price' => 'decimal:2',
        'number_rooms' => 'integer', // Can be null for activities/packages/cars
        'adults' => 'integer',
        'children' => 'integer'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
