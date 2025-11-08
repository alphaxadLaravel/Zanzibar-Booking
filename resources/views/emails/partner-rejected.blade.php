<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Partner Request Update</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color: #f7f7f7; margin: 0; padding: 30px;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 640px; margin: 0 auto; background: #ffffff; border-radius: 12px; overflow: hidden;">
        <tr>
            <td style="background: #003580; color: #ffffff; padding: 24px;">
                <h2 style="margin: 0 0 8px 0; font-size: 24px;">Partner Request Update</h2>
                <p style="margin: 0; font-size: 16px; opacity: 0.85;">Thank you for your interest in partnering with {{ config('app.name') }}.</p>
            </td>
        </tr>
        <tr>
            <td style="padding: 24px; color: #333; line-height: 1.6;">
                <p>Hi {{ $partner->full_name ?? trim(($partner->firstname ?? '') . ' ' . ($partner->lastname ?? '')) }},</p>

                <p>
                    We appreciate the time you took to submit a partner request. After reviewing your application, we’re unable to approve it at this time.
                </p>

                <p>
                    This decision does not prevent you from continuing to use our platform as a valued customer. If you believe we might have missed some important information, or if your circumstances change in the future, feel free to reach out and reapply.
                </p>

                <p>
                    Thank you again for considering a partnership with us. We wish you success and hope to collaborate down the line.
                </p>

                <p style="margin-top: 24px;">
                    Warm regards,<br>
                    <strong>The {{ config('app.name') }} Team</strong>
                </p>
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

