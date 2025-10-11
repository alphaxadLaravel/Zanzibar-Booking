<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class VerifyAdminEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:verify-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify emails for all admin and super admin users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting admin email verification...');

        // Get Admin and Super Admin roles
        $adminRoles = Role::whereIn('name', ['Admin', 'Super Admin'])->pluck('id');

        if ($adminRoles->isEmpty()) {
            $this->error('No admin roles found in the database.');
            return 1;
        }

        // Find all admin users with unverified emails
        $unverifiedAdmins = User::whereIn('role_id', $adminRoles)
            ->whereNull('email_verified_at')
            ->get();

        if ($unverifiedAdmins->isEmpty()) {
            $this->info('All admin users already have verified emails.');
            return 0;
        }

        $count = 0;
        foreach ($unverifiedAdmins as $admin) {
            $admin->email_verified_at = now();
            $admin->save();
            $count++;
            $this->line("✓ Verified email for: {$admin->firstname} {$admin->lastname} ({$admin->email})");
        }

        $this->newLine();
        $this->info("Successfully verified {$count} admin user(s).");
        
        return 0;
    }
}
