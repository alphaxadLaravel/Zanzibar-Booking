@extends('emails.layout')

@section('content')
<h2>Partner Application Received 🤝</h2>

<p>Dear {{ $partner->firstname }} {{ $partner->lastname }},</p>

<p>Thank you for your interest in becoming a partner with Zanzibar Bookings! We're excited about the possibility of working together to showcase the beauty of Zanzibar to travelers worldwide.</p>

<div class="info-box">
    <strong>📋 Application Status</strong>
    <p><span style="color: #f39c12; font-weight: bold;">Under Review</span></p>
    <p>Your partner application has been received and is currently being reviewed by our team.</p>
</div>

<h3>👤 Your Information</h3>
<table>
    <tr>
        <td><strong>Name:</strong></td>
        <td>{{ $partner->firstname }} {{ $partner->lastname }}</td>
    </tr>
    <tr>
        <td><strong>Email:</strong></td>
        <td>{{ $partner->email }}</td>
    </tr>
    <tr>
        <td><strong>Registration Date:</strong></td>
        <td>{{ $partner->created_at->format('F d, Y') }}</td>
    </tr>
</table>

<div class="info-box">
    <strong>📌 What Happens Next?</strong>
    <ul>
        <li>Our team will review your application within 2-3 business days</li>
        <li>We may contact you for additional information if needed</li>
        <li>You'll receive an email notification once your application is approved</li>
        <li>Upon approval, you'll get access to our partner dashboard</li>
    </ul>
</div>

<div class="info-box" style="background-color: #d1ecf1; border-color: #0c5460;">
    <strong>💡 Partner Benefits:</strong>
    <ul>
        <li>List your properties on our platform</li>
        <li>Access to our booking management system</li>
        <li>Exposure to thousands of travelers</li>
        <li>Dedicated partner support</li>
        <li>Real-time booking notifications</li>
        <li>Commission-based revenue model</li>
    </ul>
</div>

<p>If you have any questions about the partnership program, please don't hesitate to contact us.</p>

<p>Best regards,<br>
<strong>The Zanzibar Bookings Partnership Team</strong></p>

<p style="font-size: 12px; color: #666;">
    <strong>Contact Us:</strong> {{ env('ADMIN_EMAIL', config('mail.from.address')) }}
</p>
@endsection

