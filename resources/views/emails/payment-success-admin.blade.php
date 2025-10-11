@extends('emails.layout')

@section('content')
<h2>💰 Payment Received</h2>

<p>A payment has been successfully processed for booking <strong>{{ $booking->booking_code }}</strong>.</p>

<div class="info-box" style="background-color: #d4edda; border-color: #28a745;">
    <strong>✅ Payment Details</strong>
    <p class="total-amount" style="color: #28a745;">${{ number_format($payment->amount, 2) }}</p>
    <p><strong>Transaction ID:</strong> {{ $payment->transactionid }}</p>
    <p><strong>Tracking ID:</strong> {{ $payment->trackingid ?? 'N/A' }}</p>
    <p><strong>Payment Date:</strong> {{ $payment->updated_at->format('F d, Y H:i:s') }}</p>
    <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
    <p><strong>Status:</strong> <span style="color: #28a745;">{{ $payment->status }}</span></p>
</div>

<h3>👤 Customer Information</h3>
<table>
    <tr>
        <td><strong>Name:</strong></td>
        <td>{{ $booking->fullname }}</td>
    </tr>
    <tr>
        <td><strong>Email:</strong></td>
        <td><a href="mailto:{{ $booking->email }}">{{ $booking->email }}</a></td>
    </tr>
    <tr>
        <td><strong>Phone:</strong></td>
        <td>{{ $booking->phone }}</td>
    </tr>
    <tr>
        <td><strong>Country:</strong></td>
        <td>{{ $booking->country }}</td>
    </tr>
</table>

<h3>📦 Booking Items</h3>

@foreach($bookingItems as $item)
    @php
        $deal = $item['deal'];
        $room = $item['room'];
        $data = $item['item_data'];
    @endphp
    
    @if($deal)
    <div class="booking-item">
        <h4 style="margin-top: 0; color: #667eea;">{{ $deal->title }}</h4>
        
        @if($room)
            <p><strong>Room Type:</strong> {{ $room->name }}</p>
        @endif
        
        <table>
            <tr>
                <td><strong>Type:</strong></td>
                <td>{{ ucfirst($data['type']) }}</td>
            </tr>
            @if(isset($data['check_in']))
            <tr>
                <td><strong>Check-in:</strong></td>
                <td>{{ \Carbon\Carbon::parse($data['check_in'])->format('F d, Y') }}</td>
            </tr>
            @endif
            @if(isset($data['check_out']))
            <tr>
                <td><strong>Check-out:</strong></td>
                <td>{{ \Carbon\Carbon::parse($data['check_out'])->format('F d, Y') }}</td>
            </tr>
            @endif
            @if(isset($data['adults']))
            <tr>
                <td><strong>Guests:</strong></td>
                <td>{{ $data['adults'] }} Adult(s) @if(isset($data['children']) && $data['children'] > 0), {{ $data['children'] }} Child(ren) @endif</td>
            </tr>
            @endif
            <tr>
                <td><strong>Price:</strong></td>
                <td style="color: #2ecc71; font-weight: bold;">${{ number_format($data['total_price'], 2) }}</td>
            </tr>
        </table>
    </div>
    @endif
@endforeach

@if($booking->additional_notes)
<div class="info-box">
    <strong>📝 Special Requests:</strong>
    <p>{{ $booking->additional_notes }}</p>
</div>
@endif

<div class="info-box" style="background-color: #d1ecf1; border-color: #0c5460;">
    <strong>✓ Next Steps:</strong>
    <ul>
        <li>Booking is now confirmed and paid</li>
        <li>Prepare services for the customer</li>
        <li>Send any additional booking details if needed</li>
        <li>Update booking status in admin panel if required</li>
    </ul>
</div>

<center>
    <a href="{{ config('app.url') }}/admin/bookings/{{ $booking->id }}" class="btn">View in Admin Panel</a>
</center>

<p>This is an automated notification from Zanzibar Bookings system.</p>
@endsection

