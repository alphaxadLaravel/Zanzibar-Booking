@extends('website.layouts.app')

@section('title', 'Book Flight - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Complete your flight booking securely">
@endsection

@section('pages')
@php
    $counts = $passengerCounts ?? ['adults' => 1, 'children' => 0, 'infants' => 0];
    $totalPassengers = $counts['adults'] + $counts['children'] + $counts['infants'];
    $expiresLabel = !empty($flight['price_expires_at'])
        ? \App\Support\FlightOfferMapper::formatAvailabilityExpiry($flight['price_expires_at'])
        : null;
    $cabinLabel = str_replace('_', ' ', ucwords(strtolower($flight['cabin_class'] ?? 'Economy')));
@endphp

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
        color: #6c757d;
        font-size: 12px;
        flex-shrink: 0;
    }
    .flight-checkout__step.is-active { color: #003580; }
    .flight-checkout__step.is-active span, .flight-checkout__step.is-done span { background: #003580; color: #fff; }
    .flight-checkout__step.is-done { color: #003580; }
    .flight-checkout__step-line {
        flex: 1 1 auto;
        height: 2px;
        background: #d7dee8;
        margin: 0 10px;
        min-width: 16px;
    }
    .flight-checkout__brand-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 20px;
        box-shadow: none;
    }
    .flight-checkout__airline-logo { max-height: 48px; max-width: 120px; object-fit: contain; }
    .flight-checkout__airline-name { font-size: 20px; font-weight: 700; color: #1a2b42; }
    .flight-checkout__price { font-size: 26px; font-weight: 700; color: #003580; line-height: 1.1; }
    .flight-checkout__route {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: #f8fafc;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 16px;
    }
    .flight-checkout__route-time { font-size: 22px; font-weight: 700; color: #1a2b42; }
    .flight-checkout__route-place { font-size: 14px; color: #495057; }
    .flight-checkout__route-date { font-size: 12px; color: #6c757d; }
    .flight-checkout__route-mid { text-align: center; min-width: 110px; }
    .flight-checkout__route-line { height: 2px; background: #d7dee8; margin: 6px 0; position: relative; }
    .flight-checkout__route-line::after { content: ''; position: absolute; right: 0; top: -3px; border: 4px solid transparent; border-left-color: #d7dee8; }
    .flight-checkout__meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .flight-checkout__meta-grid div { background: #fff; border: 1px solid #e9ecef; border-radius: 6px; padding: 10px 12px; }
    .flight-checkout__meta-grid span { display: block; font-size: 11px; text-transform: uppercase; color: #6c757d; letter-spacing: .03em; }
    .flight-checkout__meta-grid strong { font-size: 14px; color: #1a2b42; }
    .flight-checkout__form-card {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        box-shadow: none;
        background: #fff;
    }
    .flight-checkout__submit { background: #003580; border-color: #003580; border-radius: 6px; font-weight: 600; }
    .flight-checkout__submit:hover { background: #002a66; border-color: #002a66; }
    .flight-checkout__summary {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 20px;
        top: 20px;
        box-shadow: none;
    }
    .flight-checkout__summary-brand { display: flex; align-items: center; gap: 12px; padding-bottom: 12px; border-bottom: 1px solid #e9ecef; }
    .flight-checkout__site-logo { max-height: 36px; }
    .flight-checkout__summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
    .flight-checkout__summary-total { font-size: 18px; color: #003580; }
    .flight-checkout__expire { font-size: 12px; color: #856404; background: #fff8e6; border-radius: 6px; padding: 8px 10px; }
    .flight-checkout__trust { font-size: 12px; color: #6c757d; }
    .flight-checkout__trust div { margin-bottom: 6px; }
    .flight-checkout__trust i { width: 18px; color: #003580; }
    .flight-checkout__success {
        display: flex;
        align-items: center;
        gap: 16px;
        background: #e8f5e9;
        border: 1px solid #c8e6c9;
        border-radius: 6px;
        padding: 20px;
        box-shadow: none;
    }
    .flight-checkout__success-icon { font-size: 40px; color: #2e7d32; }
    .passenger-card {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 18px;
        margin-bottom: 16px;
        background: #f8fafc;
        box-shadow: none;
    }
    .passenger-card__title { font-weight: 700; color: #1a2b42; margin-bottom: 14px; }
    @media (max-width: 768px) {
        .flights-hero-media { max-height: 180px; }
        .flights-hero-bg { object-position: 20% center; }
        .flight-checkout-block { margin-top: -20px; }
        .flight-checkout__route { flex-direction: column; text-align: center; }
        .flight-checkout__route-leg { text-align: center !important; width: 100%; }
        .flight-checkout__steps-card { padding: 8px 12px; }
        .flight-checkout__step { font-size: 12px; gap: 6px; }
        .flight-checkout__step span { width: 22px; height: 22px; font-size: 11px; }
        .flight-checkout__step-line { margin: 0 6px; }
    }
    @media (min-width: 769px) and (max-width: 1023px) {
        .flights-hero-media { max-height: 220px; }
    }
</style>

<div class="flights-hero">
    <div class="flights-hero-media">
        <img src="{{ asset('images/banner/flights-hero.png') }}"
             onerror="this.src='{{ asset('images/banner.jpg') }}'"
             alt="Book your flight to Zanzibar"
             class="flights-hero-bg">
        <div class="flights-hero-overlay"></div>
    </div>
</div>

<section class="flight-checkout">
    <div class="flight-checkout-block">
        <div class="container">
        @if(!empty($confirmed) && !empty($booking))
            <div class="flight-checkout__success mb-4">
                <div class="flight-checkout__success-icon"><i class="fas fa-check-circle"></i></div>
                <div>
                    <h4 class="mb-1">Your flight is booked</h4>
                    <p class="mb-1">Reference: <strong>{{ $booking->booking_reference }}</strong></p>
                    @if(!empty($booking->amadeus_response['booking_reference']))
                        <p class="mb-0 text-muted">Airline reference: <strong>{{ $booking->amadeus_response['booking_reference'] }}</strong></p>
                    @endif
                </div>
            </div>
        @endif

        <div class="flight-checkout__steps-card">
            <div class="flight-checkout__steps">
                <div class="flight-checkout__step is-done"><span>1</span> Flight</div>
                <div class="flight-checkout__step-line"></div>
                <div class="flight-checkout__step {{ empty($confirmed) ? 'is-active' : 'is-done' }}"><span>2</span> Passengers</div>
                <div class="flight-checkout__step-line"></div>
                <div class="flight-checkout__step {{ !empty($confirmed) ? 'is-done' : '' }}"><span>3</span> Payment</div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="flight-checkout__brand-card mb-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <img src="{{ $flight['airline_logo'] ?? 'https://pics.avs.io/120/48/' . ($flight['airline_code'] ?? 'XX') . '.png' }}"
                                 alt="{{ $flight['airline'] }}" class="flight-checkout__airline-logo mr-3">
                            <div>
                                <div class="flight-checkout__airline-name">{{ $flight['airline'] }}</div>
                                <div class="text-muted small">
                                    {{ $flight['flight_number'] }}
                                    @if(!empty($flight['marketing_airline']) && $flight['marketing_airline'] !== $flight['airline'])
                                        · Operated by {{ $flight['airline'] }}
                                    @endif
                                </div>
                                @if(!empty($flight['owner_name']))
                                    <div class="text-muted small">Sold by {{ $flight['owner_name'] }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="text-md-right">
                            <span class="badge badge-success mb-2">{{ $flight['availability_label'] ?? 'Available' }}</span>
                            <div class="flight-checkout__price">{{ $flight['currency'] }} {{ number_format($flight['price'], 0) }}</div>
                            <div class="small text-muted">total for {{ $totalPassengers }} passenger{{ $totalPassengers > 1 ? 's' : '' }}</div>
                        </div>
                    </div>

                    <div class="flight-checkout__route mt-4">
                        <div class="flight-checkout__route-leg">
                            <div class="flight-checkout__route-time">{{ $flight['departure']['time'] }}</div>
                            <div class="flight-checkout__route-place">{{ $flight['departure']['city'] }} ({{ $flight['departure']['airport'] }})</div>
                            <div class="flight-checkout__route-date">{{ $flight['departure']['date'] ?? '' }}</div>
                        </div>
                        <div class="flight-checkout__route-mid">
                            <div class="small text-muted mb-1"><i class="fas fa-clock mr-1"></i>{{ $flight['duration'] }}</div>
                            <div class="flight-checkout__route-line"></div>
                            <span class="badge badge-{{ ($flight['stops'] ?? 0) === 0 ? 'success' : 'secondary' }}">
                                {{ ($flight['stops'] ?? 0) === 0 ? 'Direct' : $flight['stops'] . ' stop(s)' }}
                            </span>
                        </div>
                        <div class="flight-checkout__route-leg text-md-right">
                            <div class="flight-checkout__route-time">{{ $flight['arrival']['time'] }}</div>
                            <div class="flight-checkout__route-place">{{ $flight['arrival']['city'] }} ({{ $flight['arrival']['airport'] }})</div>
                            <div class="flight-checkout__route-date">{{ $flight['arrival']['date'] ?? '' }}</div>
                        </div>
                    </div>

                    <div class="flight-checkout__meta-grid mt-3">
                        <div><span>Cabin</span><strong>{{ $cabinLabel }}</strong></div>
                        <div><span>Baggage</span><strong>{{ $flight['baggage'] ?? 'Check fare' }}</strong></div>
                        <div><span>Ticket</span><strong>{{ $flight['refundable'] ?? 'Varies' }}</strong></div>
                        <div><span>Passengers</span><strong>{{ $totalPassengers }}</strong></div>
                    </div>
                </div>

                @if(empty($confirmed))
                <div class="flight-checkout__form-card mb-4">
                    <div class="p-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0 font-weight-bold">Passenger details</h5>
                            <span class="badge badge-light border">Step 2 of 3</span>
                        </div>

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('flights.booking.process') }}" method="POST" id="booking-form">
                            @csrf
                            <input type="hidden" name="flight_id" value="{{ $flight['id'] }}">
                            <input type="hidden" name="adults" value="{{ $counts['adults'] }}">
                            <input type="hidden" name="children" value="{{ $counts['children'] }}">
                            <input type="hidden" name="infants" value="{{ $counts['infants'] }}">

                            <div class="mb-4">
                                <h6 class="text-muted text-uppercase small font-weight-bold mb-3">Contact for confirmation</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="contact_email"
                                               value="{{ auth()->user()->email ?? old('contact_email') }}" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="contact_phone"
                                               value="{{ old('contact_phone') }}" placeholder="+255 7XX XXX XXX" required>
                                    </div>
                                </div>
                            </div>

                            <div id="passengers-container"></div>

                            <div class="alert alert-light border small mb-4">
                                <i class="fas fa-shield-alt text-primary mr-1"></i>
                                Your details are sent securely to our airline partner (Duffel) to issue tickets after payment.
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the fare rules and booking terms.
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary btn-lg btn-block flight-checkout__submit">
                                <i class="fas fa-lock mr-2"></i>Continue to secure payment
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="flight-checkout__summary sticky-top">
                    <div class="flight-checkout__summary-brand mb-3">
                        <img src="{{ asset('images/logo.png') }}" alt="Zanzibar Bookings" class="flight-checkout__site-logo" onerror="this.style.display='none'">
                        <div>
                            <div class="font-weight-bold">Zanzibar Bookings</div>
                            <div class="small text-muted">Flights powered by Duffel</div>
                        </div>
                    </div>

                    <div class="flight-checkout__summary-row">
                        <span>Fare</span>
                        <strong>{{ $flight['currency'] }} {{ number_format($flight['price'], 2) }}</strong>
                    </div>
                    @if(!empty($flight['tax_amount']))
                    <div class="flight-checkout__summary-row">
                        <span>Taxes included</span>
                        <strong>{{ $flight['currency'] }} {{ number_format($flight['tax_amount'], 2) }}</strong>
                    </div>
                    @endif
                    <div class="flight-checkout__summary-row">
                        <span>Passengers</span>
                        <strong>{{ $totalPassengers }}</strong>
                    </div>
                    <hr>
                    <div class="flight-checkout__summary-total d-flex justify-content-between align-items-center">
                        <span>Total</span>
                        <strong>{{ $flight['currency'] }} {{ number_format($flight['price'], 2) }}</strong>
                    </div>

                    @if($expiresLabel)
                        <div class="flight-checkout__expire mt-3">
                            <i class="fas fa-hourglass-half mr-1"></i> Fare held until {{ $expiresLabel }}
                        </div>
                    @endif

                    <div class="flight-checkout__trust mt-4">
                        <div><i class="fas fa-lock"></i> Secure checkout</div>
                        <div><i class="fas fa-plane-departure"></i> Live airline fares</div>
                        <div><i class="fas fa-credit-card"></i> Pay with Pesapal</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('passengers-container');
    const form = document.getElementById('booking-form');
    if (!container || !form) return;

    const passengers = [];
    @for ($i = 0; $i < $counts['adults']; $i++) passengers.push({ type: 'adult', label: 'Adult {{ $i + 1 }}' }); @endfor
    @for ($i = 0; $i < $counts['children']; $i++) passengers.push({ type: 'child', label: 'Child {{ $i + 1 }}' }); @endfor
    @for ($i = 0; $i < $counts['infants']; $i++) passengers.push({ type: 'infant', label: 'Infant {{ $i + 1 }}' }); @endfor

    passengers.forEach(function (p, index) {
        container.insertAdjacentHTML('beforeend', `
            <div class="passenger-card">
                <div class="passenger-card__title"><i class="fas fa-user mr-2"></i>${p.label}</div>
                <input type="hidden" name="passengers[${index}][type]" value="${p.type}">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">First name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="passengers[${index}][first_name]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Last name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="passengers[${index}][last_name]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Date of birth <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="passengers[${index}][date_of_birth]" max="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Gender <span class="text-danger">*</span></label>
                        <select class="form-control" name="passengers[${index}][gender]" required>
                            <option value="">Select</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                        </select>
                    </div>
                </div>
            </div>
        `);
    });

    form.addEventListener('submit', function () {
        const btn = form.querySelector('.flight-checkout__submit');
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Preparing payment...';
        btn.disabled = true;
    });
});
</script>
@endsection
