<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->decimal('supplier_cost', 10, 2)->nullable()->after('taxes');
            $table->decimal('markup_amount', 10, 2)->default(0)->after('supplier_cost');
        });
    }

    public function down(): void
    {
        Schema::table('flight_bookings', function (Blueprint $table) {
            $table->dropColumn(['supplier_cost', 'markup_amount']);
        });
    }
};
