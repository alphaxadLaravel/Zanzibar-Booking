@extends('website.layouts.app')

@section('title', 'My Flights - Zanzibar Bookings')

@section('pages')
<style>
    .my-flights { padding: 32px 0 48px; background: #fff; }
    .my-flights h1 { font-size: 28px; font-weight: 700; color: #1a2b42; margin-bottom: 8px; }
    .flight-booking-row {
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 16px 18px;
        margin-bottom: 12px;
        background: #fff;
    }
    .flight-booking-row__ref { font-weight: 700; color: #003580; letter-spacing: .04em; }
    .status-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .status-pill--confirmed { background: #e8f5e9; color: #2e7d32; }
    .status-pill--pending { background: #fff8e1; color: #f57c00; }
    .status-pill--cancelled { background: #ffebee; color: #c62828; }
</style>

<section class="my-flights">
    <div class="container">
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
            <div>
                <h1 class="mb-1">My Flights</h1>
                <p class="text-muted mb-0">View and reprint tickets for flights booked with your account or email.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('flights.retrieve') }}" class="btn btn-outline-primary btn-sm">Retrieve another ticket</a>
                <a href="{{ route('flights.index') }}" class="btn btn-primary btn-sm">Book a flight</a>
            </div>
        </div>

        @forelse($bookings as $booking)
            @php
                $ticket = $booking->ticketDetails();
                $statusClass = match($booking->status) {
                    'confirmed' => 'status-pill--confirmed',
                    'cancelled' => 'status-pill--cancelled',
                    default => 'status-pill--pending',
                };
            @endphp
            <div class="flight-booking-row">
                <div class="row align-items-center">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <div class="flight-booking-row__ref">{{ $booking->booking_reference }}</div>
                        <div class="small text-muted">{{ optional($booking->created_at)->format('d M Y H:i') }}</div>
                        <span class="status-pill {{ $statusClass }} mt-1">{{ $booking->status }}</span>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <strong>{{ $booking->origin_code }} → {{ $booking->destination_code }}</strong>
                        <div class="small text-muted">{{ $booking->airline_name }} · {{ $booking->flight_number }}</div>
                        <div class="small">{{ optional($booking->departure_datetime)->format('D, d M Y g:i A') }}</div>
                    </div>
                    <div class="col-md-2 mb-2 mb-md-0">
                        <div class="small text-muted">Airline PNR</div>
                        <strong>{{ $ticket['airline_pnr'] ?? '—' }}</strong>
                        <div class="small text-muted mt-1">{{ $booking->currency }} {{ number_format((float) $booking->total_price, 2) }}</div>
                    </div>
                    <div class="col-md-3 text-md-right">
                        <a href="{{ route('flights.confirmation', $booking->booking_reference) }}" class="btn btn-sm btn-primary mb-1">
                            View ticket
                        </a>
                        <a href="{{ route('flights.ticket', $booking->booking_reference) }}" class="btn btn-sm btn-outline-primary mb-1" target="_blank">
                            Print
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="alert alert-light border text-center py-5">
                <h5 class="mb-2">No flight bookings yet</h5>
                <p class="text-muted mb-3">When you book a flight, it will appear here for reprinting anytime.</p>
                <a href="{{ route('flights.index') }}" class="btn btn-primary">Search flights</a>
                <a href="{{ route('flights.retrieve') }}" class="btn btn-outline-secondary ml-1">Retrieve a ticket</a>
            </div>
        @endforelse

        @if($bookings->hasPages())
            <div class="mt-3">{{ $bookings->links() }}</div>
        @endif
    </div>
</section>
@endsection
