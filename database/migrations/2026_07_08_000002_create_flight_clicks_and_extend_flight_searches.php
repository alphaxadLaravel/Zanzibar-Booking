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
        Schema::table('flight_searches', function (Blueprint $table) {
            if (! Schema::hasColumn('flight_searches', 'trip_type')) {
                $table->string('trip_type', 20)->default('one_way')->after('user_id');
            }
            if (! Schema::hasColumn('flight_searches', 'country')) {
                $table->string('country', 10)->nullable()->after('ip_address');
            }
            if (! Schema::hasColumn('flight_searches', 'device')) {
                $table->string('device', 50)->nullable()->after('country');
            }
            if (! Schema::hasColumn('flight_searches', 'browser')) {
                $table->string('browser', 100)->nullable()->after('device');
            }
            if (! Schema::hasColumn('flight_searches', 'operating_system')) {
                $table->string('operating_system', 100)->nullable()->after('browser');
            }
        });

        Schema::create('flight_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_search_id')->nullable()->constrained('flight_searches')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('airline')->nullable();
            $table->string('flight_number')->nullable();
            $table->string('origin', 10);
            $table->string('destination', 10);
            $table->decimal('price', 12, 2)->nullable();
            $table->string('currency', 10)->default('USD');
            $table->string('affiliate_name');
            $table->text('affiliate_url');
            $table->timestamp('clicked_at');
            $table->string('ip_address', 45)->nullable();
            $table->string('country', 10)->nullable();
            $table->string('device', 50)->nullable();
            $table->string('browser', 100)->nullable();
            $table->string('operating_system', 100)->nullable();
            $table->timestamps();

            $table->index('clicked_at');
            $table->index(['origin', 'destination']);
            $table->index('airline');
        });

        foreach (config('permissions.groups', []) as $group => $permissions) {
            foreach ($permissions as $slug => $name) {
                if (! str_starts_with($slug, 'flights.')) {
                    continue;
                }

                Permission::updateOrCreate(
                    ['slug' => $slug],
                    ['name' => $name, 'group' => $group]
                );
            }
        }

        $permissionIds = Permission::whereIn('slug', ['flights.view', 'flights.manage'])->pluck('id');
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
        Schema::dropIfExists('flight_clicks');

        Schema::table('flight_searches', function (Blueprint $table) {
            $columns = ['trip_type', 'country', 'device', 'browser', 'operating_system'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('flight_searches', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        $permissionIds = Permission::whereIn('slug', ['flights.view', 'flights.manage'])->pluck('id');
        DB::table('permission_user')->whereIn('permission_id', $permissionIds)->delete();
        Permission::whereIn('slug', ['flights.view', 'flights.manage'])->delete();
    }
};
