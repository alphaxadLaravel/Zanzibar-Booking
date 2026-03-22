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
        // Add pricing type and optional per-person price to rooms
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('price_type', 30)->default('per_night')->after('price')
                ->comment('per_night or per_person_per_night');
            $table->decimal('price_per_person', 12, 2)->nullable()->after('price_type');
        });

        // Create room_price_intervals for seasonal/period-based pricing
        Schema::create('room_price_intervals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 12, 2);
            $table->string('label')->nullable()->comment('e.g. Christmas, Jan-Mar, High Season');
            $table->timestamps();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->index(['room_id', 'start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_price_intervals');

        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['price_type', 'price_per_person']);
        });
    }
};
