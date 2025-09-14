@extends('admin.layouts.app')

@section('title', 'My Bookings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Bookings</li>
                    </ol>
                </div>
                <h4 class="page-title">My Bookings</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">My Personal Bookings</h5>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Bookings Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Service</th>
                                    <th>Type</th>
                                    <th>Check-in</th>
                                    <th>Check-out</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample Data - Replace with actual data from database -->
                                <tr>
                                    <td>#BK001</td>
                                    <td>Grand Hotel</td>
                                    <td>Hotel</td>
                                    <td>2024-02-15</td>
                                    <td>2024-02-18</td>
                                    <td>$450</td>
                                    <td>
                                        <span class="badge bg-success">Confirmed</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBooking(1)">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="modifyBooking(1)">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="cancelBooking(1)">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#BK002</td>
                                    <td>Toyota Camry</td>
                                    <td>Car</td>
                                    <td>2024-02-20</td>
                                    <td>2024-02-22</td>
                                    <td>$100</td>
                                    <td>
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBooking(2)">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="modifyBooking(2)">
                                                <i class="ti ti-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="cancelBooking(2)">
                                                <i class="ti ti-x"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#BK003</td>
                                    <td>City Walking Tour</td>
                                    <td>Tour</td>
                                    <td>2024-02-25</td>
                                    <td>2024-02-25</td>
                                    <td>$45</td>
                                    <td>
                                        <span class="badge bg-info">Completed</span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewBooking(3)">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="reviewBooking(3)">
                                                <i class="ti ti-star"></i>
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

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Booking Information</h6>
                        <p><strong>Booking ID:</strong> #BK001</p>
                        <p><strong>Service:</strong> Grand Hotel</p>
                        <p><strong>Type:</strong> Hotel</p>
                        <p><strong>Check-in:</strong> February 15, 2024</p>
                        <p><strong>Check-out:</strong> February 18, 2024</p>
                        <p><strong>Guests:</strong> 2 Adults, 1 Child</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Payment Information</h6>
                        <p><strong>Total Amount:</strong> $450.00</p>
                        <p><strong>Payment Method:</strong> Credit Card</p>
                        <p><strong>Payment Status:</strong> <span class="badge bg-success">Paid</span></p>
                        <p><strong>Booking Status:</strong> <span class="badge bg-success">Confirmed</span></p>
                        <p><strong>Special Requests:</strong> Late check-in requested</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Download Receipt</button>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cancel Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to cancel this booking?</p>
                <div class="mb-3">
                    <label for="cancel_reason" class="form-label">Reason for Cancellation</label>
                    <textarea class="form-control" id="cancel_reason" rows="3" 
                              placeholder="Please provide a reason for cancellation..."></textarea>
                </div>
                <div class="alert alert-warning">
                    <i class="ti ti-alert-triangle"></i>
                    <strong>Note:</strong> Cancellation fees may apply based on the service provider's policy.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Keep Booking</button>
                <button type="button" class="btn btn-danger">Cancel Booking</button>
            </div>
        </div>
    </div>
</div>

<script>
function viewBooking(id) {
    new bootstrap.Modal(document.getElementById('bookingModal')).show();
}

function modifyBooking(id) {
    // Redirect to booking modification page or show modification modal
    alert('Modify booking functionality would be implemented here');
}

function cancelBooking(id) {
    new bootstrap.Modal(document.getElementById('cancelModal')).show();
}

function reviewBooking(id) {
    // Redirect to review page or show review modal
    alert('Review booking functionality would be implemented here');
}
</script>
@endsection
