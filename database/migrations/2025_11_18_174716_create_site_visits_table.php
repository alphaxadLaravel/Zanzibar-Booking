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
        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->index();
            $table->text('user_agent')->nullable();
            $table->string('session_id', 255)->nullable()->index();
            $table->string('visitor_hash', 64)->index(); // Hash of IP + User Agent for uniqueness
            $table->timestamp('first_visit_at');
            $table->timestamp('last_visit_at');
            $table->integer('visit_count')->default(1);
            $table->timestamps();
            
            // Index for unique visitor tracking
            $table->unique('visitor_hash');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
