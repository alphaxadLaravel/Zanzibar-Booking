@extends('emails.layout')

@section('content')
<h2>Payment Successful! 💳✅</h2>

<p>Dear {{ $booking->fullname }},</p>

<p>Great news! Your payment has been successfully processed. Your booking is now confirmed and we're excited to welcome you to Zanzibar!</p>

<div class="info-box" style="background-color: #d4edda; border-color: #28a745;">
    <strong>✅ Payment Confirmed</strong>
    <p class="total-amount" style="color: #28a745;">${{ number_format($payment->amount, 2) }}</p>
    <p><strong>Transaction ID:</strong> {{ $payment->transactionid }}</p>
    <p><strong>Payment Date:</strong> {{ $payment->updated_at->format('F d, Y H:i:s') }}</p>
    <p><strong>Payment Method:</strong> {{ $payment->payment_method }}</p>
</div>

<div class="info-box">
    <strong>📋 Booking Information</strong>
    <p class="booking-code">{{ $booking->booking_code }}</p>
    <p><strong>Booking Status:</strong> <span style="color: #28a745;">Confirmed & Paid</span></p>
</div>

<h3>📦 Your Booking Items</h3>

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

<div class="info-box">
    <strong>📌 What's Next?</strong>
    <ul>
        <li>You'll receive additional details about your booking via email</li>
        <li>Save your booking code for reference: <strong>{{ $booking->booking_code }}</strong></li>
        <li>Check your spam folder if you don't receive further emails</li>
        <li>Contact us if you need any assistance or have questions</li>
    </ul>
</div>

<center>
    <a href="{{ config('app.url') }}/booking-lookup" class="btn">View Full Booking Details</a>
</center>

<div class="info-box" style="background-color: #fff3cd; border-color: #ffc107;">
    <strong>💡 Travel Tips:</strong>
    <ul>
        <li>Check visa requirements for Tanzania</li>
        <li>Pack sunscreen and light clothing</li>
        <li>Don't forget your travel insurance</li>
        <li>Arrive at least 2 hours before your flight</li>
    </ul>
</div>

<p>We can't wait to welcome you to the beautiful island of Zanzibar!</p>

<p>Best regards,<br>
<strong>The Zanzibar Bookings Team</strong></p>

<p style="font-size: 12px; color: #666;">
    <strong>Need Help?</strong> Contact us at {{ config('mail.from.address') }} or visit our website.
</p>
@endsection

