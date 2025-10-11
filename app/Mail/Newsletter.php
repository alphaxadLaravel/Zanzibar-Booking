<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Newsletter extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $content;
    public $unsubscribeUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $content, $email = null)
    {
        $this->subject = $subject;
        $this->content = $content;
        
        // Generate unsubscribe URL
        $this->unsubscribeUrl = $email ? route('newsletter.unsubscribe', ['email' => base64_encode($email)]) : '#';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.newsletter',
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

