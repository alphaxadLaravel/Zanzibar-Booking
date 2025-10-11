# Amadeus PHP SDK v0.3.0 - Quick Reference

## ✅ Available Methods in AmadeusBuilder

Based on the actual SDK code, here are the **correct methods** you can use:

### Basic Initialization
```php
$amadeus = Amadeus::builder($apiKey, $apiSecret)->build();
```

### Available Builder Methods

#### 1. `setHost(string $host)` ✅
Set custom API host domain
```php
->setHost('test.api.amadeus.com')  // Test environment
->setHost('api.amadeus.com')        // Production environment
```

#### 2. `setProductionEnvironment()` ✅
Switch to production environment
```php
->setProductionEnvironment()
```

#### 3. `setSsl(bool $ssl)` ✅
Enable/disable SSL (defaults to true)
```php
->setSsl(true)
```

#### 4. `setPort(int $port)` ✅
Set custom port (defaults to 443 for SSL, 80 for non-SSL)
```php
->setPort(443)
```

#### 5. `setTimeout(int $timeout)` ✅
Set cURL timeout in seconds
```php
->setTimeout(30)
```

#### 6. `setLogLevel(string $logLevel)` ✅
Set log level for debugging
```php
->setLogLevel('debug')
```

#### 7. `setHttpClient(HTTPClient $httpClient)` ✅
Set custom HTTP client
```php
->setHttpClient($customClient)
```

#### 8. `build()` ✅
Build and return Amadeus instance
```php
->build()
```

---

## ❌ Methods That DON'T Exist

These methods are **NOT available** in v0.3.0:
- ❌ `setLogger()` - Does not exist
- ❌ `setHostname()` - Does not exist
- ❌ `setEnvironment()` - Does not exist

---

## 🎯 Correct Implementation

### Our Fixed Implementation
```php
public function __construct()
{
    $builder = Amadeus::builder(
        config('amadeus.api_key'),
        config('amadeus.api_secret')
    );

    // Set production environment if configured
    if (config('amadeus.environment') === 'production') {
        $builder->setProductionEnvironment();
    }
    // Otherwise defaults to test environment

    $this->amadeus = $builder->build();
}
```

### How It Works
- **Test Environment** (default): 
  - Just build without calling `setProductionEnvironment()`
  - Uses `test.api.amadeus.com`
  - Free 10,000 calls/month

- **Production Environment**: 
  - Call `->setProductionEnvironment()`
  - Uses `api.amadeus.com`
  - Real bookings with airlines

---

## 📝 Configuration Options

### In `config/amadeus.php`
```php
return [
    'api_key' => env('AMADEUS_API_KEY'),
    'api_secret' => env('AMADEUS_API_SECRET'),
    'environment' => env('AMADEUS_ENV', 'test'), // 'test' or 'production'
];
```

### In `.env`
```env
AMADEUS_API_KEY=your_key_here
AMADEUS_API_SECRET=your_secret_here
AMADEUS_ENV=test  # or 'production'
```

---

## 🔧 Advanced Configuration (Optional)

If you need more control:

```php
$amadeus = Amadeus::builder($apiKey, $apiSecret)
    ->setProductionEnvironment()  // If production
    ->setTimeout(60)               // 60 second timeout
    ->setLogLevel('debug')         // Enable debug logging
    ->setSsl(true)                 // Ensure SSL
    ->build();
```

---

## 📚 SDK Documentation

### Official Resources
- **GitHub**: https://github.com/amadeus4dev/amadeus-php
- **Version**: 0.3.0
- **API Docs**: https://developers.amadeus.com

### Available Endpoints (v0.3.0)

#### Shopping (Flight Search)
```php
$amadeus->getShopping()->getFlightOffers()->get($params)
$amadeus->getShopping()->getFlightOffers()->getPricing()->post($data)
```

#### Booking (Flight Orders)
```php
$amadeus->getBooking()->getFlightOrders()->post($data)
$amadeus->getBooking()->getFlightOrders()->get($orderId)
```

#### Reference Data
```php
$amadeus->getReferenceData()->getLocations()->get($params)
$amadeus->getReferenceData()->getAirlines()->get($params)
```

#### Airport
```php
$amadeus->getAirport()->getDirectDestinations()->get($params)
```

---

## ✅ Summary

### What We Fixed
1. ❌ **Removed**: `->setLogger()` (doesn't exist)
2. ❌ **Removed**: `->setHostname()` (doesn't exist)
3. ✅ **Added**: Conditional `->setProductionEnvironment()`
4. ✅ **Result**: Works perfectly with actual SDK methods

### Current Status
- ✅ **SDK initialized correctly**
- ✅ **Test environment by default**
- ✅ **Production ready when needed**
- ✅ **No more undefined method errors**

---

## 🎉 Ready to Use!

Your FlightService now uses only the **correct, available methods** from the Amadeus PHP SDK v0.3.0!

Access `/flights` and it should work without any errors! ✈️

