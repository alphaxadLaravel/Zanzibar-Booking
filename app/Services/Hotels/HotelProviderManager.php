<?php

namespace App\Services\Hotels;

use App\Contracts\Hotels\HotelProviderInterface;
use App\Services\Hotels\Providers\HotelbedsProvider;

class HotelProviderManager
{
    public function driver(): HotelProviderInterface
    {
        return app(HotelbedsProvider::class);
    }
}
