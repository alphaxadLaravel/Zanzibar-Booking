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
        'is_featured',
        'category_id',
        'description',
        'policies',
        'type',
        'user_id',
        'status'
    ];

    protected $casts = [
        'lat' => 'decimal:7',
        'long' => 'decimal:7',
        'base_price' => 'decimal:2',
        'ratings' => 'float',
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
}
