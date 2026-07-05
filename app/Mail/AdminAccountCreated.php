<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    public User $admin;

    public string $plainPassword;

    public string $loginUrl;

    public string $dashboardUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $admin, string $plainPassword)
    {
        $this->admin = $admin;
        $this->plainPassword = $plainPassword;
        $this->loginUrl = route('login');
        $this->dashboardUrl = route('dashboard');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Zanzibar Bookings Admin Account',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-account-created',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
