@extends('admin.layouts.app')

@section('title', 'User Roles')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Users</a></li>
                        <li class="breadcrumb-item active">Roles</li>
                    </ol>
                </div>
                <h4 class="page-title">User Roles Management</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Available Roles</h5>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="card-title mb-0">Admin</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Full access to all system features and settings.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="ti ti-check text-success"></i> Manage all services</li>
                                        <li><i class="ti ti-check text-success"></i> Manage users</li>
                                        <li><i class="ti ti-check text-success"></i> View all bookings</li>
                                        <li><i class="ti ti-check text-success"></i> System settings</li>
                                        <li><i class="ti ti-check text-success"></i> Reports & analytics</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="card-title mb-0">Staff</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Limited access to manage services and bookings.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="ti ti-check text-success"></i> Manage services</li>
                                        <li><i class="ti ti-check text-success"></i> View bookings</li>
                                        <li><i class="ti ti-check text-success"></i> Update booking status</li>
                                        <li><i class="ti ti-x text-danger"></i> Manage users</li>
                                        <li><i class="ti ti-x text-danger"></i> System settings</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="card-title mb-0">Customer</h6>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Basic access to book services and manage their bookings.</p>
                                    <ul class="list-unstyled">
                                        <li><i class="ti ti-check text-success"></i> View services</li>
                                        <li><i class="ti ti-check text-success"></i> Make bookings</li>
                                        <li><i class="ti ti-check text-success"></i> View own bookings</li>
                                        <li><i class="ti ti-check text-success"></i> Update profile</li>
                                        <li><i class="ti ti-x text-danger"></i> Manage services</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Role Permissions Summary</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Permission</th>
                                        <th class="text-center">Admin</th>
                                        <th class="text-center">Staff</th>
                                        <th class="text-center">Customer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Manage Hotels</td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Manage Apartments</td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Manage Cars</td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Manage Tours</td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Manage Blog</td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>View All Bookings</td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>Manage Users</td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                    </tr>
                                    <tr>
                                        <td>System Settings</td>
                                        <td class="text-center"><i class="ti ti-check text-success"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                        <td class="text-center"><i class="ti ti-x text-danger"></i></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
