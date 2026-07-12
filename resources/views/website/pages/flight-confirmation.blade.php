@extends('website.layouts.app')

@section('title', 'Flight Ticket - ' . $booking->booking_reference)

@section('pages')
@php
    $ticket = $booking->ticketDetails();
    $schedule = \App\Support\FlightOfferMapper::formatTimeRange(
        optional($booking->departure_datetime)->format('g:i A'),
        optional($booking->arrival_datetime)->format('g:i A')
    );
@endphp

<style>
    .ticket-page { background: #fff; padding: 24px 0 48px; }
    .ticket-card {
        max-width: 760px;
        margin: 0 auto;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }
    .ticket-card__header {
        background: #003580;
        color: #fff;
        padding: 22px 24px;
    }
    .ticket-card__header h1 { font-size: 22px; margin: 0 0 6px; font-weight: 700; }
    .ticket-card__body { padding: 24px; }
    .ticket-ref {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 20px;
    }
    .ticket-ref__box {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 14px 16px;
        background: #f8fafc;
    }
    .ticket-ref__label { font-size: 11px; text-transform: uppercase; color: #6c757d; letter-spacing: .04em; }
    .ticket-ref__value { font-size: 22px; font-weight: 700; color: #1a2b42; letter-spacing: .06em; margin-top: 4px; }
    .ticket-schedule {
        font-size: 22px;
        font-weight: 700;
        color: #1a2b42;
        text-align: center;
        margin: 8px 0 18px;
    }
    .ticket-route {
        display: flex;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
    }
    .ticket-route strong { display: block; font-size: 28px; color: #003580; }
    .ticket-meta { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 20px; }
    .ticket-meta div { border: 1px solid #e9ecef; border-radius: 6px; padding: 10px 12px; }
    .ticket-meta span { display: block; font-size: 11px; text-transform: uppercase; color: #6c757d; }
    .ticket-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
    .ticket-actions .btn { border-radius: 6px; font-weight: 600; }
    .ticket-pending {
        background: #fff8e1;
        border: 1px solid #ffe082;
        border-radius: 6px;
        padding: 16px;
        margin-bottom: 16px;
    }
    @media (max-width: 576px) {
        .ticket-ref, .ticket-meta, .ticket-route { grid-template-columns: 1fr; display: grid; }
        .ticket-route { text-align: center; }
    }
    @media print {
        .ticket-actions, .flights-hero, .breadcrumb, header, footer, .navbar { display: none !important; }
        .ticket-card { border: none; max-width: 100%; }
    }
</style>

<section class="ticket-page">
    <div class="container">
        @if($booking->status !== 'confirmed')
            <div class="ticket-pending" style="max-width:760px;margin:0 auto 16px;">
                <strong>Payment received — ticket being issued</strong>
                <p class="mb-2 mt-1">We are confirming your seat with the airline. This page will update when your PNR is ready.</p>
                <a href="{{ route('flights.confirmation', $booking->booking_reference) }}" class="btn btn-sm btn-primary">Refresh status</a>
            </div>
        @endif

        <div class="ticket-card" id="flight-ticket">
            <div class="ticket-card__header">
                <h1>{{ $booking->status === 'confirmed' ? 'Your flight ticket' : 'Flight booking' }}</h1>
                <div>{{ $booking->airline_name }} · {{ $booking->flight_number }}</div>
            </div>
            <div class="ticket-card__body">
                <div class="ticket-ref">
                    <div class="ticket-ref__box">
                        <div class="ticket-ref__label">Zanzibar Bookings reference</div>
                        <div class="ticket-ref__value">{{ $booking->booking_reference }}</div>
                    </div>
                    <div class="ticket-ref__box">
                        <div class="ticket-ref__label">Airline PNR / booking reference</div>
                        <div class="ticket-ref__value">{{ $ticket['airline_pnr'] ?? 'Pending…' }}</div>
                    </div>
                </div>

                <div class="ticket-schedule">{{ $schedule }}</div>

                <div class="ticket-route">
                    <div>
                        <strong>{{ $booking->origin_code }}</strong>
                        <div>{{ $booking->origin_name }}</div>
                        <div class="text-muted small">{{ optional($booking->departure_datetime)->format('D, d M Y') }}</div>
                    </div>
                    <div class="text-center align-self-center text-muted">
                        {{ $booking->duration }}<br>
                        <small>{{ $booking->stops == 0 ? 'Direct' : $booking->stops . ' stop(s)' }}</small>
                    </div>
                    <div class="text-right">
                        <strong>{{ $booking->destination_code }}</strong>
                        <div>{{ $booking->destination_name }}</div>
                        <div class="text-muted small">{{ optional($booking->arrival_datetime)->format('D, d M Y') }}</div>
                    </div>
                </div>

                <div class="ticket-meta">
                    <div><span>Cabin</span><strong>{{ $booking->travel_class }}</strong></div>
                    <div><span>Passengers</span><strong>{{ $booking->adults + $booking->children + $booking->infants }}</strong></div>
                    <div><span>Total paid</span><strong>{{ $booking->currency }} {{ number_format((float) $booking->total_price, 2) }}</strong></div>
                    <div><span>Contact</span><strong>{{ $booking->contact_email }}</strong></div>
                </div>

                @if(!empty($ticket['ticket_numbers']))
                    <div class="mb-3">
                        <div class="small text-muted text-uppercase">E-ticket number(s)</div>
                        <strong>{{ implode(' · ', $ticket['ticket_numbers']) }}</strong>
                    </div>
                @endif

                <h5 class="font-weight-bold">Passengers</h5>
                <ul class="list-unstyled mb-4">
                    @foreach($booking->passengers as $passenger)
                        <li class="mb-2">
                            <strong>{{ $passenger->first_name }} {{ $passenger->last_name }}</strong>
                            <span class="text-muted">· {{ ucfirst($passenger->type) }}</span>
                        </li>
                    @endforeach
                </ul>

                <div class="alert alert-light border small mb-0">
                    <strong>Check-in:</strong> Use the airline PNR and passenger names on the airline website or app
                    (usually opens 24–48 hours before departure). Bring ID/passport matching the booked names.
                    Times are local airport times.
                </div>

                <div class="ticket-actions">
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <i class="fas fa-print mr-1"></i> Print / Save as PDF
                    </button>
                    <a href="{{ route('flights.ticket', $booking->booking_reference) }}" class="btn btn-outline-primary" target="_blank">
                        Open printable ticket
                    </a>
                    <a href="{{ route('flights.index') }}" class="btn btn-outline-secondary">Back to flights</a>
                    <a href="{{ route('flights.retrieve') }}" class="btn btn-outline-secondary">Retrieve later</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
