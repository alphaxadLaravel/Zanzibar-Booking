@extends('emails.layout')

@section('content')
@if($forAdmin)
    <h2>New flight booking confirmed</h2>
    <p>A customer has paid and a Duffel ticket was issued.</p>
@else
    <h2>Your flight ticket is confirmed</h2>
    <p>Dear passenger,</p>
    <p>Thank you for booking with Zanzibar Bookings. Your flight is confirmed. Keep this email for check-in.</p>
@endif

<div class="info-box">
    <strong>Booking references</strong>
    <p class="booking-code">{{ $booking->booking_reference }}</p>
    @if(!empty($ticket['airline_pnr']))
        <p><strong>Airline booking reference (PNR):</strong> <span style="font-size:22px;font-weight:700;letter-spacing:0.08em;">{{ $ticket['airline_pnr'] }}</span></p>
        <p style="margin:0;color:#555;">Use this PNR to check in on the airline website or app.</p>
    @endif
    @if(!empty($ticket['ticket_numbers']))
        <p style="margin-top:12px;"><strong>E-ticket number(s):</strong> {{ implode(', ', $ticket['ticket_numbers']) }}</p>
    @endif
</div>

<div class="info-box">
    <strong>Flight</strong>
    <table>
        <tr>
            <td><strong>Route</strong></td>
            <td>{{ $booking->origin_code }} → {{ $booking->destination_code }}</td>
        </tr>
        <tr>
            <td><strong>Airline</strong></td>
            <td>{{ $booking->airline_name }} ({{ $booking->flight_number }})</td>
        </tr>
        <tr>
            <td><strong>Departure</strong></td>
            <td>{{ optional($booking->departure_datetime)->format('D, d M Y g:i A') }}</td>
        </tr>
        <tr>
            <td><strong>Arrival</strong></td>
            <td>{{ optional($booking->arrival_datetime)->format('D, d M Y g:i A') }}</td>
        </tr>
        <tr>
            <td><strong>Duration</strong></td>
            <td>{{ $booking->duration }} · {{ $booking->stops == 0 ? 'Direct' : $booking->stops . ' stop(s)' }}</td>
        </tr>
        <tr>
            <td><strong>Class</strong></td>
            <td>{{ $booking->travel_class }}</td>
        </tr>
        <tr>
            <td><strong>Total paid</strong></td>
            <td style="color:#2ecc71;font-weight:bold;">{{ $booking->currency }} {{ number_format((float) $booking->total_price, 2) }}</td>
        </tr>
    </table>
</div>

<h3>Passengers</h3>
@foreach($booking->passengers as $passenger)
    <div class="booking-item">
        <strong>{{ $passenger->first_name }} {{ $passenger->last_name }}</strong>
        <span style="color:#666;"> · {{ ucfirst($passenger->type) }}</span>
        <div style="font-size:13px;color:#555;margin-top:4px;">
            DOB: {{ optional($passenger->date_of_birth)->format('d M Y') ?? $passenger->date_of_birth }}
        </div>
    </div>
@endforeach

<div class="info-box">
    <strong>Contact</strong>
    <p style="margin:6px 0 0;">{{ $booking->contact_email }} · {{ $booking->contact_phone }}</p>
</div>

@unless($forAdmin)
<div class="info-box">
    <strong>How to use your ticket</strong>
    <ol style="margin:8px 0 0;padding-left:18px;">
        <li>Save this email and your airline PNR.</li>
        <li>Check in on the airline website/app using the PNR and passenger names (usually 24–48 hours before departure).</li>
        <li>Bring a valid passport/ID matching the passenger name on the booking.</li>
        <li>Arrive at the airport early for security and boarding.</li>
    </ol>
    <p style="margin-top:14px;">
        <a href="{{ route('flights.ticket', ['bookingReference' => $booking->booking_reference]) }}"
           style="display:inline-block;background:#003580;color:#fff;padding:12px 18px;border-radius:6px;text-decoration:none;font-weight:600;">
            View / print your ticket
        </a>
    </p>
</div>

<p>Need help? Reply to this email or contact sales-reservations@zanzibarbookings.com.</p>
@endunless
@endsection
