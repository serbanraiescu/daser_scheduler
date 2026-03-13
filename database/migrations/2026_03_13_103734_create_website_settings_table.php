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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            
            // Branding
            $table->string('business_name')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('primary_color')->default('#6366f1');
            $table->string('secondary_color')->default('#a855f7');

            // Hero Section
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle', 500)->nullable();
            $table->string('hero_button_text')->default('Programează-te acum');

            // About Section
            $table->string('about_title')->nullable();
            $table->text('about_text')->nullable();

            // Contact Section
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('map_embed_url')->nullable();

            // Social Media
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('tiktok_url')->nullable();

            // Visibility Toggles
            $table->boolean('show_services_section')->default(true);
            $table->boolean('show_about_section')->default(true);
            $table->boolean('show_contact_section')->default(true);

            // SEO
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 500)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
