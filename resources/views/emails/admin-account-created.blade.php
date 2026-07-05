@extends('emails.layout')

@section('content')
<h2>Your admin account is ready</h2>

<p>Dear {{ $admin->firstname }} {{ $admin->lastname }},</p>

<p>A Super Admin has created an admin account for you on <strong>Zanzibar Bookings</strong>. Use the credentials below to sign in and access the areas assigned to your role.</p>

<div class="info-box" style="background-color: #d4edda; border-color: #28a745;">
    <strong>Login credentials</strong>
    <table style="margin-top: 12px;">
        <tr>
            <td><strong>Email:</strong></td>
            <td>{{ $admin->email }}</td>
        </tr>
        <tr>
            <td><strong>Password:</strong></td>
            <td><code style="background: #f8f9fa; padding: 2px 6px; border-radius: 4px;">{{ $plainPassword }}</code></td>
        </tr>
    </table>
</div>

<center>
    <a href="{{ $loginUrl }}" class="btn">Sign in</a>
</center>

<div class="info-box" style="background-color: #fff3cd; border-color: #ffc107;">
    <strong>Security reminder</strong>
    <p>Please change your password after your first login from <strong>Profile → Edit Profile</strong> in the admin panel.</p>
    <p>Do not share these credentials with anyone. If you did not expect this email, contact the site administrator immediately.</p>
</div>

<p>After signing in, open the dashboard here:</p>
<p><a href="{{ $dashboardUrl }}">{{ $dashboardUrl }}</a></p>

<p>Best regards,<br>
<strong>The Zanzibar Bookings Team</strong></p>
@endsection
