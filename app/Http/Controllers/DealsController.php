<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Deal;
use App\Models\Category;
use App\Models\Features;
use App\Models\Tours;
use App\Models\Car;
use App\Models\DealPhotos;
use App\Models\TourInclude;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DealsController extends Controller
{
    // Hotels Management
    public function hotels()
    {
        $hotels = Deal::with('category')
            ->where('type', 'hotel')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.pages.hotels.index', compact('hotels'));
    }

    // manageDeal
    public function manageDeal($type)
    {
        // Filter categories by type and active
        $categories = Category::active()->byType($type)->get();
        
        // Filter features based on deal type
        $features = Features::active()->where('type', $type)->get();
        
        // Get tour includes and excludes for tour type
        $tourIncludes = [];
        $tourExcludes = [];
        
        if ($type === 'tour') {
            $tourIncludes = Features::active()->where('type', 'include')->get();
            $tourExcludes = Features::active()->where('type', 'exclude')->get();
        }
            
        return view('admin.pages.manage_deal', compact('type', 'categories', 'features', 'tourIncludes', 'tourExcludes'));
    }

    // Store Deal
    public function storeDeal(Request $request, $type)
    {
        // Base validation rules for all deal types
        $baseRules = [
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'base_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'policies' => 'nullable|string',
            'location' => 'nullable|string',
            'lat' => 'nullable|numeric',
            'long' => 'nullable|numeric',
            'map_location' => 'nullable|string',
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'other_images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'nullable|in:publish,draft'
        ];

        // Type-specific validation rules
        $typeSpecificRules = [];
        
        switch ($type) {
            case 'hotel':
            case 'apartment':
                $typeSpecificRules = [
                    'features' => 'nullable|array',
                    'features.*' => 'exists:features,id',
                ];
                break;
                
            case 'car':
                $typeSpecificRules = [
                    'features' => 'nullable|array',
                    'features.*' => 'exists:features,id',
                    'car_capacity' => 'required|integer|min:1',
                    'transmission' => 'required|string|in:manual,automatic',
                    'fuel' => 'required|string|in:petrol,diesel,electric,hybrid',
                    'air_condition' => 'nullable|boolean',
                    'gps' => 'nullable|boolean',
                    'car_contract_document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
                ];
                break;
                
            case 'tour':
                $typeSpecificRules = [
                    'features' => 'nullable|array',
                    'features.*' => 'exists:features,id',
                    'tour_period' => 'required|string',
                    'max_people' => 'required|integer|min:1',
                    'adult_price' => 'required|numeric|min:0',
                    'child_price' => 'required|numeric|min:0',
                    'tour_includes' => 'nullable|array',
                    'tour_excludes' => 'nullable|array',
                ];
                break;
        }

        // Merge base rules with type-specific rules
        $validationRules = array_merge($baseRules, $typeSpecificRules);
        
        $request->validate($validationRules);

        try {
            DB::beginTransaction();

            // Handle cover photo upload
            $coverPhotoPath = null;
            if ($request->hasFile('cover_photo')) {
                $coverPhotoPath = $request->file('cover_photo')->store('deals/cover', 'public');
            }

            // Create the deal
            $deal = Deal::create([
                'title' => $request->title,
                'category_id' => $request->category_id,
                'base_price' => $request->base_price,
                'description' => $request->description,
                'policies' => $request->policies,
                'location' => $request->location,
                'lat' => $request->lat,
                'long' => $request->long,
                'map_location' => $request->map_location,
                'cover_photo' => $coverPhotoPath,
                'type' => $type, // Add deal type
                'user_id' => 1, // Set user_id to 1 as requested
                'status' => $request->status === 'publish' ? 1 : 0
            ]);

            // Handle features
            if ($request->has('features')) {
                $deal->features()->attach($request->features);
            }

            // Handle other images
            if ($request->hasFile('other_images')) {
                foreach ($request->file('other_images') as $image) {
                    $imagePath = $image->store('deals/photos', 'public');
                    DealPhotos::create([
                        'deal_id' => $deal->id,
                        'photo' => $imagePath
                    ]);
                }
            }

            // Handle type-specific data
            switch ($type) {
                case 'tour':
                    // Handle tour data
                    Tours::create([
                        'deal_id' => $deal->id,
                        'period' => $request->tour_period,
                        'max_people' => $request->max_people,
                        'adult_price' => $request->adult_price,
                        'child_price' => $request->child_price
                    ]);

                    // Handle tour includes
                    if ($request->has('tour_includes')) {
                        foreach ($request->tour_includes as $include) {
                            TourInclude::create([
                                'deal_id' => $deal->id,
                                'title' => $include,
                                'type' => 'include'
                            ]);
                        }
                    }

                    // Handle tour excludes
                    if ($request->has('tour_excludes')) {
                        foreach ($request->tour_excludes as $exclude) {
                            TourInclude::create([
                                'deal_id' => $deal->id,
                                'title' => $exclude,
                                'type' => 'exclude'
                            ]);
                        }
                    }
                    break;

                case 'car':
                    // Handle car data
                    $carData = [
                        'deal_id' => $deal->id,
                        'capacity' => $request->car_capacity,
                        'transmission' => $request->transmission,
                        'fuel' => $request->fuel,
                        'air_condition' => $request->air_condition ?? false,
                        'gps' => $request->gps ?? false
                    ];

                    // Handle car contract document
                    if ($request->hasFile('car_contract_document')) {
                        $contractPath = $request->file('car_contract_document')->store('deals/contracts', 'public');
                        $carData['terms'] = $contractPath;
                    }

                    Car::create($carData);
                    break;

                case 'hotel':
                case 'apartment':
                    // For hotels and apartments, only features are handled above
                    // No additional type-specific data needed
                    break;
            }

            DB::commit();

            return redirect()->route('admin.hotels')->with('success', 'Deal created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create deal: ' . $e->getMessage())->withInput();
        }
    }


    public function editHotel($id)
    {
        return view('admin.pages.hotels.edit', compact('id'));
    }

   

    public function deleteHotel($id)
    {
        // Add hotel deletion logic here
        return redirect()->route('admin.hotels')->with('success', 'Hotel deleted successfully!');
    }

    // Hotel Rooms Management
    public function hotelRooms($hotel_id)
    {
        return view('admin.pages.hotels.rooms.index', compact('hotel_id'));
    }

    public function createHotelRoom($hotel_id)
    {
        return view('admin.pages.hotels.rooms.create', compact('hotel_id'));
    }

    public function storeHotelRoom(Request $request, $hotel_id)
    {
        // Add hotel room creation logic here
        return redirect()->route('admin.hotels.rooms', $hotel_id)->with('success', 'Hotel room created successfully!');
    }

    public function editHotelRoom($hotel_id, $room_id)
    {
        return view('admin.pages.hotels.rooms.edit', compact('hotel_id', 'room_id'));
    }

    public function updateHotelRoom(Request $request, $hotel_id, $room_id)
    {
        // Add hotel room update logic here
        return redirect()->route('admin.hotels.rooms', $hotel_id)->with('success', 'Hotel room updated successfully!');
    }

    public function deleteHotelRoom($hotel_id, $room_id)
    {
        // Add hotel room deletion logic here
        return redirect()->route('admin.hotels.rooms', $hotel_id)->with('success', 'Hotel room deleted successfully!');
    }

    public function viewHotelRoom($hotel_id, $room_id)
    {
        return view('admin.pages.hotels.rooms.view', compact('hotel_id', 'room_id'));
    }

    public function updateRoomAvailability(Request $request, $hotel_id, $room_id)
    {
        // Add room availability update logic here
        return redirect()->route('admin.hotels.rooms.view', [$hotel_id, $room_id])->with('success', 'Room availability updated successfully!');
    }
}
