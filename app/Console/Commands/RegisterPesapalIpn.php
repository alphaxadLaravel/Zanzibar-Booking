<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Knox\Pesapal\Facades\Pesapal;

class RegisterPesapalIpn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * Usage: php artisan pesapal:register-ipn
     */
    protected $signature = 'pesapal:register-ipn';

    /**
     * The console command description.
     */
    protected $description = 'Register Pesapal IPN and display the ipn_id to set in .env';

    public function handle()
    {
        try {
            // Check if Pesapal is configured
            if (!config('pesapal.consumer_key') || !config('pesapal.consumer_secret')) {
                $this->error("❌ Pesapal credentials not configured. Check your .env file.");
                $this->line("Required: PESAPAL_CONSUMER_KEY, PESAPAL_CONSUMER_SECRET");
                return Command::FAILURE;
            }

            $this->info("🔄 Registering IPN...");
            $this->line("URL: " . route('payment.confirmation'));
            $this->line("Environment: " . config('pesapal.environment'));

            // Call Pesapal to register IPN
            $ipn = Pesapal::registerIpn(route('payment.confirmation'));

            // Handle if response is not array
            if (!is_array($ipn)) {
                $this->error("❌ Failed: Unexpected response from Pesapal");
                $this->line("Response: " . print_r($ipn, true));
                return Command::FAILURE;
            }

            // Handle missing keys
            if (empty($ipn['ipn_id']) || empty($ipn['url'])) {
                $this->error("❌ Failed: Response does not contain expected keys");
                $this->line("Response: " . print_r($ipn, true));
                return Command::FAILURE;
            }

            // Success
            $this->info('✅ IPN registered successfully!');
            $this->line('IPN ID: ' . $ipn['ipn_id']);
            $this->line('URL: ' . $ipn['url']);

            $this->warn("\n👉 Copy the IPN ID into your .env like this:");
            $this->line('PESAPAL_IPN_ID="' . $ipn['ipn_id'] . '"');
            $this->warn("\n⚠️  Make sure to restart your application after updating .env");
            $this->info("\n💡 Run: php artisan config:clear");

            return Command::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('❌ Failed to register IPN: ' . $e->getMessage());
            $this->line("\nDebug info:");
            $this->line("File: " . $e->getFile() . ":" . $e->getLine());
            
            if ($this->option('verbose')) {
                $this->line("Trace: " . $e->getTraceAsString());
            }
            
            return Command::FAILURE;
        }
    }
}

