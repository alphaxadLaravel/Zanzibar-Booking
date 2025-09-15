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
        // Get the Admin role
        $adminRole = Role::where('name', 'Admin')->first();

        if ($adminRole) {
            User::firstOrCreate(
                ['email' => 'admin@zanzibarbookings.com'],
                [
                    'firstname' => 'Admin',
                    'lastname' => 'User',
                    'email' => 'admin@zanzibarbookings.com',
                    'phone' => '+1234567890',
                    'password' => Hash::make('password'),
                    'status' => true,
                    'role_id' => $adminRole->id,
                ]
            );
        }
    }
}
