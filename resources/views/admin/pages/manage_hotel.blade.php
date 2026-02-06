@extends('admin.layouts.app')

@section('title', 'Manage Hotel')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h4 class="page-title mb-0">Hotel Rooms Management</h4>
        </div>
        <div>
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Hotels</a></li>
                <li class="breadcrumb-item active">Hotel Rooms</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body d-flex align-items-center justify-content-between" style="gap: 1.5rem;">
                    <div style="flex: 0 0 120px; max-width: 120px;">
                        @if(isset($hotel) && $hotel->cover_photo)
                        <div style="width: 100px; height: 100px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ asset('storage/' . $hotel->cover_photo) }}" alt="{{ $hotel->title }}"
                                class="img-fluid rounded" style="width: 100px; height: 100px; object-fit: cover; object-position: center;">
                        </div>
                        @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="height: 100px;">
                            <i class="ti ti-hotel text-muted" style="font-size: 2.5rem;"></i>
                        </div>
                        @endif
                    </div>
                    <!-- Hotel Info -->
                    <div style="flex: 1 1 auto;">
                        <h4 class="mb-1">{{ $hotel->title ?? 'Hotel Name' }}</h4>
                        <p class="mb-1 text-muted">
                            <i class="ti ti-map-pin me-1"></i>
                            {{ $hotel->location ?? 'Location not specified' }}
                        </p>
                        <p class="mb-0 mt-2">
                            <strong>${{ number_format($hotel->base_price ?? 0, 2) }}</strong> / night
                        </p>
                    </div>
                    <div class="d-flex align-items-start" style="flex: 0 0 auto; gap: 0.5rem;">
                        <button type="button" class="btn btn-outline-danger btn-sm"
                            onclick="deleteHotel('{{ $hashids->encode($hotelId) }}')">
                            <i class="ti ti-trash"></i> Delete Hotel
                        </button>
                        <a href="{{ route('view-hotel', $hashids->encode($hotel->id)) }}" target="_blank" class="btn btn-outline-info btn-sm">
                            Preview Hotel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12">
            <div class="card">

                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="card-title mb-0">{{ $hotel->title }} - Rooms</h5>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#addRoomModal">
                            <i class="ti ti-plus"></i> Add New Room
                        </button>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cover</th>
                                    <th>Room Title</th>
                                    <th>Capacity</th>
                                    <th>Beds</th>
                                    <th>Price</th>
                                    <th>Availability</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rooms as $index => $room)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($room->cover_photo)
                                        <img src="{{ asset('storage/' . $room->cover_photo) }}" alt="{{ $room->title }}"
                                            class="img-fluid rounded"
                                            style="width: 50px; height: 40px; object-fit: cover;">
                                        @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                            style="width: 50px; height: 40px;">
                                            <i class="ti ti-bed text-muted" style="font-size: 1rem;"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $room->title }}</div>
                                        <small class="text-muted">{{ $room->number_of_rooms }} room(s)</small>
                                    </td>
                                    <td>{{ $room->people }} people</td>
                                    <td>{{ $room->beds }} bed(s)</td>
                                    <td>
                                        <span class="fw-bold text-success">${{ number_format($room->price ?? $room->price_per_person ?? 0, 2) }}</span>
                                        <small class="d-block text-muted">{{ $room->price_type === 'per_person_per_night' ? 'per person/night' : 'per night' }}</small>
                                    </td>
                                    <td>
                                        @if($room->availability)
                                        <span class="badge bg-success">Available</span>
                                        @else
                                        <span class="badge bg-warning">Occupied</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary me-1"
                                            onclick="editRoom('{{ $hashids->encode($room->id) }}')">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>

                                        <div class="modal fade" id="addRoomModal" tabindex="-1"
                                            aria-labelledby="addRoomModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary text-white">
                                                        <h5 class="modal-title" id="addRoomModalLabel">Add New Room</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form id="addRoomForm" method="POST"
                                                        action="{{ route('admin.rooms.store', $hotelId) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="row g-3">
                                                                <!-- Room Title -->
                                                                <div class="col-md-6">
                                                                    <label for="room_title" class="form-label">Room
                                                                        Title <span class="text-danger">*</span></label>
                                                                    <input type="text" class="form-control"
                                                                        id="room_title" name="title" required
                                                                        placeholder="e.g., Deluxe Suite, Standard Room">
                                                                </div>

                                                                <!-- Number of Rooms -->
                                                                <div class="col-md-6">
                                                                    <label for="number_of_rooms"
                                                                        class="form-label">Number of Rooms <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control"
                                                                        id="number_of_rooms" name="number_of_rooms"
                                                                        min="1" value="1" required>
                                                                </div>

                                                                <!-- Price Type -->
                                                                <div class="col-md-6">
                                                                    <label for="room_price_type" class="form-label">Price Type <span class="text-danger">*</span></label>
                                                                    <select class="form-select" id="room_price_type" name="price_type" required onchange="togglePriceFields('add')">
                                                                        <option value="per_night">Price Per Night</option>
                                                                        <option value="per_person_per_night">Price Per Person per Night</option>
                                                                    </select>
                                                                </div>
                                                                <!-- Price per Night -->
                                                                <div class="col-md-6" id="add_price_per_night_wrap">
                                                                    <label for="room_price" class="form-label">Price per Night <span class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">$</span>
                                                                        <input type="number" class="form-control" id="room_price" name="price" step="0.01" min="0" required placeholder="0.00">
                                                                    </div>
                                                                </div>
                                                                <!-- Price per Person (shown when per_person_per_night) -->
                                                                <div class="col-md-6 d-none" id="add_price_per_person_wrap">
                                                                    <label for="room_price_per_person" class="form-label">Price per Person per Night <span class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">$</span>
                                                                        <input type="number" class="form-control" id="room_price_per_person" name="price_per_person" step="0.01" min="0" placeholder="0.00">
                                                                    </div>
                                                                </div>

                                                                <!-- People Capacity -->
                                                                <div class="col-md-6">
                                                                    <label for="people_capacity"
                                                                        class="form-label">People Capacity <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control"
                                                                        id="people_capacity" name="people" min="1"
                                                                        value="1" required>
                                                                </div>

                                                                <!-- Number of Beds -->
                                                                <div class="col-md-6">
                                                                    <label for="number_of_beds"
                                                                        class="form-label">Number of Beds <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="number" class="form-control"
                                                                        id="number_of_beds" name="beds" min="1"
                                                                        value="1" required>
                                                                </div>

                                                                <!-- Availability -->
                                                                <div class="col-md-6">
                                                                    <label for="room_availability"
                                                                        class="form-label">Availability</label>
                                                                    <select class="form-select" id="room_availability"
                                                                        name="availability">
                                                                        <option value="1" selected>Available</option>
                                                                        <option value="0">Not Available</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Cover Photo -->
                                                                <div class="col-md-12">
                                                                    <label for="room_cover_photo"
                                                                        class="form-label">Room Cover Photo</label>
                                                                    <input type="file" class="form-control"
                                                                        id="room_cover_photo" name="cover_photo"
                                                                        accept="image/*">
                                                                    <div class="mt-2" id="room_cover_photo_preview">
                                                                        <img src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM="
                                                                            alt="Cover Preview"
                                                                            style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                                                                    </div>
                                                                    <div class="form-text">Upload a cover photo for this
                                                                        room type</div>
                                                                </div>

                                                                <!-- Other Images -->
                                                                <div class="col-md-12">
                                                                    <label for="room_other_images"
                                                                        class="form-label">Other Images</label>
                                                                    <div class="border rounded p-3"
                                                                        style="min-height: 120px; background: #f8f9fa;">
                                                                        <input type="file" class="form-control mb-2"
                                                                            name="other_images[]" id="room_other_images"
                                                                            accept="image/*" multiple>
                                                                        <div class="d-flex flex-wrap gap-2"
                                                                            id="room_other_images_preview">
                                                                            @for ($i = 0; $i < 6; $i++) <img
                                                                                src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM="
                                                                                alt="Image {{ $i + 1 }}"
                                                                                style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                                                                                @endfor
                                                                        </div>
                                                                    </div>
                                                                    <small class="text-muted">You can drop or select
                                                                        multiple images.</small>
                                                                </div>

                                                                <!-- Price Intervals -->
                                                                <div class="col-12">
                                                                    <label class="form-label">Price Intervals (Seasonal Pricing)</label>
                                                                    <div id="add_price_intervals" class="border rounded p-3 bg-light">
                                                                        <p class="text-muted small mb-2">Add different prices for specific date ranges (e.g. Jan-Mar $90, Christmas $150)</p>
                                                                        <div id="add_interval_rows"></div>
                                                                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addIntervalRow('add')">
                                                                            <i class="ti ti-plus"></i> Add Interval
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <!-- Room Description -->
                                                                <div class="col-12">
                                                                    <label for="room_description"
                                                                        class="form-label">Room Description</label>
                                                                    <div>
                                                                        <textarea id="room_description" rows="4"
                                                                            class="form-control d-none"
                                                                            name="description"
                                                                            placeholder="Describe the room features, amenities, and what makes it special...">{{ old('description') }}</textarea>
                                                                        <div id="room-description-editor"></div>
                                                                    </div>
                                                                    <div class="form-text">
                                                                        <i class="ti ti-info-circle me-1"></i>
                                                                        You can write detailed descriptions with rich
                                                                        text formatting.
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-primary">
                                                                <i class="ti ti-plus"></i> Add Room
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="deleteRoom('{{ $hashids->encode($room->id) }}')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-bed" style="font-size: 2rem;"></i>
                                            <div class="mt-2">No rooms added yet</div>
                                            <small>Click "Add New Room" to get started</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nearby Deals Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <h5 class="card-title mb-0">Nearby Tours & Hotels</h5>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#nearbyDealsModal">
                            <i class="ti ti-plus"></i> Add Nearby Deals
                        </button>
                    </div>
                    <hr>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($nearbyDeals as $index => $near)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($near->nearDeal->cover_photo)
                                        <img src="{{ asset('storage/' . $near->nearDeal->cover_photo) }}" alt="{{ $near->nearDeal->title }}"
                                            class="img-fluid rounded" style="width: 50px; height: 40px; object-fit: cover;">
                                        @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 40px;">
                                            <i class="ti ti-photo text-muted" style="font-size: 1rem;"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $near->nearDeal->title }}</div>
                                        <small class="text-muted">{{ ucfirst($near->nearDeal->type) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $near->type == 'package' ? 'info' : ($near->type == 'activity' ? 'warning' : 'primary') }}">
                                            {{ ucfirst($near->type) }}
                                        </span>
                                    </td>
                                    <td>{{ $near->nearDeal->location ?? 'Not specified' }}</td>
                                    <td>
                                        <span class="fw-bold text-success">${{ number_format($near->nearDeal->base_price, 2) }}</span>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="showRemoveNearbyDealModal('{{ $near->id }}', '{{ $near->nearDeal->title }}')">
                                            <i class="ti ti-trash"></i> Remove
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="ti ti-map-pin" style="font-size: 2rem;"></i>
                                            <div class="mt-2">No nearby deals added yet</div>
                                            <small>Click "Add Nearby Deals" to get started</small>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Room Modal -->
