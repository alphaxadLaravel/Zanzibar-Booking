<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Features extends Model
{
    protected $fillable = [
        'name',
        'icon',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Scope for active features
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
