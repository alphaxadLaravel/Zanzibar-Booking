@extends('admin.layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.payments') }}">Payments</a></li>
                        <li class="breadcrumb-item active">Payment Details</li>
                    </ol>
                </div>
                <h4 class="page-title">Payment Details</h4>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Payment ID</h6>
                            <p class="text-muted">#PAY001</p>
                            
                            <h6>Customer Name</h6>
                            <p class="text-muted">John Doe</p>
                            
                            <h6>Email</h6>
                            <p class="text-muted">john.doe@email.com</p>
                            
                            <h6>Phone</h6>
                            <p class="text-muted">+1-555-0123</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Booking Reference</h6>
                            <p class="text-muted">#BK001</p>
                            
                            <h6>Service</h6>
                            <p class="text-muted">Grand Hotel - 3 nights</p>
                            
                            <h6>Payment Method</h6>
                            <p class="text-muted">Credit Card (**** 1234)</p>
                            
                            <h6>Transaction ID</h6>
                            <p class="text-muted">TXN_123456789</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Amount</h6>
                            <p class="text-muted h5 text-primary">$450.00</p>
                            
                            <h6>Currency</h6>
                            <p class="text-muted">USD</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Payment Date</h6>
                            <p class="text-muted">January 15, 2024 at 2:30 PM</p>
                            
                            <h6>Status</h6>
                            <p class="text-muted">
                                <span class="badge bg-success">Completed</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Summary</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Service Fee:</span>
                            <span>$400.00</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Taxes:</span>
                            <span>$40.00</span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Processing Fee:</span>
                            <span>$10.00</span>
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total:</span>
                            <span class="text-primary">$450.00</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-success">
                            <i class="ti ti-download"></i> Download Receipt
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="ti ti-mail"></i> Send Receipt
                        </button>
                        <a href="{{ route('admin.payments') }}" class="btn btn-outline-secondary">
                            <i class="ti ti-arrow-left"></i> Back to Payments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
