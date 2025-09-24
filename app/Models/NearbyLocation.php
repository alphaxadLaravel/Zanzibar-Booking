<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NearbyLocation extends Model
{
    protected $fillable = [
        'deal_id',
        'title',
        'category',
        'distance_km',
        'is_active'
    ];

    protected $casts = [
        'distance_km' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    // Relationships
    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Accessor for formatted distance
    public function getFormattedDistanceAttribute()
    {
        if ($this->distance_km < 1) {
            return round($this->distance_km * 1000) . ' m';
        }
        return round($this->distance_km, 1) . ' km';
    }

    // Static method to get available categories
    public static function getAvailableCategories()
    {
        return [
            'Airport' => 'Airport',
            'Beach' => 'Beach',
            'School' => 'School',
            'Hospital' => 'Hospital',
            'Shopping Center' => 'Shopping Center',
            'Restaurant' => 'Restaurant',
            'Bank' => 'Bank',
            'ATM' => 'ATM',
            'Gas Station' => 'Gas Station',
            'Bus Station' => 'Bus Station',
            'Train Station' => 'Train Station',
            'Tourist Attraction' => 'Tourist Attraction',
            'Market' => 'Market',
            'Pharmacy' => 'Pharmacy',
            'Police Station' => 'Police Station',
            'Post Office' => 'Post Office',
            'Gym' => 'Gym',
            'Park' => 'Park',
            'Mosque' => 'Mosque',
            'Church' => 'Church',
            'Other' => 'Other'
        ];
    }
}