<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed roles first
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            AdminSeeder::class,
            FeaturesSeeder::class,
            // CategorySeeder::class,
            PageSeeder::class,
            SystemSeeder::class,
            GroupPackageSeeder::class,
        ]);

    }
}
