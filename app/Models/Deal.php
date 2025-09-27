<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Deal extends Model
{
    protected $fillable = [
        'title',
        'cover_photo',
        'location',
        'lat',
        'long',
        'map_location',
        'base_price',
        'ratings',
        'star_rating',
        'is_featured',
        'category_id',
        'description',
        'policies',
        'type',
        'user_id',
        'status',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_image',
        'video_link'
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'long' => 'decimal:7',
        'base_price' => 'decimal:2',
        'ratings' => 'float',
        'star_rating' => 'integer',
        'is_featured' => 'boolean',
        'status' => 'boolean'
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Features::class, 'deal_features', 'deal_id', 'feature_id');
    }

    public function photos(): HasMany
    {
        return $this->hasMany(DealPhotos::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function tour(): HasOne
    {
        return $this->hasOne(Tours::class);
    }

    public function tours(): HasOne
    {
        return $this->hasOne(Tours::class);
    }

    public function car(): HasOne
    {
        return $this->hasOne(Car::class);
    }

    public function tourIncludes(): HasMany
    {
        return $this->hasMany(TourInclude::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(TourItenary::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', 1);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function nears(): HasMany
    {
        return $this->hasMany(Near::class, 'deal_id');
    }

    public function nearbyDeals(): HasMany
    {
        return $this->hasMany(Near::class, 'near_id');
    }

    public function nearbyLocations(): HasMany
    {
        return $this->hasMany(NearbyLocation::class);
    }

    public function activeNearbyLocations(): HasMany
    {
        return $this->hasMany(NearbyLocation::class)->active();
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(DealReviews::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(DealReviews::class)->approved();
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // Get average rating
    public function getAverageRatingAttribute()
    {
        return $this->approvedReviews()->avg('rating') ?: 0;
    }

    // Get total reviews count
    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }
}
