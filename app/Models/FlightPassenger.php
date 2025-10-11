<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightPassenger extends Model
{
    protected $fillable = [
        'flight_booking_id',
        'type',
        'title',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality',
        'passport_number',
        'passport_country',
        'passport_expiry',
        'email',
        'phone',
        'meal_preference',
        'seat_preference',
        'special_requirements',
        'frequent_flyer_number',
        'frequent_flyer_airline',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
    ];

    /**
     * Get the booking that owns the passenger
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(FlightBooking::class, 'flight_booking_id');
    }

    /**
     * Get full name of passenger
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->title} {$this->first_name} {$this->last_name}");
    }

    /**
     * Scope a query to only include adult passengers
     */
    public function scopeAdults($query)
    {
        return $query->where('type', 'adult');
    }

    /**
     * Scope a query to only include child passengers
     */
    public function scopeChildren($query)
    {
        return $query->where('type', 'child');
    }

    /**
     * Scope a query to only include infant passengers
     */
    public function scopeInfants($query)
    {
        return $query->where('type', 'infant');
    }
}
