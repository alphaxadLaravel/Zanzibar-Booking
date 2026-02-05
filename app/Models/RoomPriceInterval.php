<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomPriceInterval extends Model
{
    protected $fillable = [
        'room_id',
        'start_date',
        'end_date',
        'price',
        'label',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price' => 'decimal:2',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Check if a given date falls within this interval
     */
    public function containsDate(\DateTimeInterface $date): bool
    {
        $d = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
        return $d->between($this->start_date, $this->end_date);
    }
}
