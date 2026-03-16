<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = [
        'business_name',
        'logo_url',
        'logo_alt_url',
        'hero_image',
        'primary_color',
        'secondary_color',
        'hero_title',
        'hero_subtitle',
        'hero_button_text',
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
        'about_title',
        'about_text',
        'address',
        'phone',
        'email',
        'map_embed_url',
        'instagram_url',
        'facebook_url',
        'tiktok_url',
        'show_services_section',
        'show_about_section',
        'show_contact_section',
        'show_team_section',
        'show_gallery_section',
        'show_reviews_section',
        'primary_font',
        'secondary_font',
        'border_radius',
        'section_padding',
        'custom_css',
        'seo_title',
        'seo_description',
    ];
}
