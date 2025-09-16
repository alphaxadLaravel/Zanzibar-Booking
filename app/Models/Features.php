<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'type',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relationships
    public function deals()
    {
        return $this->belongsToMany(Deal::class, 'deal_features', 'feature_id', 'deal_id');
    }

    // Scope for active features
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
