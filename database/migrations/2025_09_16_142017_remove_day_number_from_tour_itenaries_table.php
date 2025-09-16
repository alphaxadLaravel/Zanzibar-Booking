<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tour_itenaries', function (Blueprint $table) {
            $table->dropColumn('day_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_itenaries', function (Blueprint $table) {
            $table->integer('day_number')->default(1)->after('deal_id');
        });
    }
};
