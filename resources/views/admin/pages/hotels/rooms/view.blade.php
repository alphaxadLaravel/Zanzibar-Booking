@extends('admin.layouts.app')

@section('title', 'Room Details')

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
                        <li class="breadcrumb-item active">Room Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Room Details</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Room Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Room Number</h6>
                            <p class="text-muted">101</p>
                            
                            <h6>Room Type</h6>
                            <p class="text-muted">Deluxe Suite</p>
                            
                            <h6>Capacity</h6>
                            <p class="text-muted">2 Adults, 1 Child</p>
                            
                            <h6>Room Size</h6>
                            <p class="text-muted">450 sq ft</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Floor</h6>
                            <p class="text-muted">5th Floor</p>
                            
                            <h6>View Type</h6>
                            <p class="text-muted">City View</p>
                            
                            <h6>Bed Type</h6>
                            <p class="text-muted">King Bed</p>
                            
                            <h6>Status</h6>
                            <p class="text-muted">
                                <span class="badge bg-success">Active</span>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <h6>Description</h6>
                            <p class="text-muted">Spacious deluxe suite with modern amenities, city view, and comfortable king-size bed. Perfect for business travelers and couples seeking luxury accommodation.</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Pricing</h6>
                            <p class="text-muted">
                                <strong>Base Price:</strong> $200/night<br>
                                <strong>Weekend Price:</strong> $250/night<br>
                                <strong>Extra Bed:</strong> $50/night<br>
                                <strong>Cleaning Fee:</strong> $25
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Check-in/out Times</h6>
                            <p class="text-muted">
                                <strong>Check-in:</strong> 3:00 PM<br>
                                <strong>Check-out:</strong> 11:00 AM
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <h6>Amenities</h6>
                            <div class="mb-2">
                                <span class="badge bg-light text-dark me-1">Free WiFi</span>
                                <span class="badge bg-light text-dark me-1">TV</span>
                                <span class="badge bg-light text-dark me-1">Air Conditioning</span>
                                <span class="badge bg-light text-dark me-1">Minibar</span>
                                <span class="badge bg-light text-dark me-1">Safe</span>
                                <span class="badge bg-light text-dark me-1">Balcony</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Room Images</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Room Image 1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Room Image 2">
                        </div>
                        <div class="col-md-4 mb-3">
                            <img src="https://via.placeholder.com/300x200" class="img-fluid rounded" alt="Room Image 3">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Availability Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <div>
                            <span class="badge bg-success fs-6">Available</span>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.hotels.rooms.availability', [$hotel_id, $room_id]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="availability_status" class="form-label">Update Status</label>
                            <select class="form-select" id="availability_status" name="availability_status" required>
                                <option value="available" selected>Available</option>
                                <option value="occupied">Occupied</option>
                                <option value="maintenance">Under Maintenance</option>
                                <option value="out_of_order">Out of Order</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Add any notes about the room availability..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-check"></i> Update Availability
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.hotels.rooms.edit', [$hotel_id, $room_id]) }}" class="btn btn-outline-primary">
                            <i class="ti ti-edit"></i> Edit Room
                        </a>
                        <button class="btn btn-outline-info">
                            <i class="ti ti-calendar"></i> View Calendar
                        </button>
                        <button class="btn btn-outline-warning">
                            <i class="ti ti-chart-bar"></i> View Analytics
                        </button>
                        <a href="{{ route('admin.hotels.rooms', $hotel_id) }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left"></i> Back to Rooms
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
