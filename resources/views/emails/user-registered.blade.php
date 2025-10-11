@extends('emails.layout')

@section('content')
<h2>Welcome to Zanzibar Bookings! 🎉</h2>

<p>Dear {{ $user->firstname }} {{ $user->lastname }},</p>

<p>Thank you for creating an account with Zanzibar Bookings! We're excited to have you join our community of travelers exploring the beautiful island of Zanzibar.</p>

<div class="info-box">
    <strong>📧 Verify Your Email Address</strong>
    <p>To complete your registration and access all features, please verify your email address by clicking the button below:</p>
</div>

<center>
    <a href="{{ $verificationUrl }}" class="btn">Verify Email Address</a>
</center>

<p style="font-size: 12px; color: #666;">
    <strong>Note:</strong> This verification link will expire in 60 minutes. If you didn't create an account with us, please ignore this email.
</p>

<div class="info-box">
    <strong>🏝️ What's Next?</strong>
    <ul>
        <li>Browse our exclusive hotels, tours, and activities</li>
        <li>Save your favorite deals to your wishlist</li>
        <li>Book your dream vacation with ease</li>
        <li>Manage your bookings from your dashboard</li>
    </ul>
</div>

<p>If you have any questions or need assistance, our support team is here to help!</p>

<p>Best regards,<br>
<strong>The Zanzibar Bookings Team</strong></p>
@endsection

