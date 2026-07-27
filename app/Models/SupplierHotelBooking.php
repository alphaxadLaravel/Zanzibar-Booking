<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierHotelBooking extends Model
{
    protected $fillable = [
        'booking_reference',
        'user_id',
        'payment_id',
        'supplier',
        'hotel_code',
        'hotel_name',
        'destination_code',
        'destination_name',
        'check_in',
        'check_out',
        'room_name',
        'board_name',
        'rooms',
        'adults',
        'children',
        'supplier_cost',
        'markup_amount',
        'total_price',
        'currency',
        'rate_key',
        'supplier_payload',
        'supplier_booking_ref',
        'supplier_response',
        'status',
        'contact_email',
        'contact_phone',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'check_in' => 'date',
        'check_out' => 'date',
        'supplier_payload' => 'array',
        'supplier_response' => 'array',
        'supplier_cost' => 'decimal:2',
        'markup_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    public static function generateBookingReference(): string
    {
        do {
            $reference = 'ZH' . strtoupper(substr(md5(uniqid((string) rand(), true)), 0, 6));
        } while (self::where('booking_reference', $reference)->exists());

        return $reference;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function markAsConfirmed(?string $supplierRef = null, ?array $response = null): void
    {
        $this->update([
            'status' => 'confirmed',
            'supplier_booking_ref' => $supplierRef ?? $this->supplier_booking_ref,
            'supplier_response' => $response ?? $this->supplier_response,
            'confirmed_at' => now(),
        ]);
    }

    public function markAsFailed(?array $response = null): void
    {
        $this->update([
            'status' => 'failed',
            'supplier_response' => $response ?? $this->supplier_response,
        ]);
    }

    /**
     * @return array{customer_paid: float, supplier_cost: float, margin: float, currency: string}
     */
    public function paymentDistribution(): array
    {
        $payload = $this->supplier_payload ?? [];
        $pricing = $payload['_pricing'] ?? [];

        $customerPaid = (float) ($this->total_price ?? $pricing['customer_total'] ?? 0);
        $supplierCost = (float) ($this->supplier_cost ?? $pricing['supplier_total'] ?? 0);
        $margin = (float) ($this->markup_amount ?? $pricing['markup'] ?? max(0, $customerPaid - $supplierCost));

        return [
            'customer_paid' => $customerPaid,
            'supplier_cost' => $supplierCost,
            'margin' => $margin,
            'currency' => strtoupper($this->currency ?? 'USD'),
        ];
    }
}
