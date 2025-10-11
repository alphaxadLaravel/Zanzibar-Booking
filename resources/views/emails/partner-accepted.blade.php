@extends('emails.layout')

@section('content')
<h2>Congratulations! Partnership Approved 🎉</h2>

<p>Dear {{ $partner->firstname }} {{ $partner->lastname }},</p>

<p>We're thrilled to inform you that your partner application has been <strong style="color: #28a745;">APPROVED</strong>! Welcome to the Zanzibar Bookings partner network!</p>

<div class="info-box" style="background-color: #d4edda; border-color: #28a745;">
    <strong>✅ Application Status</strong>
    <p><span style="color: #28a745; font-weight: bold; font-size: 18px;">APPROVED</span></p>
    <p>You can now start listing your properties and managing bookings on our platform.</p>
</div>

<h3>👤 Partner Account Details</h3>
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
        <td><strong>Partner Since:</strong></td>
        <td>{{ now()->format('F d, Y') }}</td>
    </tr>
</table>

<center>
    <a href="{{ $loginUrl }}" class="btn">Access Partner Dashboard</a>
</center>

<div class="info-box">
    <strong>🚀 Getting Started:</strong>
    <ol>
        <li><strong>Login:</strong> Use your email ({{ $partner->email }}) and password to access your dashboard</li>
        <li><strong>Complete Profile:</strong> Add your business information and payment details</li>
        <li><strong>List Properties:</strong> Start adding your hotels, tours, or activities</li>
        <li><strong>Set Pricing:</strong> Configure your rates and availability</li>
        <li><strong>Go Live:</strong> Publish your listings and start receiving bookings!</li>
    </ol>
</div>

<div class="info-box" style="background-color: #fff3cd; border-color: #ffc107;">
    <strong>📚 Resources Available:</strong>
    <ul>
        <li>Partner handbook and guidelines</li>
        <li>Property listing best practices</li>
        <li>Photography tips for your listings</li>
        <li>Marketing and promotion support</li>
        <li>24/7 partner support team</li>
    </ul>
</div>

<h3>💰 Commission Structure</h3>
<div class="info-box">
    <p>As a Zanzibar Bookings partner, you'll benefit from our competitive commission structure:</p>
    <ul>
        <li>Transparent commission rates</li>
        <li>Monthly payment processing</li>
        <li>Detailed financial reports</li>
        <li>No hidden fees</li>
    </ul>
    <p>Our team will provide detailed commission information in your partner dashboard.</p>
</div>

<div class="info-box" style="background-color: #d1ecf1; border-color: #0c5460;">
    <strong>📞 Need Help?</strong>
    <p>Our dedicated partner support team is here to help you succeed:</p>
    <ul>
        <li><strong>Email:</strong> {{ env('ADMIN_EMAIL', config('mail.from.address')) }}</li>
        <li><strong>Partner Support:</strong> Available Monday-Friday, 9 AM - 5 PM EAT</li>
    </ul>
</div>

<p>We're excited to have you as part of the Zanzibar Bookings family and look forward to a successful partnership!</p>

<center>
    <a href="{{ $loginUrl }}" class="btn">Get Started Now</a>
</center>

<p>Best regards,<br>
<strong>The Zanzibar Bookings Partnership Team</strong></p>
@endsection

