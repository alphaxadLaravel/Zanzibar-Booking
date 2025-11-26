<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Knox\Pesapal\Facades\Pesapal;
use Illuminate\Support\Facades\Log;

class TestPesapalCredentials extends Command
{
    protected $signature = 'pesapal:test-credentials';
    protected $description = 'Test Pesapal credentials by attempting to register an IPN';

    public function handle()
    {
        $this->info('Testing Pesapal Credentials...');
        $this->newLine();
        
        // Get credentials
        $consumerKey = trim(config('pesapal.consumer_key', ''), " \t\n\r\0\x0B\"'");
        $consumerSecret = trim(config('pesapal.consumer_secret', ''), " \t\n\r\0\x0B\"'");
        $environment = config('pesapal.environment', 'sandbox');
        
        // Update config
        config([
            'pesapal.consumer_key' => $consumerKey,
            'pesapal.consumer_secret' => $consumerSecret,
            'pesapal.environment' => $environment
        ]);
        
        $this->line("Environment: {$environment}");
        $this->line("Consumer Key Length: " . strlen($consumerKey));
        $this->line("Consumer Secret Length: " . strlen($consumerSecret));
        $this->line("Consumer Key (first 10 chars): " . substr($consumerKey, 0, 10) . "...");
        $this->line("Consumer Secret (first 10 chars): " . substr($consumerSecret, 0, 10) . "...");
        $this->newLine();
        
        if (empty($consumerKey) || empty($consumerSecret)) {
            $this->error('❌ Credentials are empty!');
            return Command::FAILURE;
        }
        
        // Check for quotes
        if (strpos($consumerKey, '"') !== false || strpos($consumerKey, "'") !== false) {
            $this->warn('⚠️  Consumer Key contains quotes!');
        }
        if (strpos($consumerSecret, '"') !== false || strpos($consumerSecret, "'") !== false) {
            $this->warn('⚠️  Consumer Secret contains quotes!');
        }
        
        $this->info('🔄 Testing credentials by registering IPN...');
        
        try {
            $ipnUrl = route('payment.confirmation');
            $this->line("IPN URL: {$ipnUrl}");
            
            $ipn = Pesapal::registerIpn($ipnUrl);
            
            if (is_array($ipn) && isset($ipn['ipn_id'])) {
                $this->info('✅ Credentials are VALID!');
                $this->line("IPN ID: " . $ipn['ipn_id']);
                $this->line("IPN URL: " . ($ipn['url'] ?? 'N/A'));
                return Command::SUCCESS;
            } else {
                $this->error('❌ Credentials test failed!');
                $this->line("Response: " . print_r($ipn, true));
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('❌ Error testing credentials: ' . $e->getMessage());
            $this->line("File: " . $e->getFile() . ":" . $e->getLine());
            
            if (stripos($e->getMessage(), 'Invalid consumer') !== false) {
                $this->newLine();
                $this->warn('The credentials are INVALID for the ' . $environment . ' environment.');
                $this->line('Please check:');
                $this->line('1. Are you using the correct credentials for ' . $environment . '?');
                $this->line('2. Did you copy the credentials correctly from Pesapal dashboard?');
                $this->line('3. Are there any extra spaces or characters?');
            }
            
            return Command::FAILURE;
        }
    }
}

