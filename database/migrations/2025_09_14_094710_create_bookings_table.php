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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->unsignedBigInteger('deal_id');
            $table->decimal('total_price', 12, 2);
            $table->unsignedBigInteger('room_id')->nullable();
            $table->date('check_in')->nullable();
            $table->date('check_out')->nullable();
            $table->integer('number_rooms')->default(1);
            $table->integer('adult')->default(1);
            $table->integer('children')->default(0);
            $table->string('pickup_location')->nullable();
            $table->string('return_location')->nullable();
            $table->time('pickup_time')->nullable();
            $table->time('return_time')->nullable();
            $table->boolean('need_driver')->default(0);
            $table->string('fullname');
            $table->string('email');
            $table->string('phone');
            $table->string('country');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->foreign('deal_id')->references('id')->on('deals')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
