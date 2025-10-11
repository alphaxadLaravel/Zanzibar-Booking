# Email Configuration Guide

## Hostinger Email Configuration

Add the following configuration to your `.env` file:

```bash
# ====================================
# EMAIL CONFIGURATION FOR HOSTINGER
# ====================================

# Mail Driver
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=587
MAIL_USERNAME=system@zanzibarbookings.com
MAIL_PASSWORD=your_email_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=system@zanzibarbookings.com
MAIL_FROM_NAME="Zanzibar Bookings"

# Admin Email
ADMIN_EMAIL=sales-reservations@zanzibarbookings.com
ADMIN_NAME="Zanzibar Bookings Admin"

# Application URLs
APP_URL=https://zanzibarbookings.com
APP_NAME="Zanzibar Bookings"
```

## Setup Instructions

1. **Add Configuration to .env**
   - Copy the above configuration to your `.env` file
   - Replace `your_email_password_here` with your actual email password from Hostinger

2. **Run Database Migrations**
   ```bash
   php artisan migrate
   ```
   This will create the `newsletters` table for newsletter subscriptions.

3. **Test Email Configuration**
   You can test if emails are working by:
   - Registering a new user
   - Using the forgot password feature
   - Creating a test booking

## Email Templates Created

1. **User Registration Email** - Email verification sent to new users
2. **Booking Confirmation Email (User)** - Sent to user with booking details
3. **Booking Notification Email (Admin)** - Sent to admin about new booking
4. **Payment Success Email (User)** - Sent to user when payment is completed
5. **Payment Success Email (Admin)** - Sent to admin when payment is received
6. **Partner Registration Email** - Sent to partner when they register
7. **Partner Accepted Email** - Sent to partner when their application is approved
8. **Newsletter Email** - Template for newsletter campaigns
9. **Forgot Password Email** - Password reset link email

## Testing Emails

After configuration, you can test emails by:

1. Registering a new user
2. Creating a booking
3. Processing a payment
4. Using the forgot password feature

## Sending Newsletters

### Via Command Line

You can send newsletters to all subscribers using the artisan command:

```bash
# Send newsletter with inline content
php artisan newsletter:send "Monthly Newsletter" --content="<h1>Hello!</h1><p>This is our newsletter</p>"

# Send newsletter from HTML file
php artisan newsletter:send "Monthly Newsletter" --file=/path/to/newsletter.html
```

### Via Code

You can also send newsletters programmatically:

```php
use App\Helpers\NewsletterHelper;

// Send to all subscribers
$result = NewsletterHelper::sendNewsletter(
    'Newsletter Subject',
    '<h1>Hello!</h1><p>Newsletter content here</p>'
);

// Send to specific emails
$result = NewsletterHelper::sendToSpecificEmails(
    ['email1@example.com', 'email2@example.com'],
    'Newsletter Subject',
    '<h1>Hello!</h1><p>Newsletter content here</p>'
);

// Result contains: ['total' => X, 'success' => Y, 'failed' => Z]
```

## Email Automation Flows

### 1. User Registration Flow
- User registers → Welcome email with verification link sent
- User clicks verification link → Email verified

### 2. Booking Flow
- User creates booking → Booking confirmation sent to user
- User creates booking → Booking notification sent to admin
- Payment method selected → User redirected to payment or offline instructions

### 3. Payment Flow
- Payment completed → Payment success email sent to user
- Payment completed → Payment notification sent to admin
- Booking status updated to "confirmed"

### 4. Partner Registration Flow
- Admin changes user role to "Partner" → Partner acceptance email sent
- Partner account activated → Partner can access dashboard

### 5. Password Reset Flow
- User requests password reset → Reset link email sent
- User clicks reset link → Can set new password

## Troubleshooting

- Make sure port 587 is open on your server
- Verify email credentials are correct in Hostinger cPanel
- Check `storage/logs/laravel.log` for email sending errors
- Ensure `MAIL_MAILER=smtp` (not `log` or `array`)
- Test with a simple email first before sending to all subscribers

## Security Notes

- Email verification links expire after 60 minutes
- Password reset links expire after 60 minutes
- Newsletter unsubscribe links are encoded for security
- All emails are logged for troubleshooting purposes

