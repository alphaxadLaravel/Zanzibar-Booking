@extends('admin.layouts.app')

@section('title', 'Hotel Rooms')

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
                        <li class="breadcrumb-item active">Hotel Rooms</li>
                    </ol>
                </div>
                <h4 class="page-title">Hotel Rooms Management</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Grand Hotel - Rooms</h5>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('admin.hotels.rooms.create', $hotel_id) }}" class="btn btn-primary">
                                <i class="ti ti-plus"></i> Add New Room
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Rooms Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Room Number</th>
                                    <th>Room Type</th>
                                    <th>Capacity</th>
                                    <th>Price/Night</th>
                                    <th>Availability</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample Data - Replace with actual data from database -->
                                <tr>
                                    <td>101</td>
                                    <td>Deluxe Suite</td>
                                    <td>2 Adults, 1 Child</td>
                                    <td>$200</td>
                                    <td>
                                        <span class="badge bg-success">Available</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.hotels.rooms.view', [$hotel_id, 1]) }}" class="btn btn-sm btn-outline-info">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.hotels.rooms.edit', [$hotel_id, 1]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="updateAvailability(1)">
                                                <i class="ti ti-calendar"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRoom(1)">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>102</td>
                                    <td>Standard Room</td>
                                    <td>2 Adults</td>
                                    <td>$150</td>
                                    <td>
                                        <span class="badge bg-warning">Occupied</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.hotels.rooms.view', [$hotel_id, 2]) }}" class="btn btn-sm btn-outline-info">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.hotels.rooms.edit', [$hotel_id, 2]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="updateAvailability(2)">
                                                <i class="ti ti-calendar"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRoom(2)">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>103</td>
                                    <td>Family Room</td>
                                    <td>4 Adults, 2 Children</td>
                                    <td>$300</td>
                                    <td>
                                        <span class="badge bg-success">Available</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.hotels.rooms.view', [$hotel_id, 3]) }}" class="btn btn-sm btn-outline-info">
                                                <i class="ti ti-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.hotels.rooms.edit', [$hotel_id, 3]) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-warning" onclick="updateAvailability(3)">
                                                <i class="ti ti-calendar"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRoom(3)">
                                                <i class="ti ti-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Availability Update Modal -->
<div class="modal fade" id="availabilityModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Room Availability</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="availabilityForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="availability_status" class="form-label">Availability Status</label>
                        <select class="form-select" id="availability_status" name="availability_status" required>
                            <option value="available">Available</option>
                            <option value="occupied">Occupied</option>
                            <option value="maintenance">Under Maintenance</option>
                            <option value="out_of_order">Out of Order</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Add any notes about the room availability..."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="availabilityForm" class="btn btn-primary">Update Availability</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this room? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function updateAvailability(roomId) {
    document.getElementById('availabilityForm').action = '{{ route("admin.hotels.rooms.availability", [":hotel_id", ":room_id"]) }}'.replace(':hotel_id', {{ $hotel_id }}).replace(':room_id', roomId);
    new bootstrap.Modal(document.getElementById('availabilityModal')).show();
}

function deleteRoom(roomId) {
    document.getElementById('deleteForm').action = '{{ route("admin.hotels.rooms.delete", [":hotel_id", ":room_id"]) }}'.replace(':hotel_id', {{ $hotel_id }}).replace(':room_id', roomId);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
