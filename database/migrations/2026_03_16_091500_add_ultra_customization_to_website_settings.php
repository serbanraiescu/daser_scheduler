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
        Schema::table('website_settings', function (Blueprint $table) {
            // New Visibility Toggles
            $table->boolean('show_team_section')->default(true)->after('show_contact_section');
            $table->boolean('show_gallery_section')->default(true)->after('show_team_section');
            $table->boolean('show_reviews_section')->default(true)->after('show_gallery_section');

            // Services Section Customization
            $table->string('services_title')->nullable()->after('hero_button_text');
            $table->string('services_subtitle')->nullable()->after('services_title');

            // Team Section Customization
            $table->string('team_title')->nullable()->after('services_subtitle');
            $table->text('team_subtitle')->nullable()->after('team_title');
            $table->string('team_reservation_text')->nullable()->after('team_subtitle');

            // Gallery Section Customization
            $table->string('gallery_title')->nullable()->after('team_reservation_text');
            $table->string('gallery_subtitle')->nullable()->after('gallery_title');
            $table->string('gallery_zoom_text')->nullable()->after('gallery_subtitle');

            // Reviews Section Customization
            $table->string('reviews_title')->nullable()->after('gallery_zoom_text');

            // Contact Section Customization
            $table->string('contact_title')->nullable()->after('reviews_title');
            $table->string('contact_label_location')->nullable()->after('contact_title');
            $table->string('contact_label_phone')->nullable()->after('contact_label_location');
            $table->string('contact_button_call_text')->nullable()->after('contact_label_phone');
            $table->string('contact_button_book_text')->nullable()->after('contact_button_call_text');

            // Advanced Styling
            $table->string('primary_font')->default('Inter')->after('contact_button_book_text');
            $table->string('secondary_font')->default('Inter')->after('primary_font');
            $table->string('border_radius')->default('1.5rem')->after('secondary_font');
            $table->string('section_padding')->default('80px')->after('border_radius');
            $table->text('custom_css')->nullable()->after('section_padding');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('website_settings', function (Blueprint $table) {
            $table->dropColumn([
                'show_team_section',
                'show_gallery_section',
                'show_reviews_section',
                'services_title',
                'services_subtitle',
                'team_title',
                'team_subtitle',
                'team_reservation_text',
                'gallery_title',
                'gallery_subtitle',
                'gallery_zoom_text',
                'reviews_title',
                'contact_title',
                'contact_label_location',
                'contact_label_phone',
                'contact_button_call_text',
                'contact_button_book_text',
                'primary_font',
                'secondary_font',
                'border_radius',
                'section_padding',
                'custom_css',
            ]);
        });
    }
};
