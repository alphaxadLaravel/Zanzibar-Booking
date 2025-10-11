<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightBooking extends Model
{
    protected $fillable = [
        'booking_reference',
        'user_id',
        'payment_id',
        'flight_number',
        'airline_code',
        'airline_name',
        'origin_code',
        'origin_name',
        'destination_code',
        'destination_name',
        'departure_datetime',
        'arrival_datetime',
        'duration',
        'aircraft',
        'stops',
        'status',
        'travel_class',
        'adults',
        'children',
        'infants',
        'base_price',
        'taxes',
        'total_price',
        'currency',
        'flight_offer',
        'amadeus_order_id',
        'amadeus_response',
        'contact_email',
        'contact_phone',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'departure_datetime' => 'datetime',
        'arrival_datetime' => 'datetime',
        'flight_offer' => 'array',
        'amadeus_response' => 'array',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'base_price' => 'decimal:2',
        'taxes' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Generate a unique booking reference
     */
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'ZB' . strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
        } while (self::where('booking_reference', $reference)->exists());

        return $reference;
    }

    /**
     * Get the user that made the booking
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment associated with the booking
     */
    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * Get the passengers for the booking
     */
    public function passengers(): HasMany
    {
        return $this->hasMany(FlightPassenger::class);
    }

    /**
     * Scope a query to only include confirmed bookings
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    /**
     * Scope a query to only include pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Mark booking as confirmed
     */
    public function markAsConfirmed()
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);
    }

    /**
     * Mark booking as cancelled
     */
    public function markAsCancelled()
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }
}
