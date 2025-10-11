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
        Schema::create('flight_passengers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flight_booking_id')->constrained()->onDelete('cascade');
            
            // Passenger Type
            $table->enum('type', ['adult', 'child', 'infant'])->default('adult');
            
            // Personal Information
            $table->string('title')->nullable(); // Mr, Mrs, Ms, Dr, etc.
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->string('gender')->nullable(); // M, F
            $table->string('nationality')->nullable();
            
            // Travel Documents
            $table->string('passport_number')->nullable();
            $table->string('passport_country')->nullable();
            $table->date('passport_expiry')->nullable();
            
            // Contact Information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            
            // Special Requests
            $table->string('meal_preference')->nullable();
            $table->string('seat_preference')->nullable();
            $table->text('special_requirements')->nullable();
            
            // Frequent Flyer
            $table->string('frequent_flyer_number')->nullable();
            $table->string('frequent_flyer_airline')->nullable();
            
            $table->timestamps();
            
            $table->index('flight_booking_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_passengers');
    }
};
