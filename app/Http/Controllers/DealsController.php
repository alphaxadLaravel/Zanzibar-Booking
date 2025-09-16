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
use App\Models\Room;
use App\Models\RoomPhotos;
use Hashids\Hashids;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DealsController extends Controller
{
    // Hotels Management
    public function hotels()
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $hotels = Deal::with('category')
            ->where('type', 'hotel')
            ->orderBy('created_at', 'desc')
            ->get();


        return view('admin.pages.hotels.index', compact('hotels', 'hashids'));
    }

    // manageHotel
    public function manageHotel($id)
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $hotelId = $hashids->decode($id)[0];

        $hotel = Deal::with(['category', 'features', 'photos'])
            ->where('type', 'hotel')
            ->find($hotelId);

        if (!$hotel) {
            return redirect()->back()->with('error', 'Hotel not found');
        }

        // Get rooms for this hotel
        $rooms = Room::with('photos')
            ->where('deal_id', $hotelId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pages.hotels.manage_hotel', compact('hotel', 'hotelId', 'rooms', 'hashids'));
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
            'cover_photo' => 'required|image|mimes:jpeg,png,jpg,gif',
            'other_images.*' => 'required|image|mimes:jpeg,png,jpg,gif',
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
                    'tour_includes.*' => 'integer|exists:features,id',
                    'tour_excludes' => 'nullable|array',
                    'tour_excludes.*' => 'integer|exists:features,id',
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
                        foreach ($request->tour_includes as $featureId) {
                            // Handle both integer IDs and string names
                            if (is_numeric($featureId)) {
                                $featureId = (int) $featureId;
                            } else {
                                // If it's a string (feature name), find the feature by name
                                $feature = Features::where('name', $featureId)->where('type', 'include')->first();
                                if ($feature) {
                                    $featureId = $feature->id;
                                } else {
                                    continue; // Skip invalid features
                                }
                            }
                            
                            TourInclude::create([
                                'deal_id' => $deal->id,
                                'feature_id' => $featureId,
                                'type' => 'include'
                            ]);
                        }
                    }

                    // Handle tour excludes
                    if ($request->has('tour_excludes')) {
                        foreach ($request->tour_excludes as $featureId) {
                            // Handle both integer IDs and string names
                            if (is_numeric($featureId)) {
                                $featureId = (int) $featureId;
                            } else {
                                // If it's a string (feature name), find the feature by name
                                $feature = Features::where('name', $featureId)->where('type', 'exclude')->first();
                                if ($feature) {
                                    $featureId = $feature->id;
                                } else {
                                    continue; // Skip invalid features
                                }
                            }
                            
                            TourInclude::create([
                                'deal_id' => $deal->id,
                                'feature_id' => $featureId,
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

            // Redirect based on deal type
            $redirectRoute = match($type) {
                'car' => 'admin.cars',
                'tour' => 'admin.tours',
                'hotel', 'apartment' => 'admin.hotels',
                default => 'admin.hotels'
            };
            
            return redirect()->route($redirectRoute)->with('success', 'Deal created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create deal: ' . $e->getMessage())->withInput();
        }
    }


    public function editDeal($id, $type)
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $dealId = $hashids->decode($id)[0];


        // Find deal with relationships
        $deal = Deal::with(['category', 'features', 'photos'])
            ->where('type', $type)
            ->find($dealId);

        if (!$deal) {
            return redirect()->back()->with('error', 'Deal not found');
        }

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

        // Get type-specific data
        $typeSpecificData = [];
        switch ($type) {
            case 'tour':
                $tourData = Tours::where('deal_id', $deal->id)->first();
                $tourIncludeData = TourInclude::with('feature')->where('deal_id', $deal->id)->where('type', 'include')->get();
                $tourExcludeData = TourInclude::with('feature')->where('deal_id', $deal->id)->where('type', 'exclude')->get();

                $typeSpecificData = [
                    'tour' => $tourData,
                    'tour_includes' => $tourIncludeData,
                    'tour_excludes' => $tourExcludeData
                ];
                break;

            case 'car':
                $carData = Car::where('deal_id', $deal->id)->first();
                $typeSpecificData = ['car' => $carData];
                break;
        }

        return view('admin.pages.manage_deal', compact('deal', 'type', 'categories', 'features', 'tourIncludes', 'tourExcludes', 'typeSpecificData'));
    }

    // Update Deal
    public function updateDeal(Request $request, $id, $type)
    {
        $dealId = $id;

        // Find the deal
        $deal = Deal::where('type', $type)->find($dealId);

        if (!$deal) {
            return redirect()->back()->with('error', 'Deal not found');
        }

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
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'other_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
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
                    'tour_includes.*' => 'integer|exists:features,id',
                    'tour_excludes' => 'nullable|array',
                    'tour_excludes.*' => 'integer|exists:features,id',
                ];
                break;
        }

        // Merge base rules with type-specific rules
        $validationRules = array_merge($baseRules, $typeSpecificRules);

        $request->validate($validationRules);

        try {
            DB::beginTransaction();

            // Handle cover photo upload
            $coverPhotoPath = $deal->cover_photo; // Keep existing if no new upload
            if ($request->hasFile('cover_photo')) {
                // Delete old cover photo if exists
                if ($deal->cover_photo && Storage::disk('public')->exists($deal->cover_photo)) {
                    Storage::disk('public')->delete($deal->cover_photo);
                }
                $coverPhotoPath = $request->file('cover_photo')->store('deals/cover', 'public');
            }

            // Update the deal
            $deal->update([
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
                'status' => $request->status === 'publish' ? 1 : 0
            ]);

            // Handle features
            if ($request->has('features')) {
                $deal->features()->sync($request->features);
            } else {
                $deal->features()->detach();
            }

            // Handle other images
            if ($request->hasFile('other_images')) {
                // Delete existing photos
                foreach ($deal->photos as $photo) {
                    if (Storage::disk('public')->exists($photo->photo)) {
                        Storage::disk('public')->delete($photo->photo);
                    }
                    $photo->delete();
                }

                // Add new photos
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
                    // Update tour data
                    $tourData = Tours::where('deal_id', $deal->id)->first();
                    if ($tourData) {
                        $tourData->update([
                            'period' => $request->tour_period,
                            'max_people' => $request->max_people,
                            'adult_price' => $request->adult_price,
                            'child_price' => $request->child_price
                        ]);
                    } else {
                        Tours::create([
                            'deal_id' => $deal->id,
                            'period' => $request->tour_period,
                            'max_people' => $request->max_people,
                            'adult_price' => $request->adult_price,
                            'child_price' => $request->child_price
                        ]);
                    }

                    // Update tour includes
                    TourInclude::where('deal_id', $deal->id)->where('type', 'include')->delete();
                    if ($request->has('tour_includes')) {
                        foreach ($request->tour_includes as $featureId) {
                            // Handle both integer IDs and string names
                            if (is_numeric($featureId)) {
                                $featureId = (int) $featureId;
                            } else {
                                // If it's a string (feature name), find the feature by name
                                $feature = Features::where('name', $featureId)->where('type', 'include')->first();
                                if ($feature) {
                                    $featureId = $feature->id;
                                } else {
                                    continue; // Skip invalid features
                                }
                            }
                            
                            TourInclude::create([
                                'deal_id' => $deal->id,
                                'feature_id' => $featureId,
                                'type' => 'include'
                            ]);
                        }
                    }

                    // Update tour excludes
                    TourInclude::where('deal_id', $deal->id)->where('type', 'exclude')->delete();
                    if ($request->has('tour_excludes')) {
                        foreach ($request->tour_excludes as $featureId) {
                            // Handle both integer IDs and string names
                            if (is_numeric($featureId)) {
                                $featureId = (int) $featureId;
                            } else {
                                // If it's a string (feature name), find the feature by name
                                $feature = Features::where('name', $featureId)->where('type', 'exclude')->first();
                                if ($feature) {
                                    $featureId = $feature->id;
                                } else {
                                    continue; // Skip invalid features
                                }
                            }
                            
                            TourInclude::create([
                                'deal_id' => $deal->id,
                                'feature_id' => $featureId,
                                'type' => 'exclude'
                            ]);
                        }
                    }
                    break;

                case 'car':
                    // Update car data
                    $carData = Car::where('deal_id', $deal->id)->first();
                    $carUpdateData = [
                        'capacity' => $request->car_capacity,
                        'transmission' => $request->transmission,
                        'fuel' => $request->fuel,
                        'air_condition' => $request->air_condition ?? false,
                        'gps' => $request->gps ?? false
                    ];

                    // Handle car contract document
                    if ($request->hasFile('car_contract_document')) {
                        // Delete old contract if exists
                        if ($carData && $carData->terms && Storage::disk('public')->exists($carData->terms)) {
                            Storage::disk('public')->delete($carData->terms);
                        }
                        $contractPath = $request->file('car_contract_document')->store('deals/contracts', 'public');
                        $carUpdateData['terms'] = $contractPath;
                    }

                    if ($carData) {
                        $carData->update($carUpdateData);
                    } else {
                        $carUpdateData['deal_id'] = $deal->id;
                        Car::create($carUpdateData);
                    }
                    break;

                case 'hotel':
                case 'apartment':
                    // For hotels and apartments, only features are handled above
                    // No additional type-specific data needed
                    break;
            }

            DB::commit();

            // Redirect based on deal type
            $redirectRoute = match($type) {
                'car' => 'admin.cars',
                'tour' => 'admin.tours',
                'hotel', 'apartment' => 'admin.hotels',
                default => 'admin.hotels'
            };
            
            return redirect()->route($redirectRoute)->with('success', 'Deal updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update deal: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteHotel($id)
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $decodedHotelId = $hashids->decode($id)[0];

        $hotel = Deal::where('type', 'hotel')->find($decodedHotelId);

        if (!$hotel) {
            return redirect()->back()->with('error', 'Hotel not found');
        }

        try {
            DB::beginTransaction();

            // Get all rooms for this hotel
            $rooms = Room::where('deal_id', $decodedHotelId)->get();

            // Delete room photos and rooms
            foreach ($rooms as $room) {
                // Delete room cover photo
                if ($room->cover_photo && Storage::disk('public')->exists($room->cover_photo)) {
                    Storage::disk('public')->delete($room->cover_photo);
                }

                // Delete room photos
                foreach ($room->photos as $photo) {
                    if (Storage::disk('public')->exists($photo->photo)) {
                        Storage::disk('public')->delete($photo->photo);
                    }
                    $photo->delete();
                }

                // Delete the room
                $room->delete();
            }

            // Delete hotel cover photo
            if ($hotel->cover_photo && Storage::disk('public')->exists($hotel->cover_photo)) {
                Storage::disk('public')->delete($hotel->cover_photo);
            }

            // Delete hotel photos
            foreach ($hotel->photos as $photo) {
                if (Storage::disk('public')->exists($photo->photo)) {
                    Storage::disk('public')->delete($photo->photo);
                }
                $photo->delete();
            }

            // Detach features
            $hotel->features()->detach();

            // Delete the hotel
            $hotel->delete();

            DB::commit();

            return redirect()->route('admin.hotels')->with('success', 'Hotel and all associated data deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete hotel: ' . $e->getMessage());
        }
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

    // Room CRUD Methods
    public function storeRoom(Request $request, $hotel_id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'number_of_rooms' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'people' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'availability' => 'required|in:0,1',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'other_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'description' => 'nullable|string'
        ]);

        try {
            DB::beginTransaction();

            // Handle cover photo upload
            $coverPhotoPath = null;
            if ($request->hasFile('cover_photo')) {
                $coverPhotoPath = $request->file('cover_photo')->store('rooms/cover', 'public');
            }

            // Create the room
            $room = Room::create([
                'deal_id' => $hotel_id,
                'title' => $request->title,
                'number_of_rooms' => $request->number_of_rooms,
                'price' => $request->price,
                'people' => $request->people,
                'beds' => $request->beds,
                'availability' => (bool) $request->availability,
                'cover_photo' => $coverPhotoPath,
                'description' => $request->description,
                'status' => 1
            ]);

            // Handle other images
            if ($request->hasFile('other_images')) {
                foreach ($request->file('other_images') as $image) {
                    $imagePath = $image->store('rooms/photos', 'public');
                    RoomPhotos::create([
                        'room_id' => $room->id,
                        'photo' => $imagePath
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Room created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create room: ' . $e->getMessage())->withInput();
        }
    }

    public function editRoom($hotel_id, $room_id)
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $decodedHotelId = $hashids->decode($hotel_id)[0];
        $decodedRoomId = $hashids->decode($room_id)[0];

        $hotel = Deal::where('type', 'hotel')->find($decodedHotelId);
        $room = Room::with('photos')->find($decodedRoomId);

        if (!$hotel || !$room || $room->deal_id != $decodedHotelId) {
            return redirect()->back()->with('error', 'Room not found');
        }

        return response()->json([
            'room' => $room,
            'photos' => $room->photos
        ]);
    }

    public function updateRoom(Request $request, $hotel_id, $room_id)
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $decodedHotelId = $hashids->decode($hotel_id)[0];
        $decodedRoomId = $hashids->decode($room_id)[0];

        $request->validate([
            'title' => 'required|string|max:255',
            'number_of_rooms' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'people' => 'required|integer|min:1',
            'beds' => 'required|integer|min:1',
            'availability' => 'required|in:0,1',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'other_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'description' => 'nullable|string'
        ]);

        $room = Room::find($decodedRoomId);

        if (!$room || $room->deal_id != $decodedHotelId) {
            return redirect()->back()->with('error', 'Room not found');
        }

        try {
            DB::beginTransaction();

            // Handle cover photo upload
            $coverPhotoPath = $room->cover_photo; // Keep existing if no new upload
            if ($request->hasFile('cover_photo')) {
                // Delete old cover photo if exists
                if ($room->cover_photo && Storage::disk('public')->exists($room->cover_photo)) {
                    Storage::disk('public')->delete($room->cover_photo);
                }
                $coverPhotoPath = $request->file('cover_photo')->store('rooms/cover', 'public');
            }

            // Update the room
            $room->update([
                'title' => $request->title,
                'number_of_rooms' => $request->number_of_rooms,
                'price' => $request->price,
                'people' => $request->people,
                'beds' => $request->beds,
                'availability' => (bool) $request->availability,
                'cover_photo' => $coverPhotoPath,
                'description' => $request->description
            ]);

            // Handle other images
            if ($request->hasFile('other_images')) {
                // Delete existing photos
                foreach ($room->photos as $photo) {
                    if (Storage::disk('public')->exists($photo->photo)) {
                        Storage::disk('public')->delete($photo->photo);
                    }
                    $photo->delete();
                }

                // Add new photos
                foreach ($request->file('other_images') as $image) {
                    $imagePath = $image->store('rooms/photos', 'public');
                    RoomPhotos::create([
                        'room_id' => $room->id,
                        'photo' => $imagePath
                    ]);
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Room updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update room: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteRoom($hotel_id, $room_id)
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $decodedHotelId = $hashids->decode($hotel_id)[0];
        $decodedRoomId = $hashids->decode($room_id)[0];

        $room = Room::find($decodedRoomId);

        if (!$room || $room->deal_id != $decodedHotelId) {
            return redirect()->back()->with('error', 'Room not found');
        }

        try {
            DB::beginTransaction();

            // Delete cover photo
            if ($room->cover_photo && Storage::disk('public')->exists($room->cover_photo)) {
                Storage::disk('public')->delete($room->cover_photo);
            }

            // Delete other photos
            foreach ($room->photos as $photo) {
                if (Storage::disk('public')->exists($photo->photo)) {
                    Storage::disk('public')->delete($photo->photo);
                }
                $photo->delete();
            }

            // Delete room
            $room->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Room deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete room: ' . $e->getMessage());
        }
    }

    // Cars Management
    public function cars()
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);

        $cars = Deal::with(['car', 'category', 'photos'])
            ->where('type', 'car')
            ->whereHas('car') // Only get deals that have car data
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.pages.cars.index', compact('cars', 'hashids'));
    }

    public function createCar()
    {
        // Redirect to the existing manageDeal method for car type
        return $this->manageDeal('car');
    }

    public function storeCar(Request $request)
    {
        // Redirect to the existing storeDeal method for car type
        return $this->storeDeal($request, 'car');
    }

    public function editCar($id)
    {
        // Redirect to the existing editDeal method for car type
        return $this->editDeal($id, 'car');
    }

    public function updateCar(Request $request, $id)
    {
        // Redirect to the existing updateDeal method for car type
        return $this->updateDeal($request, $id, 'car');
    }

    public function deleteCar($id)
    {
        try {
            $deal = Deal::where('type', 'car')->findOrFail($id);
            
            // Delete associated car data
            if ($deal->car) {
                $deal->car->delete();
            }
            
            // Delete associated photos
            foreach ($deal->photos as $photo) {
                if (Storage::exists($photo->photo)) {
                    Storage::delete($photo->photo);
                }
                $photo->delete();
            }
            
            // Delete cover photo
            if ($deal->cover_photo && Storage::exists($deal->cover_photo)) {
                Storage::delete($deal->cover_photo);
            }
            
            // Delete the deal
            $deal->delete();
            
            return redirect()->route('admin.cars')->with('success', 'Car deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.cars')->with('error', 'Failed to delete car: ' . $e->getMessage());
        }
    }

    // Tours Management
    public function tours()
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        
        $tours = Deal::with(['category', 'tours', 'photos'])
            ->where('type', 'tour')
            ->whereHas('tours') // Only get deals that have tour data
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.pages.tours.index', compact('tours', 'hashids'));
    }

    public function createTour()
    {
        // Redirect to the existing manageDeal method for tour type
        return $this->manageDeal('tour');
    }

    public function storeTour(Request $request)
    {
        // Redirect to the existing storeDeal method for tour type
        return $this->storeDeal($request, 'tour');
    }

    public function editTour($id)
    {
        // Redirect to the existing editDeal method for tour type
        return $this->editDeal($id, 'tour');
    }

    public function updateTour(Request $request, $id)
    {
        // Redirect to the existing updateDeal method for tour type
        return $this->updateDeal($request, $id, 'tour');
    }

    public function deleteTour($id)
    {
        $hashids = new Hashids('MchungajiZanzibarBookings', 10);
        $decodedTourId = $hashids->decode($id)[0];

        $tour = Deal::where('type', 'tour')->find($decodedTourId);

        if (!$tour) {
            return redirect()->back()->with('error', 'Tour not found');
        }

        try {
            DB::beginTransaction();

            // Delete associated tour data
            if ($tour->tours) {
                $tour->tours->delete();
            }
            
            // Delete associated tour includes/excludes
            TourInclude::where('deal_id', $tour->id)->delete();
            
            // Delete associated photos
            foreach ($tour->photos as $photo) {
                if (Storage::disk('public')->exists($photo->photo)) {
                    Storage::disk('public')->delete($photo->photo);
                }
                $photo->delete();
            }
            
            // Delete cover photo
            if ($tour->cover_photo && Storage::disk('public')->exists($tour->cover_photo)) {
                Storage::disk('public')->delete($tour->cover_photo);
            }
            
            // Detach features
            $tour->features()->detach();
            
            // Delete the tour deal
            $tour->delete();

            DB::commit();

            return redirect()->route('admin.tours')->with('success', 'Tour deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete tour: ' . $e->getMessage());
        }
    }
}
