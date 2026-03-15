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
        'seo_title',
        'seo_description',
    ];
}
