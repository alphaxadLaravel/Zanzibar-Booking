<?php

namespace App\Mail;

use App\Models\FlightBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlightTicketConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FlightBooking $booking,
        public array $ticket,
        public bool $forAdmin = false,
    ) {}

    public function envelope(): Envelope
    {
        $pnr = $this->ticket['airline_pnr'] ?? null;
        $subject = $this->forAdmin
            ? 'New flight booking — ' . $this->booking->booking_reference
            : 'Your flight ticket — ' . $this->booking->booking_reference . ($pnr ? ' (PNR ' . $pnr . ')' : '');

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.flight-ticket-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
