@extends('admin.layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">My Profile</li>
                    </ol>
                </div>
                <h4 class="page-title">My Profile</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <img src="https://via.placeholder.com/150" class="rounded-circle" alt="Profile Picture" width="120" height="120">
                    </div>
                    <h5 class="card-title">John Doe</h5>
                    <p class="text-muted">Administrator</p>
                    <div class="mb-3">
                        <span class="badge bg-success">Active</span>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.profile.edit') }}" class="btn btn-primary">
                            <i class="ti ti-edit"></i> Edit Profile
                        </a>
                        <button class="btn btn-outline-secondary">
                            <i class="ti ti-camera"></i> Change Photo
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Full Name</h6>
                            <p class="text-muted">John Doe</p>
                            
                            <h6>Email Address</h6>
                            <p class="text-muted">john.doe@email.com</p>
                            
                            <h6>Phone Number</h6>
                            <p class="text-muted">+1-555-0123</p>
                            
                            <h6>Role</h6>
                            <p class="text-muted">
                                <span class="badge bg-primary">Administrator</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>Date of Birth</h6>
                            <p class="text-muted">January 15, 1990</p>
                            
                            <h6>Member Since</h6>
                            <p class="text-muted">January 1, 2024</p>
                            
                            <h6>Last Login</h6>
                            <p class="text-muted">Today at 2:30 PM</p>
                            
                            <h6>Status</h6>
                            <p class="text-muted">
                                <span class="badge bg-success">Active</span>
                            </p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-12">
                            <h6>Address</h6>
                            <p class="text-muted">123 Main Street<br>New York, NY 10001<br>United States</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Bio</h6>
                            <p class="text-muted">Experienced administrator with 5+ years in travel industry management. Passionate about providing excellent customer service and managing travel operations efficiently.</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Skills</h6>
                            <div class="mb-2">
                                <span class="badge bg-light text-dark me-1">Management</span>
                                <span class="badge bg-light text-dark me-1">Customer Service</span>
                                <span class="badge bg-light text-dark me-1">Travel Planning</span>
                                <span class="badge bg-light text-dark me-1">Team Leadership</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Account Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary">156</h4>
                            <p class="text-muted mb-0">Total Bookings</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">$45,230</h4>
                            <p class="text-muted mb-0">Revenue Generated</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">89</h4>
                            <p class="text-muted mb-0">Happy Customers</p>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">4.8</h4>
                            <p class="text-muted mb-0">Average Rating</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
