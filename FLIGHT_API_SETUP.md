# Flight API Integration with Amadeus

## Overview
This project integrates **Amadeus Flight API** for complete flight search and booking functionality.

---

## Why Amadeus API?

✅ **Free Tier**: 10,000 API calls/month in test environment
✅ **Comprehensive**: Search, Book, Manage flights
✅ **African Coverage**: Includes Air Tanzania, Precision Air, etc.
✅ **Full Booking**: Complete booking flow with payment
✅ **Well Documented**: Excellent PHP/Laravel support

---

## Setup Instructions

### 1. Register for Amadeus API

1. Go to: https://developers.amadeus.com/register
2. Create a free account
3. Create a new app in the dashboard
4. Get your **API Key** and **API Secret**
5. Note: Test environment has 10,000 free calls/month

### 2. Install Amadeus PHP SDK

```bash
composer require amadeus4dev/amadeus-php
```

### 3. Configure Environment Variables

Add to your `.env` file:

```env
# Amadeus API Configuration
AMADEUS_API_KEY=your_api_key_here
AMADEUS_API_SECRET=your_api_secret_here
AMADEUS_ENV=test  # Use 'production' for live data
```

### 4. Create Config File

Already created at: `config/amadeus.php`

### 5. Run Migrations

```bash
php artisan migrate
```

This creates:
- `flight_searches` - Store user search history
- `flight_bookings` - Store booking data
- `flight_passengers` - Store passenger information

---

## Available API Endpoints

### Flight Search
- **Endpoint**: Flight Offers Search
- **Features**: 
  - Search one-way, round-trip, multi-city
  - Filter by price, duration, airlines
  - Real-time availability
  
### Flight Booking
- **Endpoint**: Flight Create Orders
- **Features**:
  - Book flights with passenger details
  - Seat selection
  - Baggage options
  - Payment integration

### Flight Price
- **Endpoint**: Flight Price Analysis
- **Features**:
  - Price history
  - Price predictions
  - Best time to book

---

## Tanzania Flight Routes Supported

### From Zanzibar (ZNZ):
- ✈️ Dar es Salaam (DAR) - Domestic
- ✈️ Nairobi (NBO) - Kenya
- ✈️ Mombasa (MBA) - Kenya
- ✈️ Dubai (DXB) - UAE
- ✈️ Addis Ababa (ADD) - Ethiopia

### Airlines Operating:
- Air Tanzania (TC)
- Precision Air (PW)
- Ethiopian Airlines (ET)
- Kenya Airways (KQ)
- Emirates (EK)
- And many more...

---

## Usage in Your Application

### Search Flights
```php
$flightService = new FlightService();

$results = $flightService->searchFlights([
    'origin' => 'ZNZ',
    'destination' => 'DAR',
    'departureDate' => '2024-12-20',
    'adults' => 2
]);
```

### Book Flight
```php
$booking = $flightService->bookFlight([
    'flight_offer' => $selectedOffer,
    'passengers' => [
        [
            'firstName' => 'John',
            'lastName' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'email' => 'john@example.com',
            'phone' => '+255123456789'
        ]
    ],
    'payment' => [
        'method' => 'pesapal',
        'amount' => 450.00
    ]
]);
```

---

## Integration with Existing Pesapal

The flight booking system integrates seamlessly with your existing Pesapal payment gateway:

1. User searches flights
2. User selects flight and enters passenger details
3. System creates booking (pending payment)
4. Redirects to Pesapal for payment
5. After successful payment, confirms booking with Amadeus
6. Sends confirmation email with ticket

---

## Testing

### Test Environment
- Uses Amadeus test data
- No real bookings made
- Free 10,000 calls/month
- Perfect for development

### Production
- Switch to production credentials
- Real bookings with real airlines
- Charges per API call
- Get pricing from Amadeus

---

## API Call Limits

### Free Tier (Test):
- 10,000 calls/month
- Rate limit: 10 calls/second

### Production:
- Pay-as-you-go
- Volume discounts available
- Contact Amadeus for pricing

---

## Next Steps

1. ✅ Register at https://developers.amadeus.com
2. ✅ Get API credentials
3. ✅ Add credentials to .env
4. ✅ Run migrations
5. ✅ Test flight search
6. ✅ Test booking flow
7. ✅ Integrate with Pesapal
8. ✅ Go live!

---

## Support & Resources

- **Amadeus Docs**: https://developers.amadeus.com/self-service/category/flights
- **PHP SDK Docs**: https://github.com/amadeus4dev/amadeus-php
- **API Reference**: https://developers.amadeus.com/self-service/apis-docs
- **Community**: https://developers.amadeus.com/support

---

## Example Implementations

Check the following files for complete implementation:
- `app/Services/FlightService.php` - API integration
- `app/Http/Controllers/FlightController.php` - Controller logic
- `resources/views/website/pages/flights.blade.php` - Frontend
- `routes/web.php` - API routes

---

**Happy Flying! ✈️**

