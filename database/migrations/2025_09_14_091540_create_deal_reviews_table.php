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
        Schema::create('deal_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deal_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->default(1)->constrained()->onDelete('cascade');
            $table->string('review_title');
            $table->text('review_content');
            $table->integer('rating')->unsigned(); // 1-5 stars
            $table->boolean('is_approved')->default(true);
            $table->timestamps();
            
            $table->index(['deal_id', 'is_approved']);
            $table->index(['rating']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deal_reviews');
    }
};
