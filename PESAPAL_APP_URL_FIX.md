# Pesapal Callback Route Error Fix

## Problem
Error: "callback route does not exist : N/A"

## Root Cause
Your `.env` file has:
```
APP_URL=https://zanzibarbookings.com
```

But your routes are generating URLs with `www`:
```
https://www.zanzibarbookings.com/payment/success
```

The Pesapal library validates the callback route internally and fails when there's a domain mismatch.

## Solution

**Update your `.env` file to match your actual domain:**

```env
# Use the domain that matches your actual site (with or without www)
APP_URL=https://www.zanzibarbookings.com
```

**OR if your site doesn't use www:**

```env
APP_URL=https://zanzibarbookings.com
```

**Important:** The APP_URL must match exactly what your site uses. Check your actual domain by visiting your site and seeing if it redirects to www or not.

## Steps to Fix

1. **Check your actual domain:**
   - Visit your site: `https://www.zanzibarbookings.com` or `https://zanzibarbookings.com`
   - See which one your site uses (with or without www)

2. **Update `.env` file:**
   ```env
   APP_URL=https://www.zanzibarbookings.com
   ```
   (Use the exact domain your site uses)

3. **Clear config cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

4. **Verify routes work:**
   ```bash
   php artisan route:list --name=payment
   ```

5. **Test the payment flow again**

## Why This Happens

The Pesapal library (knox/pesapal) uses `config('pesapal.callback_route')` which is set to `'payment.success'`. When the library tries to resolve this route using `route('payment.success')`, it uses `APP_URL` to generate the full URL. If `APP_URL` doesn't match your actual domain, the library's internal validation fails.

## Verification

After fixing, check the logs. You should see:
- `callback_url`: A valid URL matching your APP_URL
- No "N/A" errors
- Successful iframe generation

