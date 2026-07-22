<?php

namespace Database\Seeders;

use App\Models\Features;
use Illuminate\Database\Seeder;

class PackagePriceFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        $includes = [
            ['name' => 'Luxury accommodation', 'icon' => 'mdi-bed-king'],
            ['name' => 'Mid-range Accommodation', 'icon' => 'mdi-hotel'],
            ['name' => 'Budget Accommodation', 'icon' => 'mdi-home-city'],
            ['name' => 'All-inclusive', 'icon' => 'mdi-silverware-fork-knife'],
            ['name' => 'Half-board', 'icon' => 'mdi-food-variant'],
            ['name' => 'Daily breakfast', 'icon' => 'mdi-food-croissant'],
            ['name' => 'Selected lunches', 'icon' => 'mdi-food'],
            ['name' => 'Seafood Sunset Dinner', 'icon' => 'mdi-food-steak'],
            ['name' => 'Airport transfers', 'icon' => 'mdi-airplane'],
            ['name' => 'All private transportation', 'icon' => 'mdi-bus'],
            ['name' => 'All entrance fees', 'icon' => 'mdi-ticket-confirmation'],
            ['name' => 'All excursions and activities listed', 'icon' => 'mdi-map-marker-path'],
            ['name' => 'Professional assistance throughout your stay', 'icon' => 'mdi-face-agent'],
            ['name' => 'Visa Processing $420 3 persons / 1 complimentary', 'icon' => 'mdi-passport'],
            ['name' => 'Government taxes and Entrance charges', 'icon' => 'mdi-cash-multiple'],
            ['name' => 'Snorkeling around Mnemba Island Marine Reserve', 'icon' => 'mdi-fish'],
            ['name' => 'Relaxing at Kendwa Beach', 'icon' => 'mdi-beach'],
            ['name' => 'Clear Kayaking Experience', 'icon' => 'mdi-kayaking'],
            ['name' => 'Luxury Jet Car Ride', 'icon' => 'mdi-car-sports'],
            ['name' => 'Traditional Sunset Dhow Cruise', 'icon' => 'mdi-sail-boat'],
            ['name' => 'International Flight ticket', 'icon' => 'mdi-airplane'],
            ['name' => 'Domestic flight', 'icon' => 'mdi-airplane'],
            ['name' => 'SGR Train in Tanzania', 'icon' => 'mdi-train'],
            ['name' => 'Snorkeling and safety gears', 'icon' => 'mdi-lifebuoy'],
        ];

        $excludes = [
            ['name' => 'International Flight', 'icon' => 'mdi-airplane-off'],
            ['name' => 'Domestic flight (not included)', 'icon' => 'mdi-airplane-alert'],
            ['name' => 'Travel insurance', 'icon' => 'mdi-shield-account'],
            ['name' => 'Alcoholic beverages', 'icon' => 'mdi-glass-cocktail'],
            ['name' => 'Personal expenses', 'icon' => 'mdi-cash'],
            ['name' => 'Optional activities not listed in the itinerary', 'icon' => 'mdi-star-off'],
            ['name' => 'Tips and gratuities', 'icon' => 'mdi-hand-coin'],
        ];

        foreach ($includes as $feature) {
            Features::updateOrCreate(
                ['name' => $feature['name']],
                [
                    'icon' => $feature['icon'],
                    'type' => 'include',
                    'status' => true,
                ]
            );
        }

        foreach ($excludes as $feature) {
            Features::updateOrCreate(
                ['name' => $feature['name']],
                [
                    'icon' => $feature['icon'],
                    'type' => 'exclude',
                    'status' => true,
                ]
            );
        }
    }
}
