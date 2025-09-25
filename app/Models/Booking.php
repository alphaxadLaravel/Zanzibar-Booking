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
        'deal_id',
        'total_price',
        'room_id',
        'check_in',
        'check_out',
        'number_rooms',
        'adult',
        'children',
        'pickup_location',
        'return_location',
        'pickup_time',
        'return_time',
        'need_driver',
        'fullname',
        'email',
        'phone',
        'country',
        'user_id',
        'status',
        'payment_status'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'check_in' => 'date',
        'check_out' => 'date',
        'pickup_time' => 'datetime:H:i',
        'return_time' => 'datetime:H:i',
        'number_rooms' => 'integer',
        'adult' => 'integer',
        'children' => 'integer',
        'need_driver' => 'boolean',
        'payment_status' => 'boolean'
    ];

    // Relationships
    public function deal(): BelongsTo
    {
        return $this->belongsTo(Deal::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
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
            $code = 'BK' . strtoupper(Str::random(8));
        } while (static::where('booking_code', $code)->exists());

        return $code;
    }

    // Get the related item (hotel, tour, car, etc.)
    public function getRelatedItemAttribute()
    {
        return $this->deal;
    }

    // Calculate total price based on deal type
    public function calculateTotalPrice()
    {
        $deal = $this->deal;
        
        if (!$deal) {
            return 0;
        }

        switch ($deal->type) {
            case 'hotel':
            case 'apartment':
                return $this->calculateRoomPrice();
            case 'tour':
                return $this->calculateTourPrice();
            case 'car':
                return $this->calculateCarPrice();
            default:
                return $deal->base_price;
        }
    }

    private function calculateRoomPrice()
    {
        $nights = 1;
        if ($this->check_in && $this->check_out) {
            $checkIn = is_string($this->check_in) ? \Carbon\Carbon::parse($this->check_in) : $this->check_in;
            $checkOut = is_string($this->check_out) ? \Carbon\Carbon::parse($this->check_out) : $this->check_out;
            $nights = $checkIn->diffInDays($checkOut);
            if ($nights < 1) $nights = 1;
        }
        
        if ($this->room) {
            return $this->room->price * $this->number_rooms * $nights;
        }
        
        // Use hotel base price if no specific room selected
        return $this->deal->base_price * $this->number_rooms * $nights;
    }

    private function calculateTourPrice()
    {
        $tour = $this->deal->tour;
        if (!$tour) {
            return $this->deal->base_price;
        }

        $adultTotal = $this->adult * $tour->adult_price;
        $childTotal = $this->children * $tour->child_price;
        
        $baseTotal = $adultTotal + $childTotal;
        
        // If tour has duration (period), multiply by number of days
        if ($tour->period && $tour->period > 1) {
            return $baseTotal * $tour->period;
        }
        
        return $baseTotal;
    }

    private function calculateCarPrice()
    {
        // For cars, calculate based on rental duration
        $days = 1;
        if ($this->pickup_time && $this->return_time) {
            // Calculate days between pickup and return
            $pickup = is_string($this->pickup_time) ? \Carbon\Carbon::parse($this->pickup_time) : $this->pickup_time;
            $return = is_string($this->return_time) ? \Carbon\Carbon::parse($this->return_time) : $this->return_time;
            $days = $pickup->diffInDays($return);
            if ($days < 1) $days = 1;
        }
        
        return $this->deal->base_price * $days;
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
        return $query->where('payment_status', 1);
    }
}
