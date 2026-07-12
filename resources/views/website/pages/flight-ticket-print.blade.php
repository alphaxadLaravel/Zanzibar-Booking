<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Ticket {{ $booking->booking_reference }}</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; color: #1a2b42; margin: 0; padding: 24px; background: #f5f7fa; }
        .sheet { max-width: 720px; margin: 0 auto; background: #fff; border: 1px solid #d7dee8; border-radius: 8px; overflow: hidden; }
        .head { background: #003580; color: #fff; padding: 20px 24px; }
        .body { padding: 24px; }
        .refs { display: flex; gap: 12px; margin-bottom: 18px; }
        .refs div { flex: 1; border: 1px solid #e9ecef; border-radius: 6px; padding: 12px; background: #f8fafc; }
        .label { font-size: 11px; text-transform: uppercase; color: #6c757d; }
        .value { font-size: 20px; font-weight: 700; letter-spacing: .05em; margin-top: 4px; }
        .schedule { text-align: center; font-size: 22px; font-weight: 700; margin: 10px 0 18px; }
        .route { display: flex; justify-content: space-between; margin-bottom: 18px; }
        .route strong { font-size: 28px; color: #003580; display: block; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        td { padding: 8px 0; border-bottom: 1px solid #eee; vertical-align: top; }
        .actions { margin: 16px auto; max-width: 720px; text-align: center; }
        .actions button { background: #003580; color: #fff; border: 0; padding: 10px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; }
        @media print { body { background: #fff; padding: 0; } .actions { display: none; } .sheet { border: none; } }
    </style>
</head>
<body>
@php
    $ticket = $booking->ticketDetails();
    $schedule = \App\Support\FlightOfferMapper::formatTimeRange(
        optional($booking->departure_datetime)->format('g:i A'),
        optional($booking->arrival_datetime)->format('g:i A')
    );
@endphp

<div class="actions">
    <button type="button" onclick="window.print()">Print / Save as PDF</button>
</div>

<div class="sheet">
    <div class="head">
        <div style="font-size:20px;font-weight:700;">Zanzibar Bookings — Flight Ticket</div>
        <div>{{ $booking->airline_name }} · {{ $booking->flight_number }}</div>
    </div>
    <div class="body">
        <div class="refs">
            <div>
                <div class="label">Our reference</div>
                <div class="value">{{ $booking->booking_reference }}</div>
            </div>
            <div>
                <div class="label">Airline PNR</div>
                <div class="value">{{ $ticket['airline_pnr'] ?? 'Pending' }}</div>
            </div>
        </div>

        <div class="schedule">{{ $schedule }}</div>

        <div class="route">
            <div>
                <strong>{{ $booking->origin_code }}</strong>
                {{ $booking->origin_name }}<br>
                <small>{{ optional($booking->departure_datetime)->format('D, d M Y') }}</small>
            </div>
            <div style="text-align:center;align-self:center;color:#6c757d;">
                {{ $booking->duration }}<br>
                <small>{{ $booking->stops == 0 ? 'Direct' : $booking->stops . ' stop(s)' }}</small>
            </div>
            <div style="text-align:right;">
                <strong>{{ $booking->destination_code }}</strong>
                {{ $booking->destination_name }}<br>
                <small>{{ optional($booking->arrival_datetime)->format('D, d M Y') }}</small>
            </div>
        </div>

        <table>
            <tr><td>Cabin</td><td><strong>{{ $booking->travel_class }}</strong></td></tr>
            <tr><td>Total paid</td><td><strong>{{ $booking->currency }} {{ number_format((float) $booking->total_price, 2) }}</strong></td></tr>
            <tr><td>Contact</td><td><strong>{{ $booking->contact_email }} · {{ $booking->contact_phone }}</strong></td></tr>
            @if(!empty($ticket['ticket_numbers']))
                <tr><td>E-ticket no.</td><td><strong>{{ implode(', ', $ticket['ticket_numbers']) }}</strong></td></tr>
            @endif
        </table>

        <h3 style="margin-top:22px;">Passengers</h3>
        <table>
            @foreach($booking->passengers as $passenger)
                <tr>
                    <td>{{ $passenger->first_name }} {{ $passenger->last_name }}</td>
                    <td>{{ ucfirst($passenger->type) }}</td>
                </tr>
            @endforeach
        </table>

        <p style="margin-top:20px;font-size:13px;color:#555;">
            Check in with the airline using the PNR and passenger names. Bring ID/passport matching the booked names.
            Times are local airport times.
        </p>
    </div>
</div>
</body>
</html>
