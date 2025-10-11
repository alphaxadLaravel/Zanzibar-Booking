@extends('emails.layout')

@section('content')
<h2>Booking Confirmation ✅</h2>

<p>Dear {{ $booking->fullname }},</p>

<p>Thank you for booking with Zanzibar Bookings! Your reservation has been confirmed.</p>

<div class="info-box">
    <strong>📋 Booking Details</strong>
    <p class="booking-code">{{ $booking->booking_code }}</p>
    <p><strong>Booking Date:</strong> {{ $booking->created_at->format('F d, Y') }}</p>
    <p><strong>Status:</strong> <span style="color: #2ecc71;">{{ ucfirst($booking->status) }}</span></p>
</div>

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
    <strong>📝 Special Requests:</strong>
    <p>{{ $booking->additional_notes }}</p>
</div>
@endif

<h3>👤 Contact Information</h3>
<table>
    <tr>
        <td><strong>Name:</strong></td>
        <td>{{ $booking->fullname }}</td>
    </tr>
    <tr>
        <td><strong>Email:</strong></td>
        <td>{{ $booking->email }}</td>
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

<div class="info-box">
    <strong>📌 Important Information:</strong>
    <ul>
        <li>Please keep your booking code for reference</li>
        <li>You'll receive further instructions via email</li>
        <li>Contact us if you need to make changes to your booking</li>
        <li>Check your email for payment confirmation</li>
    </ul>
</div>

<center>
    <a href="{{ config('app.url') }}/booking-lookup" class="btn">View Booking Details</a>
</center>

<p>We look forward to welcoming you to Zanzibar!</p>

<p>Best regards,<br>
<strong>The Zanzibar Bookings Team</strong></p>
@endsection

