@extends('admin.layouts.app')

@section('title', 'Edit Hotel Room')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.hotels') }}">Hotels</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.hotels.rooms', $hotel_id) }}">Rooms</a></li>
                        <li class="breadcrumb-item active">Edit Room</li>
                    </ol>
                </div>
                <h4 class="page-title">Edit Hotel Room</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Room Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.hotels.rooms.update', [$hotel_id, $room_id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_number" class="form-label">Room Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('room_number') is-invalid @enderror" 
                                           id="room_number" name="room_number" value="{{ old('room_number', '101') }}" required>
                                    @error('room_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="room_type" class="form-label">Room Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('room_type') is-invalid @enderror" id="room_type" name="room_type" required>
                                        <option value="">Select Room Type</option>
                                        <option value="standard" {{ old('room_type', 'deluxe') == 'standard' ? 'selected' : '' }}>Standard Room</option>
                                        <option value="deluxe" {{ old('room_type', 'deluxe') == 'deluxe' ? 'selected' : '' }}>Deluxe Room</option>
                                        <option value="suite" {{ old('room_type', 'deluxe') == 'suite' ? 'selected' : '' }}>Suite</option>
                                        <option value="family" {{ old('room_type', 'deluxe') == 'family' ? 'selected' : '' }}>Family Room</option>
                                        <option value="presidential" {{ old('room_type', 'deluxe') == 'presidential' ? 'selected' : '' }}>Presidential Suite</option>
                                    </select>
                                    @error('room_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="max_adults" class="form-label">Max Adults <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_adults') is-invalid @enderror" 
                                           id="max_adults" name="max_adults" value="{{ old('max_adults', '2') }}" min="1" max="10" required>
                                    @error('max_adults')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="max_children" class="form-label">Max Children</label>
                                    <input type="number" class="form-control @error('max_children') is-invalid @enderror" 
                                           id="max_children" name="max_children" value="{{ old('max_children', '1') }}" min="0" max="10">
                                    @error('max_children')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="room_size" class="form-label">Room Size (sq ft)</label>
                                    <input type="number" class="form-control @error('room_size') is-invalid @enderror" 
                                           id="room_size" name="room_size" value="{{ old('room_size', '450') }}" min="1">
                                    @error('room_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="floor" class="form-label">Floor <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('floor') is-invalid @enderror" 
                                           id="floor" name="floor" value="{{ old('floor', '5') }}" min="1" max="50" required>
                                    @error('floor')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="view_type" class="form-label">View Type</label>
                                    <select class="form-select @error('view_type') is-invalid @enderror" id="view_type" name="view_type">
                                        <option value="">Select View Type</option>
                                        <option value="city" {{ old('view_type', 'city') == 'city' ? 'selected' : '' }}>City View</option>
                                        <option value="ocean" {{ old('view_type', 'city') == 'ocean' ? 'selected' : '' }}>Ocean View</option>
                                        <option value="garden" {{ old('view_type', 'city') == 'garden' ? 'selected' : '' }}>Garden View</option>
                                        <option value="mountain" {{ old('view_type', 'city') == 'mountain' ? 'selected' : '' }}>Mountain View</option>
                                        <option value="pool" {{ old('view_type', 'city') == 'pool' ? 'selected' : '' }}>Pool View</option>
                                    </select>
                                    @error('view_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bed_type" class="form-label">Bed Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('bed_type') is-invalid @enderror" id="bed_type" name="bed_type" required>
                                        <option value="">Select Bed Type</option>
                                        <option value="single" {{ old('bed_type', 'king') == 'single' ? 'selected' : '' }}>Single Bed</option>
                                        <option value="double" {{ old('bed_type', 'king') == 'double' ? 'selected' : '' }}>Double Bed</option>
                                        <option value="queen" {{ old('bed_type', 'king') == 'queen' ? 'selected' : '' }}>Queen Bed</option>
                                        <option value="king" {{ old('bed_type', 'king') == 'king' ? 'selected' : '' }}>King Bed</option>
                                        <option value="twin" {{ old('bed_type', 'king') == 'twin' ? 'selected' : '' }}>Twin Beds</option>
                                    </select>
                                    @error('bed_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="base_price" class="form-label">Base Price per Night <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('base_price') is-invalid @enderror" 
                                               id="base_price" name="base_price" value="{{ old('base_price', '200') }}" step="0.01" required>
                                        @error('base_price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Room Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description', 'Spacious deluxe suite with modern amenities, city view, and comfortable king-size bed. Perfect for business travelers and couples seeking luxury accommodation.') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="room_images" class="form-label">Room Images</label>
                            <input type="file" class="form-control @error('room_images') is-invalid @enderror" 
                                   id="room_images" name="room_images[]" multiple accept="image/*">
                            @error('room_images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">You can select multiple images. Leave empty to keep existing images.</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active (Room will be available for booking)
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy"></i> Update Room
                            </button>
                            <a href="{{ route('admin.hotels.rooms', $hotel_id) }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
