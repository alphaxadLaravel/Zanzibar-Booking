@extends('emails.layout')

@section('content')
<h2>🔔 New Booking Received</h2>

<p>A new booking has been placed on Zanzibar Bookings platform.</p>

<div class="info-box">
    <strong>📋 Booking Details</strong>
    <p class="booking-code">{{ $booking->booking_code }}</p>
    <p><strong>Booking Date:</strong> {{ $booking->created_at->format('F d, Y H:i:s') }}</p>
    <p><strong>Status:</strong> <span style="color: #f39c12;">{{ ucfirst($booking->status) }}</span></p>
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
            @if(isset($data['number_rooms']))
            <tr>
                <td><strong>Rooms:</strong></td>
                <td>{{ $data['number_rooms'] }}</td>
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

<div class="info-box">
    <p class="total-amount">Total Amount: ${{ number_format($booking->total_amount, 2) }}</p>
    <p><strong>Payment Method:</strong> {{ ucfirst($booking->payment_method) }}</p>
</div>

@if($booking->additional_notes)
<div class="info-box">
    <strong>📝 Special Requests from Customer:</strong>
    <p>{{ $booking->additional_notes }}</p>
</div>
@endif

<div class="info-box" style="background-color: #fff3cd; border-color: #ffc107;">
    <strong>⚠️ Action Required:</strong>
    <ul>
        <li>Review and confirm the booking details</li>
        <li>Check availability for all items</li>
        <li>Contact customer if needed</li>
        <li>Monitor payment status</li>
    </ul>
</div>

<center>
    <a href="{{ config('app.url') }}/admin/bookings/{{ $booking->id }}" class="btn">View in Admin Panel</a>
</center>

<p>This is an automated notification from Zanzibar Bookings system.</p>
@endsection

