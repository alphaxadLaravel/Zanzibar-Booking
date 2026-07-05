<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        foreach (config('permissions.groups', []) as $group => $permissions) {
            foreach ($permissions as $slug => $name) {
                Permission::updateOrCreate(
                    ['slug' => $slug],
                    ['name' => $name, 'group' => $group]
                );
            }
        }

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if ($superAdminRole) {
            User::where('email', 'admin@zanzibarbookings.com')->update([
                'role_id' => $superAdminRole->id,
            ]);
        }
    }

    public function down(): void
    {
        // Intentionally left empty — do not downgrade the primary account role on rollback.
    }
};
