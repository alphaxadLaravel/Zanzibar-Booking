<?php

namespace Database\Seeders;

use App\Models\BookingItem;
use App\Models\Category;
use App\Models\Deal;
use App\Models\Role;
use App\Models\Tours;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GroupPackageSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@zanzibarbookings.com')->first();
        if (!$admin) {
            $this->command->warn('Admin user not found. Run AdminSeeder first.');
            return;
        }

        $customer = User::updateOrCreate(
            ['email' => 'customer@test.com'],
            [
                'firstname' => 'Test',
                'lastname' => 'Customer',
                'phone' => '+255712345678',
                'password' => Hash::make('password'),
                'status' => true,
                'role_id' => Role::where('name', 'User')->value('id'),
                'email_verified_at' => now(),
                'is_suspended' => false,
            ]
        );

        $category = Category::firstOrCreate(
            ['category' => 'Group Packages', 'type' => 'tour'],
            ['status' => true]
        );

        $packages = [
            [
                'title' => 'Stone Town & Spice Farm Group Tour',
                'location' => 'Stone Town, Zanzibar',
                'base_price' => 85.00,
                'description' => '<p>Join a small group for a guided walk through historic Stone Town followed by a spice farm visit. Perfect for travelers who want a shared cultural experience at a great price.</p>',
                'policies' => '<p>Full payment required to confirm your spot. Cancellations within 7 days of departure are non-refundable.</p>',
                'period' => 1,
                'max_people' => 2,
                'adult_price' => 85.00,
                'child_price' => 45.00,
                'group_max_capacity' => 15,
                'group_booking_deadline' => Carbon::today()->addDays(30),
                'group_departure_date' => Carbon::today()->addDays(45),
                'is_featured' => true,
                'paid_bookings' => [
                    ['user_id' => $customer->id, 'adults' => 2, 'children' => 0],
                    ['user_id' => $admin->id, 'adults' => 2, 'children' => 1],
                ],
            ],
            [
                'title' => 'Safari Blue Group Adventure',
                'location' => 'Fumba, Zanzibar',
                'base_price' => 120.00,
                'description' => '<p>Full-day dhow cruise with snorkeling, seafood lunch, and sandbank stop. Group departs once minimum participants are confirmed.</p>',
                'policies' => '<p>Online payment only. Trip runs when group capacity thresholds are met.</p>',
                'period' => 1,
                'max_people' => 2,
                'adult_price' => 120.00,
                'child_price' => 60.00,
                'group_max_capacity' => 20,
                'group_booking_deadline' => Carbon::today()->addDays(60),
                'group_departure_date' => Carbon::today()->addDays(75),
                'is_featured' => true,
                'paid_bookings' => [],
            ],
            [
                'title' => 'Mnemba Island Snorkeling Group',
                'location' => 'Mnemba Atoll, Zanzibar',
                'base_price' => 150.00,
                'description' => '<p>Boat trip to Mnemba Island with guided snorkeling in crystal-clear waters. Limited to 10 guests for a premium small-group experience.</p>',
                'policies' => '<p>Includes equipment and boat transfer. Marine park fees included.</p>',
                'period' => 1,
                'max_people' => 1,
                'adult_price' => 150.00,
                'child_price' => 75.00,
                'group_max_capacity' => 10,
                'group_booking_deadline' => Carbon::today()->addDays(14),
                'group_departure_date' => Carbon::today()->addDays(21),
                'is_featured' => false,
                'paid_bookings' => [
                    ['user_id' => $customer->id, 'adults' => 2, 'children' => 1],
                    ['user_id' => $admin->id, 'adults' => 2, 'children' => 0],
                    ['user_id' => $customer->id, 'adults' => 1, 'children' => 1],
                ],
            ],
            [
                'title' => 'Jozani Forest & Beach Group Package',
                'location' => 'Jozani-Chwaka Bay, Zanzibar',
                'base_price' => 95.00,
                'description' => '<p>Half-day red colobus monkey trek at Jozani Forest plus relaxation at a nearby beach. Almost full — book your spot soon.</p>',
                'policies' => '<p>Group package — only paid bookings reserve a place.</p>',
                'period' => 1,
                'max_people' => 2,
                'adult_price' => 95.00,
                'child_price' => 50.00,
                'group_max_capacity' => 12,
                'group_booking_deadline' => Carbon::today()->addDays(7),
                'group_departure_date' => Carbon::today()->addDays(14),
                'is_featured' => false,
                'paid_bookings' => [
                    ['user_id' => $customer->id, 'adults' => 2, 'children' => 2],
                    ['user_id' => $admin->id, 'adults' => 3, 'children' => 0],
                    ['user_id' => $customer->id, 'adults' => 2, 'children' => 2],
                ],
            ],
        ];

        foreach ($packages as $data) {
            $paidBookings = $data['paid_bookings'];
            unset($data['paid_bookings']);

            $deal = Deal::updateOrCreate(
                ['title' => $data['title'], 'type' => 'package'],
                [
                    'cover_photo' => null,
                    'location' => $data['location'],
                    'lat' => -6.1659,
                    'long' => 39.2026,
                    'map_location' => $data['location'],
                    'base_price' => $data['base_price'],
                    'ratings' => 4.5,
                    'star_rating' => 5,
                    'is_featured' => $data['is_featured'],
                    'category_id' => $category->id,
                    'description' => $data['description'],
                    'policies' => $data['policies'],
                    'user_id' => $admin->id,
                    'status' => true,
                ]
            );

            $tour = Tours::updateOrCreate(
                ['deal_id' => $deal->id],
                [
                    'period' => $data['period'],
                    'max_people' => $data['max_people'],
                    'adult_price' => $data['adult_price'],
                    'child_price' => $data['child_price'],
                    'is_group_package' => true,
                    'group_max_capacity' => $data['group_max_capacity'],
                    'group_booking_deadline' => $data['group_booking_deadline'],
                    'group_departure_date' => $data['group_departure_date'],
                ]
            );

            BookingItem::where('deal_id', $deal->id)->where('status', 'paid')->delete();

            foreach ($paidBookings as $booking) {
                $adults = $booking['adults'];
                $children = $booking['children'];
                $totalPrice = ($adults * $tour->adult_price) + ($children * $tour->child_price);

                BookingItem::create([
                    'user_id' => $booking['user_id'],
                    'deal_id' => $deal->id,
                    'room_id' => null,
                    'number_rooms' => null,
                    'type' => 'package',
                    'check_in' => $tour->group_departure_date,
                    'check_out' => null,
                    'total_price' => $totalPrice,
                    'adults' => $adults,
                    'children' => $children,
                    'status' => 'paid',
                ]);
            }

            $booked = array_sum(array_map(
                fn ($b) => $b['adults'] + $b['children'],
                $paidBookings
            ));

            $this->command->info(sprintf(
                'Seeded group package: %s (%d/%d paid)',
                $deal->title,
                $booked,
                $tour->group_max_capacity
            ));
        }

        $this->command->info('Test customer: customer@test.com / password');
    }
}
