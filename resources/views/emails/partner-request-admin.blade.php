<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Partner Request</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f7f7f7; margin: 0; padding: 30px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden;">
        <tr>
            <td style="background: #003580; color: #ffffff; padding: 24px;">
                <h2 style="margin: 0 0 8px 0; font-size: 24px;">New Partner Request Submitted</h2>
                <p style="margin: 0; font-size: 16px; opacity: 0.85;">A user has requested to become a partner on {{ config('app.name') }}.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px;">
                <h3 style="margin-top: 0; font-size: 18px; color: #003580;">Applicant Details</h3>
                <table cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; width: 35%; color: #555;">Name:</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #222;">{{ $user->full_name ?? trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? '')) }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #555;">Email:</td>
                        <td style="padding: 6px 0; font-weight: 600; color: #222;">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #555;">Phone:</td>
                        <td style="padding: 6px 0; color: #222;">{{ $user->phone ?? 'Not provided' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #555;">Previous Role:</td>
                        <td style="padding: 6px 0; color: #222;">{{ $previousRole ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #555;">Current Status:</td>
                        <td style="padding: 6px 0; color: #222;">{{ $user->status ? 'Active' : 'Pending/Inactive' }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; color: #555;">Requested At:</td>
                        <td style="padding: 6px 0; color: #222;">{{ now()->format('M d, Y h:i A') }}</td>
                    </tr>
                    @if($businessName)
                        <tr>
                            <td style="padding: 6px 0; color: #555;">Business Name:</td>
                            <td style="padding: 6px 0; color: #222;">{{ $businessName }}</td>
                        </tr>
                    @endif
                </table>

                @if($notes)
                    <div style="margin-top: 16px;">
                        <h3 style="font-size: 18px; color: #003580; margin-bottom: 8px;">Additional Notes</h3>
                        <p style="background: #f3f5ff; padding: 16px; border-radius: 8px; color: #333; white-space: pre-line;">{{ $notes }}</p>
                    </div>
                @endif

                <div style="margin-top: 24px; padding: 16px; background: #f8f9fb; border-radius: 8px; border: 1px solid #eef0f5; color: #333;">
                    <strong>Next Steps:</strong>
                    <ol style="padding-left: 20px; margin-top: 10px; margin-bottom: 0;">
                        <li>Review the applicant profile in the admin dashboard.</li>
                        <li>Use the button below to approve instantly, or update the user role to <strong>Partner</strong> in the dashboard.</li>
                        <li>The system will automatically notify the partner once approved.</li>
                    </ol>
                </div>

                <div style="margin-top: 24px; text-align: center;">
                    <a href="{{ $approveUrl }}"
                       style="display: inline-block; padding: 12px 24px; background: #003580; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: 600;">
                        Approve Partner Request
                    </a>
                </div>
            </td>
        </tr>
        <tr>
            <td style="background: #f3f5ff; padding: 16px; text-align: center; color: #777; font-size: 13px;">
                Sent automatically by {{ config('app.name') }} • Please do not reply directly to this email.
            </td>
        </tr>
    </table>
</body>
</html>


