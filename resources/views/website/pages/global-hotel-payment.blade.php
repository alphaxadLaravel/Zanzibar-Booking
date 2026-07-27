@extends('website.layouts.app')

@section('title', 'Pay for hotel booking')
@section('pages')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="h4 mb-3">Complete payment</h1>
            <p class="text-muted">{{ $booking->hotel_name }} · Ref {{ $booking->booking_reference }}</p>
            <div class="mb-3">
                <strong>{{ $booking->currency }} {{ \App\Support\FlightOfferMapper::formatPrice((float) $booking->total_price) }}</strong>
            </div>
            <div class="border rounded p-2">
                {!! $iframe !!}
            </div>
        </div>
    </div>
</div>
@endsection
