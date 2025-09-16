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
            $table->unsignedBigInteger('deal_id')->after('id');
            $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
            $table->integer('day_number')->default(1)->after('deal_id');
            $table->string('time')->nullable()->after('day_number');
            $table->string('location')->nullable()->after('time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tour_itenaries', function (Blueprint $table) {
            $table->dropForeign(['deal_id']);
            $table->dropColumn(['deal_id', 'day_number', 'time', 'location']);
        });
    }
};
