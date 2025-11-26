# Pesapal Callback Route Fix

## Problem
Error: "callback route does not exist : N/A"

## Root Cause
Your `.env` file has:
```
PESAPAL_CALLBACK_ROUTE=paymentsuccess
```

But it should be:
```
PESAPAL_CALLBACK_ROUTE=payment.success
```

**Note the dot (.) between "payment" and "success"!**

The Pesapal library uses `config('pesapal.callback_route')` internally and tries to resolve it with `route('paymentsuccess')`, but your actual route is named `payment.success` (with a dot).

## Solution

**Update your `.env` file:**

```env
# Remove this line if it exists (or update it):
# PESAPAL_CALLBACK_ROUTE=paymentsuccess  ❌ WRONG

# Add or update to:
PESAPAL_CALLBACK_ROUTE=payment.success  ✅ CORRECT
```

**OR simply remove the line entirely** - the config file has the correct default value.

## Steps to Fix

1. **Open your `.env` file**

2. **Find the line:**
   ```env
   PESAPAL_CALLBACK_ROUTE=paymentsuccess
   ```

3. **Change it to:**
   ```env
   PESAPAL_CALLBACK_ROUTE=payment.success
   ```
   (Make sure there's a dot between "payment" and "success")

4. **OR remove the line entirely** - the default in `config/pesapal.php` is already correct

5. **Clear config cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

6. **Test the payment flow again**

## Verification

After fixing, check the logs. You should see:
- `"callback_route_config":"payment.success"` (with dot) ✅
- No "N/A" errors
- Successful iframe generation

## Why This Happens

The Pesapal library (knox/pesapal) uses `config('pesapal.callback_route')` to validate the callback route internally. When you call `Pesapal::makePayment()`, the library tries to resolve the route name using Laravel's `route()` helper. If the route name doesn't match exactly (e.g., `paymentsuccess` vs `payment.success`), it fails with "callback route does not exist : N/A".

## Additional Notes

- The route name in `routes/web.php` is `payment.success` (with dot)
- The config default in `config/pesapal.php` is `payment.success` (with dot)
- Your `.env` should either match this or be removed to use the default

