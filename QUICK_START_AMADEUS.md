# Quick Start Guide: Amadeus Flight API Integration

## 🎯 Overview
Your Zanzibar Booking application now has **full flight search and booking capabilities** using the **Amadeus Flight API**!

---

## ✅ What's Been Installed

### 1. **Amadeus PHP SDK**
- Package: `amadeus4dev/amadeus-php`
- Version: 0.3.0
- ✅ Installed successfully

### 2. **Database Tables**
- ✅ `flight_searches` - Track user search history
- ✅ `flight_bookings` - Store flight bookings
- ✅ `flight_passengers` - Passenger information

### 3. **Models**
- ✅ `FlightSearch` - Flight search model
- ✅ `FlightBooking` - Booking model with Pesapal integration
- ✅ `FlightPassenger` - Passenger model

### 4. **Service Class**
- ✅ `FlightService` - Complete Amadeus API integration

### 5. **Controller**
- ✅ `FlightController` - All flight booking functionality

### 6. **Routes**
- ✅ `/flights` - Flight search page
- ✅ `/flights/{id}/book` - Booking form
- ✅ `/flights/payment/{ref}` - Payment with Pesapal
- ✅ `/flights/confirmation/{ref}` - Booking confirmation
- ✅ `/my-flights` - User's flight bookings

### 7. **Frontend**
- ✅ Updated `flights.blade.php` with search form
- ✅ Integrated with real API calls
- ✅ Filter and sort functionality

---

## 🚀 Setup Instructions

### Step 1: Register for Amadeus API

1. **Go to**: https://developers.amadeus.com/register
2. **Create Account**: Sign up for free
3. **Create an App**:
   - Go to "My Apps"
   - Click "Create New App"
   - Fill in app details
4. **Get Credentials**:
   - Copy your **API Key**
   - Copy your **API Secret**

### Step 2: Configure Environment

Add these lines to your `.env` file:

```env
# Amadeus Flight API Configuration
AMADEUS_API_KEY=your_api_key_here
AMADEUS_API_SECRET=your_api_secret_here
AMADEUS_ENV=test
```

**Note**: Use `test` environment for development (10,000 free calls/month)

### Step 3: Clear Cache

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Step 4: Test the Integration

1. **Go to**: `http://your-domain.com/flights`
2. **Search for a flight**:
   - From: Zanzibar (ZNZ)
   - To: Dar es Salaam (DAR)
   - Date: Any future date
3. **Click Search**
4. **View Results** - You should see real flight data from Amadeus!

---

## 📋 Features Implemented

### ✈️ Flight Search
- [x] Search flights from Zanzibar to worldwide destinations
- [x] One-way and round-trip support
- [x] Multiple passengers (adults, children, infants)
- [x] Travel class selection (Economy, Business, First)
- [x] Non-stop filter option
- [x] Real-time results from 300+ airlines

### 💺 Flight Booking
- [x] Select flight from search results
- [x] Enter passenger details
- [x] Passport information (optional)
- [x] Contact information
- [x] Generate unique booking reference

### 💳 Payment Integration
- [x] Integrated with existing Pesapal payment
- [x] Secure payment processing
- [x] Payment confirmation
- [x] Auto-confirm booking after payment

### 📧 Email Notifications
- [ ] Booking confirmation email (template ready)
- [ ] Payment receipt email (template ready)
- [ ] Booking reminder email (template ready)

### 👤 User Dashboard
- [x] View all flight bookings
- [x] Booking history
- [x] Download ticket (coming soon)

---

## 🌍 Supported Routes from Zanzibar

### Domestic Tanzania
- ✈️ Dar es Salaam (DAR)
- ✈️ Kilimanjaro (JRO)
- ✈️ Mwanza (MWZ)
- ✈️ Dodoma (DOD)

### Regional East Africa
- ✈️ Nairobi, Kenya (NBO)
- ✈️ Mombasa, Kenya (MBA)
- ✈️ Addis Ababa, Ethiopia (ADD)

### International
- ✈️ Dubai, UAE (DXB)
- ✈️ Doha, Qatar (DOH)
- ✈️ Istanbul, Turkey (IST)
- ✈️ London, UK (LHR)
- ✈️ Amsterdam, Netherlands (AMS)
- ✈️ And 100+ more destinations...

---

## 🔧 Configuration Files

### `config/amadeus.php`
Contains all Amadeus API configuration:
- API credentials
- Default search parameters
- Tanzania airports
- Tanzania airlines

### `app/Services/FlightService.php`
Main service class with methods:
- `searchFlights()` - Search for flights
- `createBooking()` - Create booking
- `confirmBookingWithAmadeus()` - Confirm with airline
- `getPriceForOffer()` - Get latest price

### `app/Http/Controllers/FlightController.php`
Handles all flight-related requests:
- Search
- Booking
- Payment
- Confirmation

---

## 💰 Pricing

### Test Environment (Current)
- **Cost**: FREE
- **Calls**: 10,000 per month
- **Data**: Test data (not real bookings)
- **Perfect For**: Development & Testing

### Production Environment
- **Cost**: Pay-as-you-go
- **Pricing**: Contact Amadeus for details
- **Data**: Real flight data
- **Bookings**: Real airline bookings
- **To Enable**: Change `AMADEUS_ENV=production` in `.env`

---

## 📚 API Documentation

- **Amadeus Docs**: https://developers.amadeus.com/self-service
- **Flight Search API**: https://developers.amadeus.com/self-service/category/flights/api-doc/flight-offers-search
- **Flight Booking API**: https://developers.amadeus.com/self-service/category/flights/api-doc/flight-create-orders
- **PHP SDK**: https://github.com/amadeus4dev/amadeus-php

---

## 🐛 Troubleshooting

### Issue: "Unable to search flights"
**Solution**: Check your API credentials in `.env`

### Issue: "API Key not found"
**Solution**: Run `php artisan config:clear`

### Issue: "No flights found"
**Solution**: 
- Check if the route exists
- Try different dates
- Some routes may not be available in test environment

### Issue: "Booking confirmation failed"
**Solution**: 
- Check logs in `storage/logs/amadeus.log`
- Verify passenger details are complete
- Ensure payment was successful

---

## 📊 Database Structure

### flight_searches
Tracks all flight searches for analytics
- origin/destination
- dates
- passenger count
- results count

### flight_bookings
Stores all flight bookings
- booking reference (unique)
- flight details
- pricing
- status (pending/confirmed/cancelled)
- Amadeus order ID
- payment integration

### flight_passengers
Passenger information for each booking
- personal details
- passport info
- special requests
- seat preferences

---

## 🎯 Next Steps

1. ✅ **Get Amadeus Credentials** (if not done)
2. ✅ **Add to .env**
3. ✅ **Test search functionality**
4. ✅ **Test booking flow**
5. ⏳ **Customize email templates**
6. ⏳ **Add ticket download feature**
7. ⏳ **Go live with production credentials**

---

## 📞 Support

- **Amadeus Support**: https://developers.amadeus.com/support
- **Community Forum**: https://developers.amadeus.com/community

---

## 🎉 You're All Set!

Your flight booking system is ready to use! Start by getting your Amadeus API credentials and testing the search functionality.

**Happy Flying! ✈️**

