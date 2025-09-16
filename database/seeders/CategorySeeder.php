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

            // Tour Categories
            ['category' => 'City Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Cultural Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Adventure Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Nature Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Food Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Historical Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Photography Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Day Trips', 'type' => 'tour', 'image' => null],
            ['category' => 'Multi-day Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Private Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Group Tours', 'type' => 'tour', 'image' => null],
            ['category' => 'Walking Tours', 'type' => 'tour', 'image' => null],
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
