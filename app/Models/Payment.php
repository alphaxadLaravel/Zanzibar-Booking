<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'reference',
        'payment_method',
        'status',
        'transactionid',
        'trackingid'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    // Relationships
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // Boot method to generate reference
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->reference)) {
                $payment->reference = static::generateReference();
            }
        });
    }

    // Generate unique payment reference
    public static function generateReference()
    {
        do {
            $reference = 'PAY' . strtoupper(Str::random(10));
        } while (static::where('reference', $reference)->exists());

        return $reference;
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'FAILED');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'CANCELLED');
    }
}
