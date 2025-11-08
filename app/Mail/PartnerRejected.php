<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerRejected extends Mailable
{
    use Queueable, SerializesModels;

    public User $partner;

    public function __construct(User $partner)
    {
        $this->partner = $partner;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Partner Request Update - Zanzibar Bookings',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner-rejected',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


