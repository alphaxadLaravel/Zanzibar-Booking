<?php

use App\Models\Permission;
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

        $this->migrateLegacyPermission('blog.manage', [
            'blog.view',
            'blog.create',
            'blog.edit',
            'blog.delete',
        ]);

        $this->migrateLegacyPermission('settings.manage', [
            'settings.system',
            'settings.seo',
            'settings.content',
        ]);

        Permission::whereIn('slug', ['blog.manage', 'settings.manage'])->delete();
    }

    protected function migrateLegacyPermission(string $legacySlug, array $newSlugs): void
    {
        $legacyPermission = Permission::where('slug', $legacySlug)->first();

        if (!$legacyPermission) {
            return;
        }

        $newPermissionIds = Permission::query()
            ->whereIn('slug', $newSlugs)
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
    }

    public function down(): void
    {
        // Intentionally left empty.
    }
};
