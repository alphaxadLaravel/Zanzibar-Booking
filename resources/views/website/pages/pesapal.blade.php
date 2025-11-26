@extends('website.layouts.app')

@section('title', 'Payment - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Complete your payment securely with Pesapal">
@endsection

@section('pages')
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><span>Payment</span></li>
        </ul>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Payment Header -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card mr-2"></i>
                        Complete Your Payment
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Booking Reference</h6>
                            <h5 class="text-primary font-weight-bold">{{ $booking->booking_code }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Total Amount</h6>
                            <h5 class="text-success font-weight-bold">{{ config('pesapal.currency', 'USD') }} {{ number_format($payment->amount, 2) }}</h5>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Booking Summary
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $deal = $booking->deal;
                        $bookingItems = $booking->getBookingItems();
                        $firstItem = !empty($bookingItems) ? $bookingItems[0] : null;
                    @endphp
                    
                    @if($deal)
                    <div class="row">
                        <div class="col-md-3">
                            <img src="{{ $deal->cover_photo ? asset('storage/' . $deal->cover_photo) : asset('images/default-placeholder.jpg') }}" 
                                 alt="{{ $deal->title }}" class="img-fluid rounded" style="height: 120px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <h6 class="font-weight-bold">{{ $deal->title }}</h6>
                            <p class="text-muted mb-2">{{ $deal->location ?? 'Zanzibar' }}</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <small class="text-muted">
                                        <i class="fas fa-shopping-bag mr-1"></i>
                                        {{ count($bookingItems) }} {{ count($bookingItems) == 1 ? 'Item' : 'Items' }} booked
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="font-weight-bold">Booking #{{ $booking->booking_code }}</h6>
                            <p class="text-muted mb-2">
                                <i class="fas fa-shopping-bag mr-1"></i>
                                {{ count($bookingItems) }} {{ count($bookingItems) == 1 ? 'Item' : 'Items' }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Payment Instructions -->
            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Payment Instructions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Please follow these steps:</h6>
                        <ol class="mb-0">
                            <li>Complete your payment using the secure payment form below</li>
                            <li>You will be redirected to Pesapal's secure payment gateway</li>
                            <li>After successful payment, you'll receive a confirmation</li>
                            <li>Your booking will be confirmed automatically</li>
                        </ol>
                    </div>
                </div>
            </div>

            <!-- Pesapal Payment Form -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Secure Payment Gateway
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading payment form...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading secure payment form...</p>
                    </div>
                    
                    <!-- Pesapal iframe will be loaded here -->
                    <div id="pesapal-payment-form">
                        @if(!empty($iframe))
                            {!! $iframe !!}
                        @else
                            <div class="alert alert-danger">
                                <h6>Payment Form Error</h6>
                                <p>Unable to load payment form. Please try again or contact support.</p>
                                <a href="{{ route('booking.view', $booking->id) }}" class="btn btn-primary">Go Back to Booking</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Payment Security Info -->
            <div class="card mt-4">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <i class="fas fa-lock fa-2x text-success mb-2"></i>
                            <h6>Secure Payment</h6>
                            <small class="text-muted">256-bit SSL encryption</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-shield-alt fa-2x text-primary mb-2"></i>
                            <h6>Protected</h6>
                            <small class="text-muted">Your data is safe with us</small>
                        </div>
                        <div class="col-md-4">
                            <i class="fas fa-credit-card fa-2x text-info mb-2"></i>
                            <h6>Multiple Options</h6>
                            <small class="text-muted">Various payment methods</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Hide loading spinner when iframe loads
    const iframe = document.querySelector('#pesapal-payment-form iframe');
    if (iframe) {
        iframe.addEventListener('load', function() {
            const spinner = document.querySelector('.spinner-border');
            const loadingText = document.querySelector('.text-muted');
            if (spinner) spinner.style.display = 'none';
            if (loadingText) loadingText.style.display = 'none';
        });
    }

    // Auto-hide loading after 3 seconds as fallback
    setTimeout(function() {
        const spinner = document.querySelector('.spinner-border');
        const loadingText = document.querySelector('.text-muted');
        if (spinner) spinner.style.display = 'none';
        if (loadingText) loadingText.style.display = 'none';
    }, 3000);
});
</script>
@endsection
