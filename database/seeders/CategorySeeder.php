<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Hotel Categories
            ['category' => 'Luxury Hotels', 'type' => 'hotel', 'image' => null],
            ['category' => 'Budget Hotels', 'type' => 'hotel', 'image' => null],
            ['category' => 'Boutique Hotels', 'type' => 'hotel', 'image' => null],
            ['category' => 'Resort Hotels', 'type' => 'hotel', 'image' => null],
            ['category' => 'Business Hotels', 'type' => 'hotel', 'image' => null],
            ['category' => 'Beach Hotels', 'type' => 'hotel', 'image' => null],
            ['category' => 'Mountain Hotels', 'type' => 'hotel', 'image' => null],
            ['category' => 'City Hotels', 'type' => 'hotel', 'image' => null],

            // Car Categories
            ['category' => 'Economy Cars', 'type' => 'car', 'image' => null],
            ['category' => 'Compact Cars', 'type' => 'car', 'image' => null],
            ['category' => 'Mid-size Cars', 'type' => 'car', 'image' => null],
            ['category' => 'Full-size Cars', 'type' => 'car', 'image' => null],
            ['category' => 'Luxury Cars', 'type' => 'car', 'image' => null],
            ['category' => 'SUVs', 'type' => 'car', 'image' => null],
            ['category' => 'Convertibles', 'type' => 'car', 'image' => null],
            ['category' => 'Electric Vehicles', 'type' => 'car', 'image' => null],

            // Apartment Categories
            ['category' => 'Studio Apartments', 'type' => 'apartment', 'image' => null],
            ['category' => '1-Bedroom Apartments', 'type' => 'apartment', 'image' => null],
            ['category' => '2-Bedroom Apartments', 'type' => 'apartment', 'image' => null],
            ['category' => '3-Bedroom Apartments', 'type' => 'apartment', 'image' => null],
            ['category' => 'Penthouse', 'type' => 'apartment', 'image' => null],
            ['category' => 'Serviced Apartments', 'type' => 'apartment', 'image' => null],
            ['category' => 'Furnished Apartments', 'type' => 'apartment', 'image' => null],
            ['category' => 'Unfurnished Apartments', 'type' => 'apartment', 'image' => null],

            // Activity Categories
            ['category' => 'City Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Cultural Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Adventure Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Nature Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Food Experiences', 'type' => 'activity', 'image' => null],
            ['category' => 'Historical Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Photography Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Day Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Walking Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Water Sports', 'type' => 'activity', 'image' => null],
            ['category' => 'Spice Farm Tours', 'type' => 'activity', 'image' => null],
            ['category' => 'Stone Town Tours', 'type' => 'activity', 'image' => null],
            ['category' => 'Snorkeling Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Diving Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Sunset Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Beach Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Wildlife Activities', 'type' => 'activity', 'image' => null],
            ['category' => 'Art & Craft Activities', 'type' => 'activity', 'image' => null],

            // Package Categories
            ['category' => 'Multi-day Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Private Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Group Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Beach Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Cultural Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Adventure Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Romantic Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Family Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Luxury Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Budget Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Safari Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Island Hopping', 'type' => 'package', 'image' => null],
            ['category' => 'City Tour Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Wellness Packages', 'type' => 'package', 'image' => null],
            ['category' => 'Honeymoon Packages', 'type' => 'package', 'image' => null],

            // Blog Categories
            ['category' => 'Travel Tips', 'type' => 'blog', 'image' => null],
            ['category' => 'Destination Guides', 'type' => 'blog', 'image' => null],
            ['category' => 'Hotel Reviews', 'type' => 'blog', 'image' => null],
            ['category' => 'Travel News', 'type' => 'blog', 'image' => null],
            ['category' => 'Adventure Stories', 'type' => 'blog', 'image' => null],
            ['category' => 'Food & Dining', 'type' => 'blog', 'image' => null],
            ['category' => 'Cultural Experiences', 'type' => 'blog', 'image' => null],
            ['category' => 'Travel Photography', 'type' => 'blog', 'image' => null],
            ['category' => 'Budget Travel', 'type' => 'blog', 'image' => null],
            ['category' => 'Luxury Travel', 'type' => 'blog', 'image' => null],
        ];

        // Seed all categories
        foreach ($categories as $categoryData) {
            Category::create([
                'category' => $categoryData['category'],
                'type' => $categoryData['type'],
                'image' => $categoryData['image'],
                'status' => true
            ]);
        }
    }
}
