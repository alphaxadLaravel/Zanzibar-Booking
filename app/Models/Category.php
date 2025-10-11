<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'category',
        'type',
        'image',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    // Scope for active categories
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Scope for filtering by type
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Relationship with deals
    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class);
    }
}
