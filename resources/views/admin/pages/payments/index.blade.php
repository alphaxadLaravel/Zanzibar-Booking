@extends('admin.layouts.app')

@section('title', 'All Payments')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Payments</li>
                    </ol>
                </div>
                <h4 class="page-title">All Payments</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payments Management</h5>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Payments Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Customer</th>
                                    <th>Booking</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample Data - Replace with actual data from database -->
                                <tr>
                                    <td>#PAY001</td>
                                    <td>John Doe</td>
                                    <td>#BK001</td>
                                    <td>$450.00</td>
                                    <td>Credit Card</td>
                                    <td>
                                        <span class="badge bg-success">Completed</span>
                                    </td>
                                    <td>2024-01-15</td>
                                    <td>
                                        <a href="{{ route('admin.payments.view', 1) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#PAY002</td>
                                    <td>Jane Smith</td>
                                    <td>#BK002</td>
                                    <td>$100.00</td>
                                    <td>PayPal</td>
                                    <td>
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td>2024-01-20</td>
                                    <td>
                                        <a href="{{ route('admin.payments.view', 2) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#PAY003</td>
                                    <td>Mike Johnson</td>
                                    <td>#BK003</td>
                                    <td>$200.00</td>
                                    <td>Bank Transfer</td>
                                    <td>
                                        <span class="badge bg-danger">Failed</span>
                                    </td>
                                    <td>2024-01-22</td>
                                    <td>
                                        <a href="{{ route('admin.payments.view', 3) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-eye"></i>
                                        </a>
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
@endsection
