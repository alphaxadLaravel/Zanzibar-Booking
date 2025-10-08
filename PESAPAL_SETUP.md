# Pesapal Payment Integration Setup Guide

## Overview
This guide will help you set up Pesapal payment integration for the Zanzibar Booking system.

## Prerequisites
- PHP 8.2 or higher
- Laravel 12
- MySQL database
- Pesapal merchant account (https://www.pesapal.com)

## Installation Steps

### 1. Environment Configuration

Add the following variables to your `.env` file:

```env
# Pesapal Payment Gateway Configuration
# For testing, use sandbox environment
# For production, use live environment

PESAPAL_ENV=sandbox
PESAPAL_CONSUMER_KEY=your_consumer_key_here
PESAPAL_CONSUMER_SECRET=your_consumer_secret_here
PESAPAL_IPN_ID=
PESAPAL_CURRENCY=USD
PESAPAL_CALLBACK_URL="${APP_URL}/payment/success"
PESAPAL_NOTIFICATION_URL="${APP_URL}/payment/confirmation"
```

### 2. Get Pesapal Credentials

1. Go to https://www.pesapal.com
2. Create an account or log in
3. Navigate to your dashboard
4. Get your Consumer Key and Consumer Secret
5. For testing, use the sandbox environment
6. For production, switch to live environment

### 3. Run Database Migrations

Run the migration to update the payments table schema:

```bash
php artisan migrate
```

This will rename the payment table columns to match the Pesapal integration.

### 4. Register IPN (Instant Payment Notification)

Run the custom artisan command to register your IPN with Pesapal:

```bash
php artisan pesapal:register-ipn
```

This command will:
- Register your IPN URL with Pesapal
- Return an IPN ID
- Display instructions to add it to your .env

Copy the IPN ID and add it to your `.env` file:

```env
PESAPAL_IPN_ID="your_ipn_id_here"
```

### 5. Clear Configuration Cache

After updating your `.env` file, clear the configuration cache:

```bash
php artisan config:clear
php artisan cache:clear
```

## Testing Payment Flow

### 1. Make a Booking
- Navigate to your website
- Select a deal (hotel, tour, activity, car, or package)
- Complete the booking form
- Submit the booking

### 2. Process Payment
- After booking, you'll be redirected to the payment page
- The Pesapal payment iframe will load
- Enter test card details (provided by Pesapal for sandbox)
- Complete the payment

### 3. Payment Callbacks

The system handles three callbacks:

**Payment Success (`/payment/success`)**
- User is redirected here after completing payment on Pesapal
- Shows payment pending status
- Payment is not yet confirmed

**Payment Confirmation (`/payment/confirmation`)**
- Pesapal sends IPN to this URL when payment status changes
- System queries Pesapal for actual payment status
- Updates booking and payment records
- Runs automatically in the background

## API Routes

All payment routes are configured in `routes/web.php`:

```php
// Payment processing
Route::get('/payment/{bookingId}', [PaymentController::class, 'processPayment'])
    ->name('payment.process');

// Payment success callback (user redirect)
Route::match(['get', 'post'], '/payment/success', [PaymentController::class, 'paymentSuccess'])
    ->name('payment.success');

// Payment confirmation (IPN from Pesapal)
Route::match(['get', 'post'], '/payment/confirmation', [PaymentController::class, 'paymentConfirmation'])
    ->name('payment.confirmation');
```

## Payment Statuses

The system uses the following payment statuses:

- `PENDING` - Payment initiated but not confirmed
- `COMPLETED` - Payment successful
- `FAILED` - Payment failed
- `CANCELLED` - Payment cancelled by user

## Troubleshooting

### Payment iframe not loading
1. Check your Pesapal credentials in `.env`
2. Verify you're using the correct environment (sandbox/live)
3. Check browser console for errors
4. Verify IPN is registered: `php artisan pesapal:register-ipn`

### IPN not receiving notifications
1. Ensure your `APP_URL` is publicly accessible (use ngrok for local testing)
2. Verify IPN ID is set in `.env`
3. Check Pesapal dashboard for IPN status
4. Review logs: `storage/logs/laravel.log`

### Payment status not updating
1. Check that IPN callbacks are being received (check logs)
2. Verify payment confirmation route is accessible
3. Ensure CSRF is excluded for payment routes (already configured)
4. Test IPN manually using Pesapal dashboard

## Logging

All payment operations are logged for debugging. Check:

```bash
tail -f storage/logs/laravel.log
```

Log entries include:
- Payment process started
- Payment record created
- Pesapal iframe generated
- Payment success callback received
- Payment confirmation received
- Payment status updates

## Security Notes

1. **CSRF Protection**: Payment callback routes are excluded from CSRF verification in `bootstrap/app.php`
2. **Environment Variables**: Never commit `.env` file with real credentials
3. **Sandbox vs Live**: Always test in sandbox before going live
4. **Validation**: All payment data is validated before processing
5. **Logging**: All transactions are logged for audit trail

## Going Live

Before switching to production:

1. Update `.env`:
   ```env
   PESAPAL_ENV=live
   PESAPAL_CONSUMER_KEY=your_live_consumer_key
   PESAPAL_CONSUMER_SECRET=your_live_consumer_secret
   ```

2. Re-register IPN for live environment:
   ```bash
   php artisan pesapal:register-ipn
   ```

3. Update IPN ID in `.env`:
   ```env
   PESAPAL_IPN_ID="your_live_ipn_id"
   ```

4. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

5. Test with real payment using small amount

## Support

For Pesapal API documentation and support:
- API Docs: https://developer.pesapal.com
- Support: support@pesapal.com
- Dashboard: https://www.pesapal.com

## Files Modified/Created

### Created:
- `config/pesapal.php` - Pesapal configuration
- `app/Console/Commands/RegisterPesapalIpn.php` - IPN registration command
- `app/Console/Kernel.php` - Console kernel for commands
- `database/migrations/2025_10_07_200000_update_payments_table_for_pesapal.php` - Payment table update

### Modified:
- `app/Http/Controllers/PaymentController.php` - Payment processing logic
- `app/Models/Booking.php` - Added payment-related methods
- `app/Models/Payment.php` - Updated fillable fields
- `bootstrap/app.php` - CSRF exclusion for payment routes
- `resources/views/website/pages/pesapal.blade.php` - Payment page
- `resources/views/website/pages/payment_success.blade.php` - Success page
- `routes/web.php` - Payment routes (already existed)

## Quick Start Checklist

- [ ] Add Pesapal credentials to `.env`
- [ ] Run migrations: `php artisan migrate`
- [ ] Register IPN: `php artisan pesapal:register-ipn`
- [ ] Add IPN ID to `.env`
- [ ] Clear cache: `php artisan config:clear`
- [ ] Test payment with sandbox credentials
- [ ] Review logs for any errors
- [ ] Test complete booking-to-payment flow

## Example Test Card (Sandbox Only)

Pesapal provides test cards for sandbox. Check their documentation for current test cards:
- https://developer.pesapal.com/how-to-integrate/testing

Remember to use real cards ONLY in live environment!

