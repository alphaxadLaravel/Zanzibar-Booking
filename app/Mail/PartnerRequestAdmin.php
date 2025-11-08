<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerRequestAdmin extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public ?string $businessName;
    public ?string $notes;
    public ?string $previousRole;
    public string $approveUrl;
    public string $rejectUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, ?string $businessName = null, ?string $notes = null, ?string $previousRole = null, string $approveUrl = '#', string $rejectUrl = '#')
    {
        $this->user = $user;
        $this->businessName = $businessName;
        $this->notes = $notes;
        $this->previousRole = $previousRole;
        $this->approveUrl = $approveUrl;
        $this->rejectUrl = $rejectUrl;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Partner Request Submitted',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.partner-request-admin',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}


