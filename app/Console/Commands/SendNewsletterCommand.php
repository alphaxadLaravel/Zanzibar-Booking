<?php

namespace App\Console\Commands;

use App\Helpers\NewsletterHelper;
use Illuminate\Console\Command;

class SendNewsletterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send 
                            {subject : The email subject}
                            {--content= : The email content HTML}
                            {--file= : Path to HTML file containing email content}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send newsletter to all subscribed users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subject = $this->argument('subject');
        
        // Get content from option or file
        $content = $this->option('content');
        if (!$content && $this->option('file')) {
            $filePath = $this->option('file');
            if (file_exists($filePath)) {
                $content = file_get_contents($filePath);
            } else {
                $this->error('File not found: ' . $filePath);
                return 1;
            }
        }

        if (!$content) {
            $this->error('Please provide email content using --content or --file option');
            return 1;
        }

        $this->info('Sending newsletter: ' . $subject);
        $this->info('This may take a while...');

        $result = NewsletterHelper::sendNewsletter($subject, $content);

        $this->info('Newsletter sent!');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Subscribers', $result['total']],
                ['Sent Successfully', $result['success']],
                ['Failed', $result['failed']],
            ]
        );

        return 0;
    }
}

