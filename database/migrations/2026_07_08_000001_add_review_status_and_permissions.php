<?php

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deal_reviews', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('is_approved');
        });

        DB::table('deal_reviews')->where('is_approved', true)->update(['status' => 'approved']);
        DB::table('deal_reviews')->where('is_approved', false)->update(['status' => 'pending']);

        foreach (config('permissions.groups', []) as $group => $permissions) {
            foreach ($permissions as $slug => $name) {
                if (!str_starts_with($slug, 'reviews.')) {
                    continue;
                }

                Permission::updateOrCreate(
                    ['slug' => $slug],
                    ['name' => $name, 'group' => $group]
                );
            }
        }

        $permissionIds = Permission::whereIn('slug', ['reviews.view', 'reviews.manage'])->pluck('id');
        $adminRoleIds = Role::whereIn('name', ['Super Admin', 'Admin'])->pluck('id');
        $userIds = User::whereIn('role_id', $adminRoleIds)->pluck('id');

        foreach ($userIds as $userId) {
            foreach ($permissionIds as $permissionId) {
                DB::table('permission_user')->updateOrInsert(
                    [
                        'user_id' => $userId,
                        'permission_id' => $permissionId,
                    ],
                    [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }

    public function down(): void
    {
        $permissionIds = Permission::whereIn('slug', ['reviews.view', 'reviews.manage'])->pluck('id');
        DB::table('permission_user')->whereIn('permission_id', $permissionIds)->delete();
        Permission::whereIn('slug', ['reviews.view', 'reviews.manage'])->delete();

        Schema::table('deal_reviews', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
