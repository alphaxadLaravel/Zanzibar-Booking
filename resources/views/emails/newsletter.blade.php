@extends('emails.layout')

@section('content')
<h2>{{ $subject }}</h2>

<div style="line-height: 1.8;">
    {!! $content !!}
</div>

<div style="margin-top: 40px; padding-top: 20px; border-top: 2px solid #e0e0e0;">
    <center>
        <a href="{{ config('app.url') }}" class="btn">Visit Our Website</a>
    </center>
</div>

<div class="info-box" style="background-color: #f8f9fa; margin-top: 30px;">
    <strong>🏝️ Explore Zanzibar:</strong>
    <ul>
        <li><a href="{{ config('app.url') }}/hotels" style="color: #667eea;">Browse Hotels</a></li>
        <li><a href="{{ config('app.url') }}/tours" style="color: #667eea;">Discover Tours</a></li>
        <li><a href="{{ config('app.url') }}/activities" style="color: #667eea;">View Activities</a></li>
        <li><a href="{{ config('app.url') }}/packages" style="color: #667eea;">Check Packages</a></li>
    </ul>
</div>

<p style="margin-top: 30px;">
    Thank you for being part of our community!
</p>

<p>Best regards,<br>
<strong>The Zanzibar Bookings Team</strong></p>

<div style="margin-top: 30px; padding: 15px; background-color: #f8f9fa; border-radius: 5px; text-align: center; font-size: 12px;">
    <p style="color: #666;">
        You're receiving this email because you subscribed to our newsletter.<br>
        <a href="{{ $unsubscribeUrl }}" style="color: #667eea;">Unsubscribe</a> | 
        <a href="{{ config('app.url') }}/page/privacy-policy" style="color: #667eea;">Privacy Policy</a>
    </p>
</div>
@endsection

