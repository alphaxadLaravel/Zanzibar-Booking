<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Booking extends Model
{
    protected $fillable = [
        'booking_code',
        'fullname',
        'email',
        'country',
        'phone',
        'user_id',
        'booking_items',
        'total_amount',
        'payment_method',
        'status',
        'additional_notes'
    ];

    protected $casts = [
        'booking_items' => 'array',
        'total_amount' => 'decimal:2'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function bookingItems(): HasMany
    {
        return $this->hasMany(BookingItem::class);
    }

    // Helper method to get first booking item's deal
    public function getDealAttribute()
    {
        $items = $this->booking_items;
        if (is_array($items) && !empty($items)) {
            $firstItem = $items[0];
            if (isset($firstItem['deal_id'])) {
                return Deal::find($firstItem['deal_id']);
            }
        }
        
        // Fallback to BookingItem relationship
        $bookingItem = $this->bookingItems()->first();
        return $bookingItem ? $bookingItem->deal : null;
    }

    // Helper method for total price (alias for total_amount)
    public function getTotalPriceAttribute()
    {
        return $this->total_amount;
    }

    // Helper method to check if booking has payment
    public function hasPayment()
    {
        return $this->payments()->exists();
    }

    // Helper method to get latest payment
    public function latestPayment()
    {
        return $this->payments()->latest()->first();
    }

    // Add payment_status attribute
    public function getPaymentStatusAttribute()
    {
        $payment = $this->latestPayment();
        return $payment && $payment->status === 'COMPLETED';
    }

    public function setPaymentStatusAttribute($value)
    {
        // This is handled through payments table
        if ($value) {
            $this->status = 'confirmed';
        }
    }

    // Boot method to generate booking code
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $booking->booking_code = static::generateBookingCode();
            }
        });
    }

    // Generate unique booking code
    public static function generateBookingCode()
    {
        do {
            $code = 'ZBOOK-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (static::where('booking_code', $code)->exists());

        return $code;
    }

    // Helper method to get booking items
    public function getBookingItems()
    {
        return $this->booking_items ?? [];
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }
}
