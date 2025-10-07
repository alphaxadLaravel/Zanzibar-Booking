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
        'video_url',
        'facebook_url',
        'whatsapp_url',
        'instagram_url',
        'twitter_url',
        'linkedin_url',
        'tripadvisor_url',
        'youtube_url',
    ];
}
