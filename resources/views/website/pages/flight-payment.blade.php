@extends('website.layouts.app')

@section('title', 'Flight Payment - Zanzibar Bookings')

@section('pages')
<style>
    .flights-hero { position: relative; width: 100%; }
    .flights-hero-media {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 1;
        max-height: 256px;
        overflow: hidden;
        background: #1a0a2e;
    }
    .flights-hero-bg {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: left center;
        display: block;
    }
    .flights-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 21, 56, 0.05) 0%, rgba(0, 21, 56, 0.25) 100%);
        pointer-events: none;
    }
    .flight-checkout { background: #fff; }
    .flight-checkout-block {
        position: relative;
        z-index: 2;
        margin-top: -26px;
        padding-bottom: 40px;
    }
    .flight-checkout__steps-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 10px 18px;
        box-shadow: none;
        margin-bottom: 16px;
    }
    .flight-checkout__steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 0;
    }
    .flight-checkout__step {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #6c757d;
        font-weight: 600;
        flex-shrink: 0;
        white-space: nowrap;
    }
    .flight-checkout__step span {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #e9ecef;
        font-size: 12px;
        flex-shrink: 0;
    }
    .flight-checkout__step.is-active, .flight-checkout__step.is-done { color: #003580; }
    .flight-checkout__step.is-active span, .flight-checkout__step.is-done span { background: #003580; color: #fff; }
    .flight-checkout__step-line {
        flex: 1 1 auto;
        height: 2px;
        background: #d7dee8;
        margin: 0 10px;
        min-width: 16px;
    }
    .flight-checkout__payment-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        box-shadow: none;
    }
    .flight-checkout__summary {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 20px;
        top: 20px;
        box-shadow: none;
    }
    .flight-checkout__summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
    .flight-checkout__summary-total { font-size: 18px; color: #003580; font-weight: 700; }
    .flight-checkout__trust { font-size: 12px; color: #6c757d; }
    .flight-checkout__trust i { width: 18px; color: #003580; }
    @media (max-width: 768px) {
        .flights-hero-media { max-height: 180px; }
        .flights-hero-bg { object-position: 20% center; }
        .flight-checkout-block { margin-top: -20px; }
    }
    @media (min-width: 769px) and (max-width: 1023px) {
        .flights-hero-media { max-height: 220px; }
    }
</style>

<div class="flights-hero">
    <div class="flights-hero-media">
        <img src="{{ asset('images/banner/flights-hero.png') }}"
             onerror="this.src='{{ asset('images/banner.jpg') }}'"
             alt="Complete your flight payment"
             class="flights-hero-bg">
        <div class="flights-hero-overlay"></div>
    </div>
</div>

<section class="flight-checkout">
    <div class="flight-checkout-block">
        <div class="container">
            <div class="flight-checkout__steps-card">
                <div class="flight-checkout__steps">
                    <div class="flight-checkout__step is-done"><span>1</span> Flight</div>
                    <div class="flight-checkout__step-line"></div>
                    <div class="flight-checkout__step is-done"><span>2</span> Passengers</div>
                    <div class="flight-checkout__step-line"></div>
                    <div class="flight-checkout__step is-active"><span>3</span> Payment</div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="flight-checkout__payment-card mb-4">
                        <div class="p-4">
                            <h5 class="font-weight-bold mb-2">Secure payment</h5>
                            <p class="text-muted mb-4">Complete your payment below. Your flight will be booked with the airline immediately after confirmation.</p>

                            <div id="pesapal-payment-form" style="min-height: 700px;">
                                @if(!empty($iframe))
                                    <style>#pesapal-payment-form iframe { width: 100%; min-height: 700px; border: none; }</style>
                                    {!! $iframe !!}
                                @else
                                    <div class="alert alert-danger">Unable to load payment form. Please try again.</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="flight-checkout__summary sticky-top">
                        <div class="d-flex align-items-center mb-3">
                            <img src="{{ $flight['airline_logo'] ?? '' }}" alt="" class="flight-checkout__airline-logo mr-2" style="max-height:36px;">
                            <div>
                                <div class="font-weight-bold">{{ $flight['airline'] ?? $booking->airline_name }}</div>
                                <div class="small text-muted">{{ $booking->origin_code }} → {{ $booking->destination_code }}</div>
                            </div>
                        </div>

                        <div class="flight-checkout__summary-row"><span>Reference</span><strong>{{ $booking->booking_reference }}</strong></div>
                        <div class="flight-checkout__summary-row"><span>Departure</span><strong>{{ optional($booking->departure_datetime)->format('d M Y H:i') }}</strong></div>
                        <div class="flight-checkout__summary-row"><span>Passengers</span><strong>{{ $booking->adults + $booking->children + $booking->infants }}</strong></div>
                        <hr>
                        <div class="flight-checkout__summary-total d-flex justify-content-between">
                            <span>Amount due</span>
                            <strong>{{ $booking->currency }} {{ number_format($booking->total_price, 2) }}</strong>
                        </div>

                        <div class="flight-checkout__trust mt-4">
                            <div><i class="fas fa-lock"></i> 256-bit encrypted payment</div>
                            <div><i class="fas fa-plane"></i> Ticket issued via Duffel after payment</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
