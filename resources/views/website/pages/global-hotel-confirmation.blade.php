@extends('website.layouts.app')

@section('title', 'Hotel booking confirmed')
@section('pages')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            @if($booking->status === 'confirmed')
                <div class="display-6 text-success mb-3">✓</div>
                <h1 class="h3">Hotel booking confirmed</h1>
            @elseif($booking->status === 'failed')
                <h1 class="h3 text-danger">Booking could not be confirmed</h1>
                <p class="text-muted">Payment was received but the supplier booking failed. Our team will contact you shortly.</p>
            @else
                <h1 class="h3">Payment received</h1>
                <p class="text-muted">We are confirming your hotel with the supplier. You will receive an update shortly.</p>
            @endif

            <div class="border rounded p-4 text-start mt-4 bg-light">
                <div class="mb-2"><strong>Reference:</strong> {{ $booking->booking_reference }}</div>
                @if($booking->supplier_booking_ref)
                    <div class="mb-2"><strong>Supplier ref:</strong> {{ $booking->supplier_booking_ref }}</div>
                @endif
                <div class="mb-2"><strong>Hotel:</strong> {{ $booking->hotel_name }}</div>
                <div class="mb-2"><strong>Room:</strong> {{ $booking->room_name }} @if($booking->board_name) · {{ $booking->board_name }} @endif</div>
                <div class="mb-2">
                    <strong>Dates:</strong>
                    {{ $booking->check_in->format('M j, Y') }} – {{ $booking->check_out->format('M j, Y') }}
                </div>
                <div><strong>Total paid:</strong> {{ $booking->currency }} {{ \App\Support\FlightOfferMapper::formatPrice((float) $booking->total_price) }}</div>
            </div>

            <div class="mt-4">
                <a href="{{ route('hotels.global.index') }}" class="btn btn-primary">Search more hotels</a>
                <a href="{{ route('hotels') }}" class="btn btn-outline-secondary">Local hotels</a>
            </div>
        </div>
    </div>
</div>
@endsection
