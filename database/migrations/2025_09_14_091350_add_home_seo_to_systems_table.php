<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->string('home_seo_title', 60)->nullable()->after('youtube_url');
            $table->string('home_seo_description', 160)->nullable()->after('home_seo_title');
            $table->text('home_seo_keywords')->nullable()->after('home_seo_description');
            $table->string('home_seo_image')->nullable()->after('home_seo_keywords');
        });
    }

    public function down(): void
    {
        Schema::table('systems', function (Blueprint $table) {
            $table->dropColumn(['home_seo_title', 'home_seo_description', 'home_seo_keywords', 'home_seo_image']);
        });
    }
};
