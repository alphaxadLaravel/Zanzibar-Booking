@extends('website.layouts.app')

@section('title', 'Booking Details - Zanzibar Bookings')
@section('meta')
<meta name="description" content="View your booking details and status">
@endsection

@section('pages')
<div class="breadcrumb">
    <div class="container">
        <ul>
            <li><a href="{{ route('index') }}">Home</a></li>
            <li><span>Booking Details</span></li>
        </ul>
    </div>
</div>

<div class="container py-5">
    <div class="row">
        <!-- Booking Status Card -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-receipt mr-2"></i>
                        Booking Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($booking->status == 'confirmed')
                            <i class="fas fa-check-circle fa-3x text-success"></i>
                        @elseif($booking->status == 'pending')
                            <i class="fas fa-clock fa-3x text-warning"></i>
                        @elseif($booking->status == 'cancelled')
                            <i class="fas fa-times-circle fa-3x text-danger"></i>
                        @else
                            <i class="fas fa-info-circle fa-3x text-info"></i>
                        @endif
                    </div>
                    
                    <div class="text-center mb-3">
                        <h6 class="text-muted">Booking Reference</h6>
                        <h4 class="text-primary font-weight-bold">{{ $booking->booking_code }}</h4>
                    </div>
                    
                    <div class="text-center mb-3">
                        <h6 class="text-muted">Status</h6>
                        <span class="badge badge-{{ $booking->status == 'confirmed' ? 'success' : ($booking->status == 'pending' ? 'warning' : 'danger') }} badge-lg">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>
                    
                    <div class="text-center mb-3">
                        <h6 class="text-muted">Payment Status</h6>
                        @if($booking->payment_status)
                            <span class="badge badge-success badge-lg">Paid</span>
                        @else
                            <span class="badge badge-warning badge-lg">Pending</span>
                        @endif
                    </div>
                    
                    <div class="text-center">
                        <h6 class="text-muted">Total Amount</h6>
                        <h5 class="text-success font-weight-bold">USD {{ number_format($booking->total_price, 2) }}</h5>
                    </div>
                </div>
            </div>

            <!-- Payment History -->
            @if($booking->payments->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-credit-card mr-2"></i>
                        Payment History
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($booking->payments as $payment)
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between">
                            <span class="font-weight-bold">{{ $payment->reference }}</span>
                            <span class="badge badge-{{ $payment->status == 'COMPLETED' ? 'success' : ($payment->status == 'PENDING' ? 'warning' : 'danger') }}">
                                {{ $payment->status }}
                            </span>
                        </div>
                        <small class="text-muted">USD {{ number_format($payment->amount, 2) }} - {{ $payment->created_at->format('M d, Y H:i') }}</small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Booking Details -->
        <div class="col-lg-8">
            <!-- Deal Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Booking Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ $booking->deal->cover_photo ? asset('storage/' . $booking->deal->cover_photo) : asset('images/default-placeholder.jpg') }}" 
                                 alt="{{ $booking->deal->title }}" class="img-fluid rounded" style="height: 200px; object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <h5 class="font-weight-bold">{{ $booking->deal->title }}</h5>
                            <p class="text-muted mb-3">{{ $booking->deal->location }}</p>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Type</h6>
                                    <p class="font-weight-bold">{{ ucfirst($booking->deal->type) }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Guests</h6>
                                    <p class="font-weight-bold">
                                        {{ $booking->adult }} {{ $booking->adult == 1 ? 'Adult' : 'Adults' }}
                                        @if($booking->children > 0)
                                            , {{ $booking->children }} {{ $booking->children == 1 ? 'Child' : 'Children' }}
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($booking->deal->type == 'hotel' || $booking->deal->type == 'apartment')
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Check-in</h6>
                                    <p class="font-weight-bold">{{ $booking->check_in->format('M d, Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Check-out</h6>
                                    <p class="font-weight-bold">{{ $booking->check_out->format('M d, Y') }}</p>
                                </div>
                            </div>
                            @if($booking->room)
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Room Type</h6>
                                    <p class="font-weight-bold">{{ $booking->room->title }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Number of Rooms</h6>
                                    <p class="font-weight-bold">{{ $booking->number_rooms }}</p>
                                </div>
                            </div>
                            @endif
                            @elseif($booking->deal->type == 'tour')
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Pickup Location</h6>
                                    <p class="font-weight-bold">{{ $booking->pickup_location }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Pickup Time</h6>
                                    <p class="font-weight-bold">{{ $booking->pickup_time }}</p>
                                </div>
                            </div>
                            @elseif($booking->deal->type == 'car')
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Pickup Location</h6>
                                    <p class="font-weight-bold">{{ $booking->pickup_location }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Return Location</h6>
                                    <p class="font-weight-bold">{{ $booking->return_location }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Pickup Time</h6>
                                    <p class="font-weight-bold">{{ $booking->pickup_time }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Return Time</h6>
                                    <p class="font-weight-bold">{{ $booking->return_time }}</p>
                                </div>
                            </div>
                            @if($booking->need_driver)
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-muted mb-2">Driver Required</h6>
                                    <p class="font-weight-bold text-success">Yes</p>
                                </div>
                            </div>
                            @endif
                            @endif
                        </div>
                    </div>
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
                            <h6 class="text-muted mb-2">Full Name</h6>
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

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        @if($booking->status == 'pending' && !$booking->payment_status)
                        <a href="{{ route('payment.process', $booking->id) }}" class="btn btn-success btn-lg mr-3">
                            <i class="fas fa-credit-card mr-2"></i>
                            Complete Payment
                        </a>
                        @endif
                        
                        @if($booking->status == 'pending')
                        <form method="POST" action="{{ route('booking.cancel', $booking->id) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-lg" onclick="return confirm('Are you sure you want to cancel this booking?')">
                                <i class="fas fa-times mr-2"></i>
                                Cancel Booking
                            </button>
                        </form>
                        @endif
                        
                        <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-lg ml-3">
                            <i class="fas fa-home mr-2"></i>
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
