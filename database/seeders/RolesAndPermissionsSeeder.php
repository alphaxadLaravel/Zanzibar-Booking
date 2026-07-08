<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeds only roles and permissions. Safe to run multiple times — no duplicates.
 * Does not seed users, categories, deals, pages, or any other data.
 */
class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);
    }
}
