<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_hotel_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference', 20)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->string('supplier', 40)->default('hotelbeds');
            $table->string('hotel_code', 40);
            $table->string('hotel_name');
            $table->string('destination_code', 10)->nullable();
            $table->string('destination_name')->nullable();
            $table->date('check_in');
            $table->date('check_out');
            $table->string('room_name')->nullable();
            $table->string('board_name')->nullable();
            $table->unsignedTinyInteger('rooms')->default(1);
            $table->unsignedTinyInteger('adults')->default(1);
            $table->unsignedTinyInteger('children')->default(0);
            $table->decimal('supplier_cost', 12, 2)->default(0);
            $table->decimal('markup_amount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->string('currency', 3)->default('USD');
            $table->text('rate_key')->nullable();
            $table->json('supplier_payload')->nullable();
            $table->string('supplier_booking_ref')->nullable();
            $table->json('supplier_response')->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('contact_email');
            $table->string('contact_phone', 40)->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('hotel_code');
            $table->index('check_in');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->foreignId('supplier_hotel_booking_id')
                ->nullable()
                ->after('flight_booking_id')
                ->constrained('supplier_hotel_bookings')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('supplier_hotel_booking_id');
        });

        Schema::dropIfExists('supplier_hotel_bookings');
    }
};
