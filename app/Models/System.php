<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'secondary_phone',
        'address',
        'about_text',
        'header_photo',
        'video_file',
        'facebook_url',
        'whatsapp_url',
        'instagram_url',
        'tripadvisor_url',
        'youtube_url',
        'home_seo_title',
        'home_seo_description',
        'home_seo_keywords',
        'home_seo_image',
    ];
}
