<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->boolean('is_group_package')->default(false)->after('child_price');
            $table->unsignedInteger('group_max_capacity')->nullable()->after('is_group_package');
            $table->date('group_booking_deadline')->nullable()->after('group_max_capacity');
            $table->date('group_departure_date')->nullable()->after('group_booking_deadline');
        });
    }

    public function down(): void
    {
        Schema::table('tours', function (Blueprint $table) {
            $table->dropColumn([
                'is_group_package',
                'group_max_capacity',
                'group_booking_deadline',
                'group_departure_date',
            ]);
        });
    }
};
