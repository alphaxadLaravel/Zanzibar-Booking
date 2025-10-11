@extends('emails.layout')

@section('content')
<h2>Reset Your Password 🔐</h2>

<p>Hello,</p>

<p>You are receiving this email because we received a password reset request for your account on Zanzibar Bookings.</p>

<div class="info-box">
    <strong>🔑 Password Reset Request</strong>
    <p>To reset your password, click the button below. This link will expire in 60 minutes.</p>
</div>

<center>
    <a href="{{ $resetUrl }}" class="btn">Reset Password</a>
</center>

<p style="font-size: 14px; color: #666; margin-top: 20px;">
    If the button above doesn't work, copy and paste this link into your browser:<br>
    <a href="{{ $resetUrl }}" style="color: #667eea; word-break: break-all;">{{ $resetUrl }}</a>
</p>

<div class="info-box" style="background-color: #fff3cd; border-color: #ffc107;">
    <strong>⚠️ Security Notice:</strong>
    <ul>
        <li>This password reset link will expire in 60 minutes</li>
        <li>If you didn't request a password reset, please ignore this email</li>
        <li>Your password won't change until you access the link and create a new one</li>
        <li>Never share your password with anyone</li>
    </ul>
</div>

<div class="info-box">
    <strong>💡 Password Tips:</strong>
    <ul>
        <li>Use at least 8 characters</li>
        <li>Include uppercase and lowercase letters</li>
        <li>Add numbers and special characters</li>
        <li>Don't use common words or personal information</li>
    </ul>
</div>

<p>If you're having trouble resetting your password or didn't request this change, please contact our support team immediately.</p>

<p>Best regards,<br>
<strong>The Zanzibar Bookings Team</strong></p>

<p style="font-size: 12px; color: #666; margin-top: 30px;">
    <strong>Need Help?</strong> Contact us at {{ config('mail.from.address') }}
</p>
@endsection

