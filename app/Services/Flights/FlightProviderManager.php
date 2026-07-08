<?php

namespace App\Services\Flights;

use App\Contracts\Flights\FlightProviderInterface;
use App\Services\Flights\Providers\DuffelProvider;

class FlightProviderManager
{
    public function driver(): FlightProviderInterface
    {
        return app(DuffelProvider::class);
    }
}
