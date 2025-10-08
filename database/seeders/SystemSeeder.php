<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\System;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        System::create([
            'email' => 'info@zanzibarbookings.com',
            'phone' => '+255 774 378835',
            'secondary_phone' => '+255 123 456789',
            'address' => 'Zanzibar, Tanzania',
            'about_text' => 'Zanzibar Bookings is your trusted local travel and tour company dedicated to creating tailor-made adventures that showcase the beauty, culture, and spirit of Zanzibar.',
            'whatsapp_url' => 'https://wa.me/message/JMDWFIGBWX5TI1',
            'facebook_url' => 'https://www.facebook.com/zanzibarbookings',
            'instagram_url' => 'https://www.instagram.com/zanzibarbookings',
            'tripadvisor_url' => 'https://www.tripadvisor.com/zanzibarbookings',
            'youtube_url' => 'https://www.youtube.com/zanzibarbookings',
            'video_file' => 'zanzibar.mp4',
            'header_photo' => null,
        ]);
    }
}
