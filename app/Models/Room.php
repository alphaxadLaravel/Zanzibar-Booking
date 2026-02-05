<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'deal_id',
        'title',
        'number_of_rooms',
        'price',
        'price_type',
        'price_per_person',
        'people',
        'beds',
        'availability',
        'cover_photo',
        'description',
        'status'
    ];

    protected $casts = [
        'availability' => 'boolean',
        'status' => 'boolean',
        'price' => 'decimal:2',
        'price_per_person' => 'decimal:2',
        'number_of_rooms' => 'integer',
        'people' => 'integer',
        'beds' => 'integer'
    ];

    // Relationships
    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(RoomPhotos::class);
    }

    public function priceIntervals(): HasMany
    {
        return $this->hasMany(RoomPriceInterval::class)->orderBy('start_date');
    }

    /**
     * Get minimum price (base or from intervals) for display
     */
    public function getMinPrice(): float
    {
        return app(\App\Services\RoomPriceService::class)->getMinPrice($this);
    }

    /**
     * Get price unit label for display
     */
    public function getPriceUnitLabel(): string
    {
        return ($this->price_type ?? 'per_night') === 'per_person_per_night' ? 'per person / night' : '/ night';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeAvailable($query)
    {
        return $query->where('availability', 1);
    }
}