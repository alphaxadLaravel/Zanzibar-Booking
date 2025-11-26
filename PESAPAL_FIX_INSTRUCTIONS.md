# Pesapal Payment Callback URL Fix

## Problem
Error: "callback route does not exist : N/A"

This error occurs when `APP_URL` is not set correctly in your `.env` file, causing the `route()` helper to return "N/A" instead of a valid URL.

## Solution Applied
Updated `PaymentController.php` to:
1. Validate `APP_URL` before generating callback URLs
2. Use `url()` helper as fallback if `route()` fails
3. Ensure absolute URLs are always generated
4. Provide better error messages

## Required .env Configuration

Make sure your `.env` file has the following settings:

```env
# Required: Your application URL (must be absolute URL with http:// or https://)
APP_URL=https://yourdomain.com

# Or for local development:
# APP_URL=http://localhost:8000

# Pesapal Configuration (lines 73-79 in your .env)
PESAPAL_CONSUMER_KEY=your_consumer_key_here
PESAPAL_CONSUMER_SECRET=your_consumer_secret_here
PESAPAL_ENV=sandbox
# or for production:
# PESAPAL_ENV=live

# Optional: If you want to override callback URLs
# PESAPAL_CALLBACK_URL=https://yourdomain.com/payment/success
# PESAPAL_NOTIFICATION_URL=https://yourdomain.com/payment/confirmation

# Currency (optional, defaults to USD)
PESAPAL_CURRENCY=USD
```

## Important Notes

1. **APP_URL must be set correctly**: 
   - For production: `APP_URL=https://yourdomain.com` (no trailing slash)
   - For local: `APP_URL=http://localhost:8000` (or your local port)
   - Must include `http://` or `https://`
   - Must NOT have a trailing slash

2. **After updating .env**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

3. **Verify routes exist**:
   ```bash
   php artisan route:list --name=payment
   ```
   You should see:
   - `payment.success`
   - `payment.confirmation`
   - `payment.callback`

## Testing

After updating your `.env` file:
1. Clear config cache: `php artisan config:clear`
2. Try making a booking with Pesapal payment
3. Check logs: `storage/logs/laravel.log` for any URL generation errors

## Common Issues

- **"N/A" in callback URL**: APP_URL is missing or incorrect
- **"localhost" in production**: APP_URL is set to localhost instead of your domain
- **Missing http:// or https://**: APP_URL must include the protocol

