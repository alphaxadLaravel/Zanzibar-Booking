# Pesapal Callback Setup Guide

## Overview
This guide explains how to configure Pesapal callbacks for the Zanzibar Booking system.

## Callback URLs

### 1. Success Callback URL
**URL**: `https://yourdomain.com/payment/success`
**Method**: GET/POST
**Purpose**: Redirects user after successful payment
**Parameters**:
- `pesapal_transaction_tracking_id`
- `pesapal_merchant_reference`

### 2. Notification Callback URL (IPN)
**URL**: `https://yourdomain.com/payment/confirmation`
**Method**: POST
**Purpose**: Server-to-server notification for payment status updates
**Parameters**:
- `pesapal_transaction_tracking_id`
- `pesapal_merchant_reference`
- `pesapal_notification_type`

### 3. Alternative Callback URLs
- `https://yourdomain.com/payment/callback` (Alternative success callback)
- `https://yourdomain.com/payment/ipn` (Alternative IPN callback)

## Pesapal Configuration

### In your Pesapal Dashboard:
1. **Success URL**: `https://yourdomain.com/payment/success`
2. **Notification URL**: `https://yourdomain.com/payment/confirmation`

### In your Laravel config/pesapal.php:
```php
return [
    'consumer_key' => 'your_consumer_key',
    'consumer_secret' => 'your_consumer_secret',
    'currency' => 'USD',
    'environment' => 'sandbox', // or 'production'
    'callback_url' => 'https://yourdomain.com/payment/success',
    'notification_url' => 'https://yourdomain.com/payment/confirmation',
];
```

## Payment Flow

1. **User initiates payment** → Redirected to Pesapal
2. **Payment completed** → Pesapal redirects to success callback
3. **Status update** → Pesapal sends notification to IPN callback
4. **Booking confirmed** → System updates booking status

## Security Features

- **IP Logging**: All callbacks log IP addresses
- **User Agent Logging**: Tracks request sources
- **Parameter Validation**: Validates required parameters
- **Error Handling**: Comprehensive error logging
- **Duplicate Prevention**: Prevents duplicate processing

## Testing

### Test URLs:
- Success: `https://yourdomain.com/payment/success?pesapal_transaction_tracking_id=TEST123&pesapal_merchant_reference=PAY123`
- IPN: `https://yourdomain.com/payment/confirmation` (POST with parameters)

### Logs:
Check `storage/logs/laravel.log` for callback activity:
```bash
tail -f storage/logs/laravel.log | grep "Payment"
```

## Troubleshooting

### Common Issues:
1. **Callback not received**: Check URL accessibility
2. **SSL Certificate**: Ensure HTTPS is working
3. **Firewall**: Allow Pesapal IPs
4. **Logs**: Check Laravel logs for errors

### Debug Mode:
Enable detailed logging by setting `LOG_LEVEL=debug` in `.env`

## Production Checklist

- [ ] Update Pesapal dashboard with production URLs
- [ ] Test all callback URLs
- [ ] Verify SSL certificates
- [ ] Check firewall settings
- [ ] Monitor logs for errors
- [ ] Test payment flow end-to-end

## Support

For Pesapal-specific issues, contact Pesapal support.
For application issues, check Laravel logs and error messages.
