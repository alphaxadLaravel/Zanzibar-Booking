@extends('admin.layouts.app')

@section('title', 'Hotel Rooms')

@section('content')
<div class="container-fluid">
    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
        <div>
            <h4 class="page-title mb-0">Hotel Rooms Management</h4>
        </div>
        <div>
            <ol class="breadcrumb m-0 bg-transparent p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.hotels') }}">Hotels</a></li>
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
                        <img src="{{ asset('storage/' . $hotel->cover_photo) }}" alt="{{ $hotel->title }}"
                            class="img-fluid rounded" style="max-height: 100px;">
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
                        <a href="" class="btn btn-outline-info btn-sm">
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
                                    <th>Price/Night</th>
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
                                        <span class="fw-bold text-success">${{ number_format($room->price, 2) }}</span>
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

                                                                <!-- Price -->
                                                                <div class="col-md-6">
                                                                    <label for="room_price" class="form-label">Price per
                                                                        Night <span class="text-danger">*</span></label>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text">$</span>
                                                                        <input type="number" class="form-control"
                                                                            id="room_price" name="price" step="0.01"
                                                                            min="0" required placeholder="0.00">
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

                        <!-- Price -->
                        <div class="col-md-6">
                            <label for="edit_room_price" class="form-label">Price per Night <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="edit_room_price" name="price" step="0.01"
                                    min="0" required placeholder="0.00">
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

                        <!-- Price -->
                        <div class="col-md-6">
                            <label for="room_price" class="form-label">Price per Night <span
                                    class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control" id="room_price" name="price" step="0.01"
                                    min="0" required placeholder="0.00">
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
                    // Populate form fields
                    document.getElementById('edit_room_title').value = data.room.title;
                    document.getElementById('edit_number_of_rooms').value = data.room.number_of_rooms;
                    document.getElementById('edit_room_price').value = data.room.price;
                    document.getElementById('edit_people_capacity').value = data.room.people;
                    document.getElementById('edit_number_of_beds').value = data.room.beds;
                    document.getElementById('edit_room_availability').value = data.room.availability ? '1' : '0';
                    document.getElementById('edit_room_description').value = data.room.description || '';

                    // Set Quill editor content
                    editRoomDescriptionQuill.root.innerHTML = data.room.description || '';

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

</script>
@endpush