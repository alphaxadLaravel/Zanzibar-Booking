@extends('admin.layouts.app')

@section('title', 'View Booking')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.bookings') }}">Bookings</a></li>
                        <li class="breadcrumb-item active">View Booking</li>
                    </ol>
                </div>
                <h4 class="page-title">Booking Details</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Booking Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Booking ID</h6>
                            <p class="text-muted">#BK001</p>
                            
                            <h6>Customer Name</h6>
                            <p class="text-muted">John Doe</p>
                            
                            <h6>Email</h6>
                            <p class="text-muted">john.doe@email.com</p>
                            
                            <h6>Phone</h6>
                            <p class="text-muted">+1-555-0123</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Service</h6>
                            <p class="text-muted">Grand Hotel</p>
                            
                            <h6>Type</h6>
                            <p class="text-muted">Hotel</p>
                            
                            <h6>Check-in Date</h6>
                            <p class="text-muted">February 15, 2024</p>
                            
                            <h6>Check-out Date</h6>
                            <p class="text-muted">February 18, 2024</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Guests</h6>
                            <p class="text-muted">2 Adults, 1 Child</p>
                            
                            <h6>Special Requests</h6>
                            <p class="text-muted">Late check-in requested</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Total Amount</h6>
                            <p class="text-muted h5 text-primary">$450.00</p>
                            
                            <h6>Payment Status</h6>
                            <p class="text-muted">
                                <span class="badge bg-success">Paid</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Booking Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <div>
                            <span class="badge bg-success fs-6">Confirmed</span>
                        </div>
                    </div>
                    
                    <form action="{{ route('admin.bookings.status', $id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Update Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending">Pending</option>
                                <option value="confirmed" selected>Confirmed</option>
                                <option value="cancelled">Cancelled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" 
                                      placeholder="Add any notes about this booking..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-check"></i> Update Status
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
                        <a href="mailto:john.doe@email.com" class="btn btn-outline-primary">
                            <i class="ti ti-mail"></i> Send Email
                        </a>
                        <a href="tel:+15550123" class="btn btn-outline-success">
                            <i class="ti ti-phone"></i> Call Customer
                        </a>
                        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left"></i> Back to Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
