# Pesapal .env Configuration Fix

## Issues Found in Your Current .env

1. **Quotes around values** - Laravel will include quotes as part of the value
2. **Duplicate PESAPAL_IPN entries** - Only one should exist
3. **PESAPAL_LIVE=true** - This is not a valid config key
4. **PESAPAL_ENV has quotes** - Should not have quotes

## Corrected .env Configuration

Replace your Pesapal settings with this:

```env
# Pesapal API Credentials (NO QUOTES!)
PESAPAL_CONSUMER_KEY=K51LW7GtLRtIDz+y54CbAYoairagQAin
PESAPAL_CONSUMER_SECRET=NijwAFWOSRlGzxn2BuCQdrlhqm0=

# Pesapal Environment (NO QUOTES!)
PESAPAL_ENV=live

# Currency
PESAPAL_CURRENCY=USD

# Callback Route
PESAPAL_CALLBACK_ROUTE=payment.success

# IPN ID (if you have one from Pesapal dashboard)
# PESAPAL_IPN_ID=your_ipn_id_here
```

## Important Notes

1. **NO QUOTES** - Remove all quotes from values
   - ❌ `PESAPAL_CONSUMER_KEY="K51LW7GtLRtIDz+y54CbAYoairagQAin"`
   - ✅ `PESAPAL_CONSUMER_KEY=K51LW7GtLRtIDz+y54CbAYoairagQAin`

2. **Remove duplicate entries** - You have `PESAPAL_IPN` twice, remove both (they're not used)

3. **Remove PESAPAL_LIVE** - This is not a valid config key. Use `PESAPAL_ENV=live` instead

4. **IPN_ID is optional** - Only add if you've registered an IPN in Pesapal dashboard

## Steps to Fix

1. Open your `.env` file
2. Find all Pesapal-related lines
3. Replace with the corrected version above (NO QUOTES!)
4. Save the file
5. Clear cache:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```
6. Test the payment again

## Verification

After fixing, the config should show:
- `consumer_key`: Your key without quotes
- `consumer_secret`: Your secret without quotes  
- `environment`: `live` (not `'live'` with quotes)

