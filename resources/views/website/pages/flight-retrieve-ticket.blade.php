@extends('website.layouts.app')

@section('title', 'Retrieve Flight Ticket - Zanzibar Bookings')
@section('meta')
<meta name="description" content="Retrieve and reprint your flight ticket using your email and booking reference">
@endsection

@section('pages')
<style>
    .retrieve-shell { max-width: 560px; margin: 0 auto; }
    .retrieve-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 28px 24px;
    }
    .retrieve-card h1 {
        font-size: 24px;
        font-weight: 700;
        color: #1a2b42;
        margin-bottom: 8px;
    }
    .retrieve-card .lead {
        color: #6c757d;
        font-size: 15px;
        margin-bottom: 24px;
    }
    .retrieve-card .form-control {
        height: 48px;
        border-radius: 6px;
        border-color: #d7dee8;
    }
    .retrieve-card .btn-primary {
        background: #003580;
        border-color: #003580;
        height: 48px;
        font-weight: 600;
        border-radius: 6px;
    }
    .retrieve-help {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 18px 20px;
        margin-top: 16px;
        background: #f8fafc;
    }
</style>

<div class="container py-5">
    <div class="retrieve-shell">
        <div class="retrieve-card">
            <h1>Retrieve your flight ticket</h1>
            <p class="lead">Enter the email used at checkout and your booking reference to view or reprint your ticket.</p>

            <form method="POST" action="{{ route('flights.retrieve.post') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email used for booking</label>
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', auth()->user()->email ?? '') }}"
                           placeholder="you@example.com"
                           required>
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="booking_reference" class="form-label">Booking reference</label>
                    <input type="text"
                           id="booking_reference"
                           name="booking_reference"
                           class="form-control text-uppercase @error('booking_reference') is-invalid @enderror"
                           value="{{ old('booking_reference') }}"
                           placeholder="e.g. ZB1A2B3C or airline PNR"
                           required>
                    @error('booking_reference')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Use your Zanzibar Bookings code (ZB…) or the airline PNR from your ticket email.</small>
                </div>

                <button type="submit" class="btn btn-primary btn-block w-100">
                    Find my ticket
                </button>
            </form>
        </div>

        <div class="retrieve-help">
            <strong>Need help?</strong>
            <p class="text-muted mb-3 mt-2 small">
                Check your inbox for “Your flight ticket”. The reference is in that email.
                @auth
                    You can also open <a href="{{ route('flights.my-bookings') }}">My Flights</a> if you booked while logged in.
                @endauth
            </p>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('flights.index') }}" class="btn btn-outline-secondary btn-sm">Search flights</a>
                <a href="mailto:sales-reservations@zanzibarbookings.com" class="btn btn-outline-primary btn-sm">Email support</a>
            </div>
        </div>
    </div>
</div>
@endsection
