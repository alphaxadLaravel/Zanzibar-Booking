<?php

use App\Models\Permission;
use App\Support\DealPermissions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

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

        $legacyMap = [
            'deals.view' => 'view',
            'deals.create' => 'create',
            'deals.edit' => 'edit',
            'deals.delete' => 'delete',
        ];

        foreach ($legacyMap as $legacySlug => $action) {
            $legacyPermission = Permission::where('slug', $legacySlug)->first();

            if (!$legacyPermission) {
                continue;
            }

            $newPermissionIds = Permission::query()
                ->whereIn('slug', DealPermissions::slugsForAction($action))
                ->pluck('id');

            $userIds = DB::table('permission_user')
                ->where('permission_id', $legacyPermission->id)
                ->pluck('user_id');

            foreach ($userIds as $userId) {
                foreach ($newPermissionIds as $newPermissionId) {
                    DB::table('permission_user')->updateOrInsert(
                        [
                            'user_id' => $userId,
                            'permission_id' => $newPermissionId,
                        ],
                        [
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }

            DB::table('permission_user')->where('permission_id', $legacyPermission->id)->delete();
            $legacyPermission->delete();
        }
    }

    public function down(): void
    {
        // Intentionally left empty.
    }
};
