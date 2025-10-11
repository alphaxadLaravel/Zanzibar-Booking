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
        Schema::create('flight_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            
            // Flight Details
            $table->string('flight_number');
            $table->string('airline_code');
            $table->string('airline_name');
            $table->string('origin_code');
            $table->string('origin_name');
            $table->string('destination_code');
            $table->string('destination_name');
            $table->dateTime('departure_datetime');
            $table->dateTime('arrival_datetime');
            $table->string('duration');
            $table->string('aircraft')->nullable();
            $table->integer('stops')->default(0);
            
            // Booking Details
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending');
            $table->string('travel_class');
            $table->integer('adults')->default(1);
            $table->integer('children')->default(0);
            $table->integer('infants')->default(0);
            
            // Pricing
            $table->decimal('base_price', 10, 2);
            $table->decimal('taxes', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2);
            $table->string('currency')->default('USD');
            
            // Amadeus Data
            $table->json('flight_offer')->nullable();
            $table->string('amadeus_order_id')->nullable();
            $table->json('amadeus_response')->nullable();
            
            // Contact Information
            $table->string('contact_email');
            $table->string('contact_phone');
            
            // Timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            
            $table->index(['booking_reference', 'status']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_bookings');
    }
};
