# Troubleshooting: Invalid consumer secret/key

## Error Analysis
The error "Invalid consumer secret/key" means Pesapal cannot authenticate your API credentials.

## Step-by-Step Fix

### 1. Verify .env Configuration

Check your `.env` file has these lines:
```bash
PESAPAL_ENV=sandbox
PESAPAL_CONSUMER_KEY=your_actual_key_here
PESAPAL_CONSUMER_SECRET=your_actual_secret_here
```

**Important:** Remove any quotes, spaces, or special characters around the values.

Example of CORRECT format:
```bash
PESAPAL_CONSUMER_KEY=qkio1BGGYAXTu2JOfjwXYqm93n0bPSTQ
PESAPAL_CONSUMER_SECRET=osGQ364R49cXKeOYSpaOnT/wnfP+1tqA
```

Example of INCORRECT format:
```bash
PESAPAL_CONSUMER_KEY="qkio1BGGYAXTu2JOfjwXYqm93n0bPSTQ"  ❌ (has quotes)
PESAPAL_CONSUMER_KEY= qkio1BGGYAXTu2JOfjwXYqm93n0bPSTQ  ❌ (space after =)
```

### 2. Get Your Credentials from Pesapal

#### For Sandbox (Testing):
1. Go to: https://developer.pesapal.com/
2. Log in to your developer account
3. Navigate to: **Dashboard → API Keys**
4. Copy your **Sandbox Consumer Key** and **Consumer Secret**

#### For Live (Production):
1. Go to: https://www.pesapal.com/
2. Log in to your merchant account
3. Navigate to: **Settings → API Keys**
4. Copy your **Live Consumer Key** and **Consumer Secret**

### 3. Update Your .env File

Edit your `.env` file (located at the root of your project):

```bash
nano .env
```

or

```bash
vi .env
```

Add/update these lines:
```bash
PESAPAL_ENV=sandbox
PESAPAL_CONSUMER_KEY=paste_your_consumer_key_here
PESAPAL_CONSUMER_SECRET=paste_your_consumer_secret_here
PESAPAL_IPN_ID=
PESAPAL_CURRENCY=USD
```

**Save the file** (Ctrl+X, then Y for nano)

### 4. Clear Configuration Cache

After updating .env, you MUST clear the cache:

```bash
php artisan config:clear
php artisan cache:clear
```

### 5. Test Configuration

Check if credentials are loaded:

```bash
php artisan tinker
```

Then run:
```php
config('pesapal.consumer_key')
config('pesapal.consumer_secret')
config('pesapal.environment')
```

You should see your actual values (not null).

Type `exit` to leave tinker.

### 6. Try Registering IPN Again

```bash
php artisan pesapal:register-ipn
```

## Common Issues

### Issue 1: Credentials are empty
**Solution:** Make sure you've actually added the credentials to .env

Check with:
```bash
grep PESAPAL .env
```

### Issue 2: Using wrong environment credentials
**Problem:** You have live credentials but .env says sandbox (or vice versa)

**Solution:** Match your credentials to the environment:
- Sandbox credentials → `PESAPAL_ENV=sandbox`
- Live credentials → `PESAPAL_ENV=live`

### Issue 3: Credentials have extra spaces or quotes
**Solution:** Remove any quotes or spaces

WRONG:
```bash
PESAPAL_CONSUMER_KEY=" abc123 "
```

RIGHT:
```bash
PESAPAL_CONSUMER_KEY=abc123
```

### Issue 4: Config cache not cleared
**Solution:** Always clear cache after editing .env:
```bash
php artisan config:clear
php artisan cache:clear
```

## Verification Checklist

- [ ] I have a Pesapal account (developer or merchant)
- [ ] I've copied the Consumer Key from Pesapal dashboard
- [ ] I've copied the Consumer Secret from Pesapal dashboard
- [ ] I've added both to .env file WITHOUT quotes
- [ ] The environment matches (sandbox/live)
- [ ] I've run `php artisan config:clear`
- [ ] I've run `php artisan cache:clear`
- [ ] I've verified config values in tinker

## Still Not Working?

### Check Config File

```bash
php artisan tinker
```

```php
// Check if config is loading
dd([
    'consumer_key' => config('pesapal.consumer_key'),
    'consumer_secret' => config('pesapal.consumer_secret'),
    'environment' => config('pesapal.environment'),
]);
```

If any value is `null`, the .env values aren't being read.

### Check .env Syntax

Your .env file should look like this:

```bash
APP_NAME="Zanzibar Bookings"
APP_ENV=production
APP_URL=https://zanzibarbookings.com

# ... other settings ...

PESAPAL_ENV=sandbox
PESAPAL_CONSUMER_KEY=actual_key_from_pesapal
PESAPAL_CONSUMER_SECRET=actual_secret_from_pesapal
PESAPAL_IPN_ID=
PESAPAL_CURRENCY=USD
```

### Contact Pesapal Support

If credentials are definitely correct but still failing:
- Email: support@pesapal.com
- Check if your API keys are active
- Verify your account status
- Check if IP whitelist is required

## Next Steps After Success

Once IPN registration succeeds, you'll see:
```
✅ IPN registered successfully!
IPN ID: 123abc-456def-789ghi
URL: https://zanzibarbookings.com/payment/confirmation

👉 Copy the IPN ID into your .env like this:
PESAPAL_IPN_ID="123abc-456def-789ghi"
```

Then:
1. Copy the IPN ID
2. Add to .env: `PESAPAL_IPN_ID=paste_here`
3. Clear cache: `php artisan config:clear`
4. Test payment flow

## Quick Debug Command

Run this to see what's being sent:

```bash
php artisan tinker
```

```php
Log::info('Pesapal Config Check', [
    'consumer_key' => config('pesapal.consumer_key'),
    'has_secret' => !empty(config('pesapal.consumer_secret')),
    'environment' => config('pesapal.environment'),
    'callback_url' => route('payment.success'),
    'notification_url' => route('payment.confirmation'),
]);
```

Then check: `tail -f storage/logs/laravel.log`

