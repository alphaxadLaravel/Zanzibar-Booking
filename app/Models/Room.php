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