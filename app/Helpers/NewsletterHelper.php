<?php

namespace App\Helpers;

use App\Mail\Newsletter as NewsletterMail;
use App\Models\Newsletter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NewsletterHelper
{
    /**
     * Send newsletter to all subscribed users
     *
     * @param string $subject
     * @param string $content HTML content
     * @return array
     */
    public static function sendNewsletter($subject, $content)
    {
        $subscribers = Newsletter::where('subscribed', true)->get();
        
        $successCount = 0;
        $failCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterMail($subject, $content, $subscriber->email));
                $successCount++;
                Log::info('Newsletter sent successfully', ['email' => $subscriber->email, 'subject' => $subject]);
            } catch (\Exception $e) {
                $failCount++;
                Log::error('Failed to send newsletter', [
                    'email' => $subscriber->email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'total' => $subscribers->count(),
            'success' => $successCount,
            'failed' => $failCount
        ];
    }

    /**
     * Send newsletter to specific emails
     *
     * @param array $emails
     * @param string $subject
     * @param string $content HTML content
     * @return array
     */
    public static function sendToSpecificEmails(array $emails, $subject, $content)
    {
        $successCount = 0;
        $failCount = 0;

        foreach ($emails as $email) {
            try {
                Mail::to($email)->send(new NewsletterMail($subject, $content, $email));
                $successCount++;
                Log::info('Newsletter sent successfully', ['email' => $email, 'subject' => $subject]);
            } catch (\Exception $e) {
                $failCount++;
                Log::error('Failed to send newsletter', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return [
            'total' => count($emails),
            'success' => $successCount,
            'failed' => $failCount
        ];
    }
}

