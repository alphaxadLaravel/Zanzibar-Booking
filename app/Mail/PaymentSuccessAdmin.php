<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentSuccessAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $payment;
    public $bookingItems;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, Payment $payment)
    {
        $this->booking = $booking;
        $this->payment = $payment;
        
        // Load booking items with deal and room details
        $this->bookingItems = [];
        if ($booking->booking_items) {
            foreach ($booking->booking_items as $item) {
                $deal = null;
                $room = null;
                
                if (isset($item['deal_id'])) {
                    $deal = \App\Models\Deal::with(['category', 'photos'])->find($item['deal_id']);
                }
                
                if (isset($item['room_id'])) {
                    $room = \App\Models\Room::find($item['room_id']);
                }

                $this->bookingItems[] = [
                    'deal' => $deal,
                    'room' => $room,
                    'item_data' => $item
                ];
            }
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Received - ' . $this->booking->booking_code,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-success-admin',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}

