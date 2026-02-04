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
    <div class="row">
        <!-- Main Payment Area - Iframe First -->
        <div class="col-lg-8">
            <!-- Pesapal Payment Form - Prominent -->
            <div class="card mb-4">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading payment form...</span>
                        </div>
                        <p class="mt-2 text-muted">Loading secure payment form...</p>
                    </div>
                    
                    <!-- Pesapal iframe will be loaded here -->
                    <div id="pesapal-payment-form" style="min-height: 800px;">
                        @if(!empty($iframe))
                            <style>
                                #pesapal-payment-form iframe {
                                    width: 100%;
                                    min-height: 800px;
                                    border: none;
                                }
                            </style>
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
            <div class="card">
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

        <!-- Sidebar - Booking Summary -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-info-circle mr-2"></i>
                        Booking Summary
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Booking Reference</h6>
                        <h5 class="text-primary font-weight-bold">{{ $booking->booking_code }}</h5>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="text-muted mb-1">Total Amount</h6>
                        <h4 class="text-success font-weight-bold">{{ priceForUser($payment->amount, 2) }}</h4>
                    </div>

                    <hr>

                    @php
                        $deal = $booking->deal;
                        $bookingItems = $booking->getBookingItems();
                    @endphp
                    
                    @if($deal)
                    <div class="mb-3">
                        <img src="{{ $deal->cover_photo ? asset('storage/' . $deal->cover_photo) : asset('images/default-placeholder.jpg') }}" 
                             alt="{{ $deal->title }}" class="img-fluid rounded mb-2" style="width: 100%; height: 150px; object-fit: cover;">
                        <h6 class="font-weight-bold mb-1">{{ $deal->title }}</h6>
                        <p class="text-muted mb-2 small">
                            <i class="fas fa-map-marker-alt"></i> {{ $deal->location ?? 'Zanzibar' }}
                        </p>
                    </div>
                    @endif

                    <div class="booking-items-summary">
                        <h6 class="text-muted mb-2">Items Booked</h6>
                        @foreach($bookingItems as $item)
                        @php
                            $itemDeal = isset($item['deal_id']) ? \App\Models\Deal::find($item['deal_id']) : null;
                            $itemType = $item['type'] ?? 'unknown';
                        @endphp
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between">
                                <span class="font-weight-bold">{{ $itemDeal ? $itemDeal->title : 'Item' }}</span>
                                <span class="text-success">{{ priceForUser($item['total_price'] ?? 0, 2) }}</span>
                            </div>
                            @if(isset($item['check_in']))
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> 
                                @if($itemType === 'car')
                                    Pickup: {{ \Carbon\Carbon::parse($item['check_in'])->format('M d, Y') }}
                                    @if(isset($item['check_out']))
                                        <br>Return: {{ \Carbon\Carbon::parse($item['check_out'])->format('M d, Y') }}
                                    @endif
                                @else
                                    {{ \Carbon\Carbon::parse($item['check_in'])->format('M d, Y') }}
                                    @if(isset($item['check_out']))
                                        - {{ \Carbon\Carbon::parse($item['check_out'])->format('M d, Y') }}
                                    @endif
                                @endif
                            </small>
                            @endif
                        </div>
                        @endforeach
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
