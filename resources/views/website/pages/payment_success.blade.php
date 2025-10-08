@extends('website.layouts.app')

@section('title', 'Payment Success - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Your payment has been processed successfully">
@endsection

@section('pages')
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><span>Payment Success</span></li>
        </ul>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            <div class="card text-center mb-4">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h2 class="text-success mb-3">Payment Submitted Successfully!</h2>
                    <p class="text-muted mb-4">
                        Your payment has been submitted and is being processed. 
                        You will receive a confirmation email once the payment is confirmed.
                    </p>
                </div>
            </div>

            <!-- Booking Details -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt mr-2"></i>
                        Booking Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Booking Reference</h6>
                            <h5 class="text-primary font-weight-bold">{{ $booking->booking_code }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Payment Reference</h6>
                            <h5 class="text-info font-weight-bold">{{ $payment->reference }}</h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Amount Paid</h6>
                            <h5 class="text-success font-weight-bold">{{ config('pesapal.currency', 'USD') }} {{ number_format($payment->amount, 2) }}</h5>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Payment Status</h6>
                            @if($payment->status === 'COMPLETED')
                                <span class="badge badge-success badge-lg">{{ $payment->status }}</span>
                            @elseif($payment->status === 'FAILED' || $payment->status === 'CANCELLED')
                                <span class="badge badge-danger badge-lg">{{ $payment->status }}</span>
                            @else
                                <span class="badge badge-warning badge-lg">{{ $payment->status }}</span>
                            @endif
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

            <!-- Customer Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-user mr-2"></i>
                        Customer Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Name</h6>
                            <p class="font-weight-bold">{{ $booking->fullname }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Email</h6>
                            <p class="font-weight-bold">{{ $booking->email }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Phone</h6>
                            <p class="font-weight-bold">{{ $booking->phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Country</h6>
                            <p class="font-weight-bold">{{ $booking->country }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        What Happens Next?
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="mb-2">
                                <i class="fas fa-clock fa-2x text-warning"></i>
                            </div>
                            <h6>Payment Processing</h6>
                            <small class="text-muted">Your payment is being verified</small>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="mb-2">
                                <i class="fas fa-envelope fa-2x text-primary"></i>
                            </div>
                            <h6>Confirmation Email</h6>
                            <small class="text-muted">You'll receive a confirmation email</small>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="mb-2">
                                <i class="fas fa-calendar-check fa-2x text-success"></i>
                            </div>
                            <h6>Booking Confirmed</h6>
                            <small class="text-muted">Your booking will be confirmed</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center">
                <a href="{{ route('booking.view', $booking->id) }}" class="btn btn-primary btn-lg mr-3">
                    <i class="fas fa-eye mr-2"></i>
                    View Booking Details
                </a>
                <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-home mr-2"></i>
                    Back to Home
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