<div class="modal fade" id="editRoomModal" tabindex="-1" aria-labelledby="editRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="editRoomModalLabel">Edit Room</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="editRoomForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Room Title -->
                        <div class="col-md-6">
                            <label for="edit_room_title" class="form-label">Room Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_room_title" name="title" required
                                placeholder="e.g., Deluxe Suite, Standard Room">
                        </div>

                        <!-- Number of Rooms -->
                        <div class="col-md-6">
                            <label for="edit_number_of_rooms" class="form-label">Number of Rooms <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_number_of_rooms" name="number_of_rooms"
                                min="1" value="1" required>
                        </div>

                        <!-- Price Type -->
                        <div class="col-md-6">
                            <label for="edit_room_price_type" class="form-label">Price Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_room_price_type" name="price_type" required onchange="togglePriceFields('edit')">
                                <option value="per_night">Price Per Night</option>
                                <option value="per_person_per_night">Price Per Person per Night</option>
                            </select>
                        </div>
                        <!-- Price per Night -->
                        <div class="col-md-6" id="edit_price_per_night_wrap">
                            <label for="edit_room_price" class="form-label">Price per Night <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="edit_room_price" name="price" step="0.01" min="0" required placeholder="0.00">
                            </div>
                        </div>
                        <!-- Price per Person -->
                        <div class="col-md-6 d-none" id="edit_price_per_person_wrap">
                            <label for="edit_room_price_per_person" class="form-label">Price per Person per Night <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="edit_room_price_per_person" name="price_per_person" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>

                        <!-- People Capacity -->
                        <div class="col-md-6">
                            <label for="edit_people_capacity" class="form-label">People Capacity <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_people_capacity" name="people" min="1"
                                value="1" required>
                        </div>

                        <!-- Number of Beds -->
                        <div class="col-md-6">
                            <label for="edit_number_of_beds" class="form-label">Number of Beds <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_number_of_beds" name="beds" min="1"
                                value="1" required>
                        </div>

                        <!-- Availability -->
                        <div class="col-md-6">
                            <label for="edit_room_availability" class="form-label">Availability</label>
                            <select class="form-select" id="edit_room_availability" name="availability">
                                <option value="1">Available</option>
                                <option value="0">Not Available</option>
                            </select>
                        </div>

                        <!-- Cover Photo -->
                        <div class="col-md-12">
                            <label for="edit_room_cover_photo" class="form-label">Room Cover Photo</label>
                            <input type="file" class="form-control" id="edit_room_cover_photo" name="cover_photo"
                                accept="image/*">
                            <div class="mt-2" id="edit_room_cover_photo_preview">
                                <img src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM="
                                    alt="Cover Preview"
                                    style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                            </div>
                            <div class="form-text">Upload a new cover photo to replace the current one</div>
                        </div>

                        <!-- Other Images -->
                        <div class="col-md-12">
                            <label for="edit_room_other_images" class="form-label">Other Images</label>
                            <div class="border rounded p-3" style="min-height: 120px; background: #f8f9fa;">
                                <input type="file" class="form-control mb-2" name="other_images[]"
                                    id="edit_room_other_images" accept="image/*" multiple>
                                <div class="d-flex flex-wrap gap-2" id="edit_room_other_images_preview">
                                    <!-- Existing images will be loaded here -->
                                </div>
                            </div>
                            <small class="text-muted">Upload new images to replace existing ones. Leave empty to keep
                                current images.</small>
                        </div>

                        <!-- Price Intervals -->
                        <div class="col-12">
                            <label class="form-label">Price Intervals (Seasonal Pricing)</label>
                            <div id="edit_price_intervals" class="border rounded p-3 bg-light">
                                <p class="text-muted small mb-2">Add different prices for specific date ranges (e.g. Jan-Mar $90, Christmas $150)</p>
                                <div id="edit_interval_rows"></div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addIntervalRow('edit')">
                                    <i class="ti ti-plus"></i> Add Interval
                                </button>
                            </div>
                        </div>

                        <!-- Room Description -->
                        <div class="col-12">
                            <label for="edit_room_description" class="form-label">Room Description</label>
                            <div>
                                <textarea id="edit_room_description" rows="4" class="form-control d-none"
                                    name="description"
                                    placeholder="Describe the room features, amenities, and what makes it special..."></textarea>
                                <div id="edit-room-description-editor"></div>
                            </div>
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                You can write detailed descriptions with rich text formatting.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-edit"></i> Update Room
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="ti ti-alert-triangle me-2"></i>Confirm Room Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="ti ti-trash" style="font-size: 3rem; color: #dc3545;"></i>
                </div>
                <p class="text-center mb-3">Are you sure you want to delete this room?</p>
                <div class="alert alert-warning" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone. All room data, photos, and associated bookings will be permanently deleted.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Cancel
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>Delete Room
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addRoomModal" tabindex="-1" aria-labelledby="addRoomModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addRoomModalLabel">Add New Room</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <form id="addRoomForm" method="POST" action="{{ route('admin.rooms.store', $hotelId) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Room Title -->
                        <div class="col-md-6">
                            <label for="room_title" class="form-label">Room Title <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="room_title" name="title" required
                                placeholder="e.g., Deluxe Suite, Standard Room">
                        </div>

                        <!-- Number of Rooms -->
                        <div class="col-md-6">
                            <label for="number_of_rooms" class="form-label">Number of Rooms <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="number_of_rooms" name="number_of_rooms"
                                min="1" value="1" required>
                        </div>

                        <!-- Price Type -->
                        <div class="col-md-6">
                            <label for="room_price_type" class="form-label">Price Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="room_price_type" name="price_type" required onchange="togglePriceFields('add')">
                                <option value="per_night">Price Per Night</option>
                                <option value="per_person_per_night">Price Per Person per Night</option>
                            </select>
                        </div>
                        <!-- Price per Night -->
                        <div class="col-md-6" id="add_price_per_night_wrap">
                            <label for="room_price" class="form-label">Price per Night <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="room_price" name="price" step="0.01" min="0" required placeholder="0.00">
                            </div>
                        </div>
                        <!-- Price per Person -->
                        <div class="col-md-6 d-none" id="add_price_per_person_wrap">
                            <label for="room_price_per_person" class="form-label">Price per Person per Night</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="room_price_per_person" name="price_per_person" step="0.01" min="0" placeholder="0.00">
                            </div>
                        </div>

                        <!-- People Capacity -->
                        <div class="col-md-6">
                            <label for="people_capacity" class="form-label">People Capacity <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="people_capacity" name="people" min="1"
                                value="1" required>
                        </div>

                        <!-- Number of Beds -->
                        <div class="col-md-6">
                            <label for="number_of_beds" class="form-label">Number of Beds <span
                                    class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="number_of_beds" name="beds" min="1" value="1"
                                required>
                        </div>

                        <!-- Availability -->
                        <div class="col-md-6">
                            <label for="room_availability" class="form-label">Availability</label>
                            <select class="form-select" id="room_availability" name="availability">
                                <option value="1" selected>Available</option>
                                <option value="0">Not Available</option>
                            </select>
                        </div>

                        <!-- Cover Photo -->
                        <div class="col-md-12">
                            <label for="room_cover_photo" class="form-label">Room Cover Photo</label>
                            <input type="file" class="form-control" id="room_cover_photo" name="cover_photo"
                                accept="image/*">
                            <div class="mt-2" id="room_cover_photo_preview">
                                <img src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM="
                                    alt="Cover Preview"
                                    style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                            </div>
                            <div class="form-text">Upload a cover photo for this room type</div>
                        </div>

                        <!-- Other Images -->
                        <div class="col-md-12">
                            <label for="room_other_images" class="form-label">Other Images</label>
                            <div class="border rounded p-3" style="min-height: 120px; background: #f8f9fa;">
                                <input type="file" class="form-control mb-2" name="other_images[]"
                                    id="room_other_images" accept="image/*" multiple>
                                <div class="d-flex flex-wrap gap-2" id="room_other_images_preview">
                                    @for ($i = 0; $i < 6; $i++) <img
                                        src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM="
                                        alt="Image {{ $i + 1 }}"
                                        style="width:100px; height:75px; object-fit:cover; border-radius:4px;">
                                        @endfor
                                </div>
                            </div>
                            <small class="text-muted">You can drop or select multiple images.</small>
                        </div>

                        <!-- Price Intervals -->
                        <div class="col-12">
                            <label class="form-label">Price Intervals (Seasonal Pricing)</label>
                            <div id="add_price_intervals" class="border rounded p-3 bg-light">
                                <p class="text-muted small mb-2">Add different prices for specific date ranges (e.g. Jan-Mar $90, Christmas $150)</p>
                                <div id="add_interval_rows"></div>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addIntervalRow('add')">
                                    <i class="ti ti-plus"></i> Add Interval
                                </button>
                            </div>
                        </div>

                        <!-- Room Description -->
                        <div class="col-12">
                            <label for="room_description" class="form-label">Room Description</label>
                            <div>
                                <textarea id="room_description" rows="4" class="form-control d-none" name="description"
                                    placeholder="Describe the room features, amenities, and what makes it special...">{{ old('description') }}</textarea>
                                <div id="room-description-editor"></div>
                            </div>
                            <div class="form-text">
                                <i class="ti ti-info-circle me-1"></i>
                                You can write detailed descriptions with rich text formatting.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-plus"></i> Add Room
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Hotel Modal -->
<div class="modal fade" id="deleteHotelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="ti ti-alert-triangle me-2"></i>Confirm Hotel Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="ti ti-building-skyscraper" style="font-size: 3rem; color: #dc3545;"></i>
                </div>
                <p class="text-center mb-3">Are you sure you want to delete this hotel?</p>
                <div class="alert alert-danger" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>
                    <strong>Critical Warning:</strong> This action will permanently delete:
                    <ul class="mb-0 mt-2">
                        <li>Hotel information and details</li>
                        <li>All rooms and room configurations</li>
                        <li>Hotel photos and media files</li>
                        <li>Associated bookings and reservations</li>
                        <li>Reviews and ratings</li>
                    </ul>
                    <strong class="text-danger">This action cannot be undone!</strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Cancel
                </button>
                <form id="deleteHotelForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>Delete Hotel
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Nearby Deals Modal -->
<div class="modal fade" id="nearbyDealsModal" tabindex="-1" aria-labelledby="nearbyDealsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="nearbyDealsModalLabel">Add Nearby Deal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="nearbyDealsForm" method="POST" action="{{ route('admin.hotels.add-nearby', $hotelId) }}">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Deal Type Selection -->
                        <div class="col-12">
                            <label for="deal_type" class="form-label">Deal Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="deal_type" name="deal_type" required>
                                <option value="">Select deal type...</option>
                                <option value="package">Packages</option>
                                <option value="activity">Activities</option>
                                <option value="hotel">Hotels</option>
                                <option value="apartment">Apartments</option>
                            </select>
                        </div>

                        <!-- Deal Selection -->
                        <div class="col-12">
                            <label for="nearby_deal_id" class="form-label">Select Deal <span class="text-danger">*</span></label>
                            <select class="form-select" id="nearby_deal_id" name="nearby_deal_id" required>
                                <option value="">Select deal type first...</option>
                            </select>
                        </div>

                        <!-- Selected Deal Preview -->
                        <div class="col-12" id="deal-preview-container" style="display: none;">
                            <label class="form-label">Selected Deal Preview</label>
                            <div id="deal-preview" class="border rounded p-3 bg-light">
                                <!-- Preview content will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" id="add-deal-btn" disabled>
                        <i class="ti ti-plus"></i> Add Deal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Nearby Deal Modal -->
<div class="modal fade" id="removeNearbyDealModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="ti ti-alert-triangle me-2"></i>Confirm Nearby Deal Removal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="ti ti-trash" style="font-size: 3rem; color: #dc3545;"></i>
                </div>
                <p class="text-center mb-3">Are you sure you want to remove this nearby deal?</p>
                <div class="alert alert-warning" role="alert">
                    <i class="ti ti-alert-circle me-2"></i>
                    <strong>Warning:</strong> This action will remove the nearby deal association. The deal itself will not be deleted.
                </div>
                <div class="text-center">
                    <strong id="deal-title-to-remove"></strong>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="ti ti-x me-1"></i>Cancel
                </button>
                <form id="removeNearbyDealForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ti ti-trash me-1"></i>Remove Deal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
    // Room Quill editor config
    var roomQuillToolbarOptions = [
        [{ 'header': [1, 2, false] }],
        ['bold', 'italic', 'underline', 'link', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'align': [] }],
        ['clean']
    ];

    // Initialize Quill editors
    var roomDescriptionQuill = new Quill('#room-description-editor', {
        theme: 'snow',
        modules: {
            toolbar: roomQuillToolbarOptions
        }
    });

    var editRoomDescriptionQuill = new Quill('#edit-room-description-editor', {
        theme: 'snow',
        modules: {
            toolbar: roomQuillToolbarOptions
        }
    });

    // Set initial content from textarea
    roomDescriptionQuill.root.innerHTML = document.getElementById('room_description').value;

    // On form submit, update textarea values with Quill HTML
    document.getElementById('addRoomForm').addEventListener('submit', function(e) {
        document.getElementById('room_description').value = roomDescriptionQuill.root.innerHTML;
    });

    document.getElementById('editRoomForm').addEventListener('submit', function(e) {
        document.getElementById('edit_room_description').value = editRoomDescriptionQuill.root.innerHTML;
    });

    // Room cover photo preview
    document.getElementById('room_cover_photo').addEventListener('change', function(e) {
        const preview = document.getElementById('room_cover_photo_preview');
        preview.innerHTML = '';
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.style.width = '100px';
                img.style.height = '75px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '4px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Room other images preview
    document.getElementById('room_other_images').addEventListener('change', function(e) {
        const preview = document.getElementById('room_other_images_preview');
        preview.innerHTML = '';
        if (e.target.files) {
            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.style.width = '100px';
                    img.style.height = '75px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '4px';
                    img.classList.add('me-2');
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    });

    // Edit room cover photo preview
    document.getElementById('edit_room_cover_photo').addEventListener('change', function(e) {
        const preview = document.getElementById('edit_room_cover_photo_preview');
        preview.innerHTML = '';
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(ev) {
                const img = document.createElement('img');
                img.src = ev.target.result;
                img.style.width = '100px';
                img.style.height = '75px';
                img.style.objectFit = 'cover';
                img.style.borderRadius = '4px';
                preview.appendChild(img);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Edit room other images preview
    document.getElementById('edit_room_other_images').addEventListener('change', function(e) {
        const preview = document.getElementById('edit_room_other_images_preview');
        // Don't clear existing images, just add new ones
        if (e.target.files) {
            Array.from(e.target.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    const img = document.createElement('img');
                    img.src = ev.target.result;
                    img.style.width = '100px';
                    img.style.height = '75px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '4px';
                    img.classList.add('me-2');
                    preview.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    });

    // Price intervals and price type toggle
    let addIntervalIndex = 0;
    let editIntervalIndex = 0;

    function togglePriceFields(form) {
        const isPerPerson = (form === 'add' ? document.getElementById('room_price_type') : document.getElementById('edit_room_price_type'))?.value === 'per_person_per_night';
        if (form === 'add') {
            document.getElementById('add_price_per_night_wrap').classList.toggle('d-none', isPerPerson);
            document.getElementById('add_price_per_person_wrap').classList.toggle('d-none', !isPerPerson);
            document.getElementById('room_price').required = !isPerPerson;
            document.getElementById('room_price_per_person').required = isPerPerson;
        } else {
            document.getElementById('edit_price_per_night_wrap').classList.toggle('d-none', isPerPerson);
            document.getElementById('edit_price_per_person_wrap').classList.toggle('d-none', !isPerPerson);
            document.getElementById('edit_room_price').required = !isPerPerson;
            document.getElementById('edit_room_price_per_person').required = isPerPerson;
        }
    }

    function addIntervalRow(form) {
        const prefix = form === 'add' ? 'add' : 'edit';
        const idx = form === 'add' ? addIntervalIndex++ : editIntervalIndex++;
        const container = document.getElementById(prefix + '_interval_rows');
        const today = new Date().toISOString().split('T')[0];
        const minAttr = form === 'add' ? ` min="${today}"` : '';
        const row = document.createElement('div');
        row.className = 'row g-2 align-items-end mb-2 interval-row';
        row.dataset.rowIndex = idx;
        row.innerHTML = `
            <div class="col-md-3"><input type="date" class="form-control form-control-sm interval-date" data-type="start"${minAttr} name="price_intervals[${idx}][start_date]" placeholder="Start" required></div>
            <div class="col-md-3"><input type="date" class="form-control form-control-sm interval-date" data-type="end"${minAttr} name="price_intervals[${idx}][end_date]" placeholder="End" required></div>
            <div class="col-md-2"><input type="text" class="form-control form-control-sm" name="price_intervals[${idx}][label]" placeholder="Label (e.g. Christmas)"></div>
            <div class="col-md-2"><div class="input-group input-group-sm"><span class="input-group-text">$</span><input type="number" step="0.01" class="form-control" name="price_intervals[${idx}][price]" placeholder="Price" required></div></div>
            <div class="col-md-1"><button type="button" class="btn btn-sm btn-outline-danger" onclick="this.closest('.interval-row').remove()"><i class="ti ti-trash"></i></button></div>
        `;
        container.appendChild(row);
        row.querySelectorAll('.interval-date').forEach(input => {
            input.addEventListener('change', () => validateIntervalOverlap(container, row));
        });
    }

    function validateIntervalOverlap(container, currentRow) {
        const rows = container.querySelectorAll('.interval-row');
        const getRange = (row) => {
            const start = row.querySelector('input[data-type="start"]')?.value;
            const end = row.querySelector('input[data-type="end"]')?.value;
            return start && end ? { start, end, row } : null;
        };
        const ranges = Array.from(rows).map(getRange).filter(Boolean);
        const current = getRange(currentRow);
        if (!current) return;
        for (const r of ranges) {
            if (r.row === currentRow) continue;
            const overlap = (current.start <= r.end && current.end >= r.start);
            if (overlap) {
                alert('This date range overlaps with another interval. Please choose different dates.');
                currentRow.querySelector('input[data-type="start"]').value = '';
                currentRow.querySelector('input[data-type="end"]').value = '';
                return;
            }
        }
        if (current.start > current.end) {
            alert('End date must be after start date.');
            currentRow.querySelector('input[data-type="end"]').value = '';
        }
    }

    // Edit room function
    function editRoom(roomId) {
        // Show loading state
        const editModal = new bootstrap.Modal(document.getElementById('editRoomModal'));
        editModal.show();

        // Fetch room data
        fetch(`/admin/hotels/{{ $hashids->encode($hotelId) }}/rooms/${roomId}/edit`)
            .then(response => response.json())
            .then(data => {
                if (data.room) {
                    const room = data.room;
                    // Populate form fields
                    document.getElementById('edit_room_title').value = room.title;
                    document.getElementById('edit_number_of_rooms').value = room.number_of_rooms;
                    document.getElementById('edit_room_price').value = room.price;
                    document.getElementById('edit_room_price_type').value = room.price_type || 'per_night';
                    document.getElementById('edit_room_price_per_person').value = room.price_per_person || '';
                    document.getElementById('edit_people_capacity').value = room.people;
                    document.getElementById('edit_number_of_beds').value = room.beds;
                    document.getElementById('edit_room_availability').value = room.availability ? '1' : '0';
                    document.getElementById('edit_room_description').value = room.description || '';
                    togglePriceFields('edit');

                    // Price intervals
                    const intervalContainer = document.getElementById('edit_interval_rows');
                    intervalContainer.innerHTML = '';
                    editIntervalIndex = 0;
                    const formatDateForInput = (d) => {
                        if (!d) return '';
                        const s = String(d);
                        return s.substring(0, 10);
                    };
                    (data.price_intervals || []).forEach((pi) => {
                        addIntervalRow('edit');
                        const rows = intervalContainer.querySelectorAll('.interval-row');
                        const r = rows[rows.length - 1];
                        if (r) {
                            const startIn = r.querySelector('input[data-type="start"], input[name*="[start_date]"]');
                            const endIn = r.querySelector('input[data-type="end"], input[name*="[end_date]"]');
                            if (startIn) startIn.value = formatDateForInput(pi.start_date);
                            if (endIn) endIn.value = formatDateForInput(pi.end_date);
                            r.querySelector('input[name*="[label]"]').value = pi.label || '';
                            r.querySelector('input[name*="[price]"]').value = pi.price ?? '';
                        }
                    });

                    // Set Quill editor content
                    editRoomDescriptionQuill.root.innerHTML = room.description || '';

                    // Set form action
                    document.getElementById('editRoomForm').action = `/admin/hotels/{{ $hashids->encode($hotelId) }}/rooms/${roomId}`;

                    // Handle cover photo preview
                    const coverPreview = document.getElementById('edit_room_cover_photo_preview');
                    if (data.room.cover_photo) {
                        coverPreview.innerHTML = `<img src="/storage/${data.room.cover_photo}" alt="Cover Preview" style="width:100px; height:75px; object-fit:cover; border-radius:4px;">`;
                    } else {
                        coverPreview.innerHTML = `<img src="https://media.istockphoto.com/id/1147544807/vector/thumbnail-image-vector-graphic.jpg?s=612x612&w=0&k=20&c=rnCKVbdxqkjlcs3xH87-9gocETqpspHFXu5dIGB4wuM=" alt="Cover Preview" style="width:100px; height:75px; object-fit:cover; border-radius:4px;">`;
                    }

                    // Handle other images preview
                    const otherImagesPreview = document.getElementById('edit_room_other_images_preview');
                    otherImagesPreview.innerHTML = '';
                    if (data.photos && data.photos.length > 0) {
                        data.photos.forEach(photo => {
                            const img = document.createElement('img');
                            img.src = `/storage/${photo.photo}`;
                            img.style.width = '100px';
                            img.style.height = '75px';
                            img.style.objectFit = 'cover';
                            img.style.borderRadius = '4px';
                            img.classList.add('me-2');
                            otherImagesPreview.appendChild(img);
                        });
                    }
                } else {
                    alert('Room not found');
                    editModal.hide();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading room data');
                editModal.hide();
            });
    }

    // Delete room function
    function deleteRoom(roomId) {
        // Set the form action URL
        const deleteForm = document.getElementById('deleteForm');
        deleteForm.action = `/admin/hotels/{{ $hashids->encode($hotelId) }}/rooms/${roomId}`;
        
        // Show the delete modal
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    }

    // Delete hotel function
    function deleteHotel(hotelId) {
        // Set the form action URL
        const deleteHotelForm = document.getElementById('deleteHotelForm');
        deleteHotelForm.action = `/admin/hotels/${hotelId}`;
        
        // Show the delete modal
        const deleteHotelModal = new bootstrap.Modal(document.getElementById('deleteHotelModal'));
        deleteHotelModal.show();
    }

    // Nearby Deals functionality
    let availableDeals = [];

    // Reset modal when it opens
    document.getElementById('nearbyDealsModal').addEventListener('show.bs.modal', function () {
        document.getElementById('deal_type').value = '';
        document.getElementById('nearby_deal_id').innerHTML = '<option value="">Select deal type first...</option>';
        document.getElementById('deal-preview-container').style.display = 'none';
        document.getElementById('add-deal-btn').disabled = true;
        availableDeals = [];
    });

    // Handle deal type change
    document.getElementById('deal_type').addEventListener('change', function() {
        const dealType = this.value;
        const dealSelect = document.getElementById('nearby_deal_id');
        const previewContainer = document.getElementById('deal-preview-container');
        const addBtn = document.getElementById('add-deal-btn');
        
        // Reset
        dealSelect.innerHTML = '<option value="">Loading...</option>';
        previewContainer.style.display = 'none';
        addBtn.disabled = true;
        
        if (!dealType) {
            dealSelect.innerHTML = '<option value="">Select deal type first...</option>';
            return;
        }
        
        // Load deals for selected type
        fetch(`/admin/hotels/{{ $hotelId }}/get-deals-by-type/${dealType}`)
            .then(response => response.json())
            .then(data => {
                dealSelect.innerHTML = '';
                availableDeals = data.deals || [];
                
                if (availableDeals.length === 0) {
                    dealSelect.innerHTML = '<option value="">No deals available</option>';
                    return;
                }
                
                dealSelect.innerHTML = '<option value="">Select a deal...</option>';
                availableDeals.forEach(deal => {
                    const option = document.createElement('option');
                    option.value = deal.id;
                    option.textContent = `${deal.title} - $${deal.base_price}`;
                    dealSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                dealSelect.innerHTML = '<option value="">Error loading deals</option>';
            });
    });

    // Handle deal selection change
    document.getElementById('nearby_deal_id').addEventListener('change', function() {
        const selectedDealId = this.value;
        const previewContainer = document.getElementById('deal-preview-container');
        const preview = document.getElementById('deal-preview');
        const addBtn = document.getElementById('add-deal-btn');
        
        if (!selectedDealId) {
            previewContainer.style.display = 'none';
            addBtn.disabled = true;
            return;
        }
        
        // Find selected deal
        const selectedDeal = availableDeals.find(deal => deal.id == selectedDealId);
        
        if (selectedDeal) {
            // Show preview
            preview.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="me-3">
                        ${selectedDeal.cover_photo ? 
                            `<img src="/storage/${selectedDeal.cover_photo}" alt="${selectedDeal.title}" style="width: 60px; height: 45px; object-fit: cover; border-radius: 6px;">` :
                            `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 45px;"><i class="ti ti-photo text-muted"></i></div>`
                        }
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${selectedDeal.title}</h6>
                        <p class="mb-1 text-muted">${selectedDeal.location || 'Location not specified'}</p>
                        <p class="mb-0">
                            <strong class="text-success">$${selectedDeal.base_price}</strong>
                        </p>
                    </div>
                </div>
            `;
            previewContainer.style.display = 'block';
            addBtn.disabled = false;
        } else {
            previewContainer.style.display = 'none';
            addBtn.disabled = true;
        }
    });

    // Show remove nearby deal modal
    function showRemoveNearbyDealModal(nearId, dealTitle) {
        // Set the deal title in the modal
        document.getElementById('deal-title-to-remove').textContent = dealTitle;
        
        // Set the form action URL
        const removeForm = document.getElementById('removeNearbyDealForm');
        removeForm.action = `/admin/hotels/{{ $hotelId }}/remove-nearby/${nearId}`;
        
        // Show the modal
        const removeModal = new bootstrap.Modal(document.getElementById('removeNearbyDealModal'));
        removeModal.show();
    }

    // Remove nearby deal function (called when form is submitted)
    function removeNearbyDeal(nearId) {
        fetch(`/admin/hotels/{{ $hotelId }}/remove-nearby/${nearId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error removing nearby deal: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing nearby deal');
        });
    }

    // Handle form submission for removing nearby deals
    document.getElementById('removeNearbyDealForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        
        fetch(form.action, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide the modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('removeNearbyDealModal'));
                modal.hide();
                
                // Reload the page to show updated data
                location.reload();
            } else {
                alert('Error removing nearby deal: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error removing nearby deal');
        });
    });

</script>
@endpush