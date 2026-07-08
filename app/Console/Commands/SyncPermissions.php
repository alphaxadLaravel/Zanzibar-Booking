<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncPermissions extends Command
{
    protected $signature = 'permissions:sync {--assign-admins : Assign all permissions to Super Admin and Admin role users}';

    protected $description = 'Sync permissions from config/permissions.php (no duplicates).';

    public function handle(): int
    {
        $groups = config('permissions.groups', []);

        if (empty($groups)) {
            $this->error('No permission groups found in config. Run: php artisan config:clear');

            return self::FAILURE;
        }

        $created = 0;
        $updated = 0;

        foreach ($groups as $group => $permissions) {
            foreach ($permissions as $slug => $name) {
                $permission = Permission::query()->where('slug', $slug)->first();

                if ($permission) {
                    $permission->update(['name' => $name, 'group' => $group]);
                    $updated++;
                } else {
                    Permission::create([
                        'slug' => $slug,
                        'name' => $name,
                        'group' => $group,
                    ]);
                    $created++;
                }
            }
        }

        $total = Permission::count();
        $this->info("Permissions synced: {$created} created, {$updated} updated, {$total} total in database.");

        if ($this->option('assign-admins')) {
            $permissionIds = Permission::pluck('id');
            $adminRoleIds = Role::whereIn('name', ['Super Admin', 'Admin'])->pluck('id');
            $userIds = User::whereIn('role_id', $adminRoleIds)->pluck('id');

            foreach ($userIds as $userId) {
                foreach ($permissionIds as $permissionId) {
                    DB::table('permission_user')->updateOrInsert(
                        ['user_id' => $userId, 'permission_id' => $permissionId],
                        ['created_at' => now(), 'updated_at' => now()]
                    );
                }
            }

            $this->info('Assigned all permissions to Super Admin and Admin users.');
        }

        return self::SUCCESS;
    }
}
