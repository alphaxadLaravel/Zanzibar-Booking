<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'Super Admin')->first();

        if ($superAdminRole) {
            User::updateOrCreate(
                ['email' => 'admin@zanzibarbookings.com'],
                [
                    'firstname' => 'Super',
                    'lastname' => 'Admin',
                    'email' => 'admin@zanzibarbookings.com',
                    'phone' => '+1234567890',
                    'password' => Hash::make('password'),
                    'status' => true,
                    'role_id' => $superAdminRole->id,
                    'email_verified_at' => now(),
                    'is_suspended' => false,
                ]
            );
        }
    }
}
