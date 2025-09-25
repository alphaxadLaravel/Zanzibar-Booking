# Zanzibar Booking System Implementation

## Overview
This document outlines the complete booking system implementation with Pesapal payment integration for the Zanzibar Booking platform.

## Features Implemented

### 1. Models
- **Booking Model** (`app/Models/Booking.php`)
  - Automatic booking code generation (BK + 8 random characters)
  - Price calculation based on deal type (hotel, tour, car, apartment)
  - Support for adults/children pricing for tours
  - Room-specific pricing for hotels/apartments
  - Relationships with Deal, Room, User, and Payment models

- **Payment Model** (`app/Models/Payment.php`)
  - Automatic payment reference generation (PAY + 10 random characters)
  - Integration with Pesapal transaction tracking
  - Status management (pending, COMPLETED, FAILED, CANCELLED)

### 2. Controllers
- **BookingController** (`app/Http/Controllers/BookingController.php`)
  - `confirmBooking()` - Shows booking confirmation form
  - `processBooking()` - Processes booking and redirects to payment
  - `viewBooking()` - Displays booking details
  - `cancelBooking()` - Cancels pending bookings
  - Comprehensive validation based on deal type

- **PaymentController** (`app/Http/Controllers/PaymentController.php`)
  - `processPayment()` - Initiates Pesapal payment
  - `paymentSuccess()` - Handles payment success callback
  - `paymentConfirmation()` - Handles payment confirmation webhook
  - `checkPaymentStatus()` - Verifies payment status with Pesapal

### 3. Views
- **Booking Confirmation** (`resources/views/website/pages/confirm_booking.blade.php`)
  - Dynamic form based on deal type
  - Hotel/Apartment: Check-in/out dates, room selection, adults/children
  - Tour: Pickup location/time, adults/children with separate pricing
  - Car: Pickup/return locations and times, driver option

- **Pesapal Payment** (`resources/views/website/pages/pesapal.blade.php`)
  - Secure payment iframe integration
  - Booking summary display
  - Payment security information

- **Payment Success** (`resources/views/website/pages/payment_success.blade.php`)
  - Payment confirmation display
  - Booking details summary
  - Next steps information

- **Booking Details** (`resources/views/website/pages/booking_details.blade.php`)
  - Complete booking information
  - Payment history
  - Status management
  - Action buttons (pay, cancel, etc.)

### 4. Routes
- `/confirm-booking` - Booking confirmation form
- `/process-booking` - Process booking submission
- `/booking/{id}` - View booking details
- `/booking/{id}/cancel` - Cancel booking
- `/payment/{booking_id}` - Process payment
- `/payment/success` - Payment success callback
- `/payment/confirmation` - Payment confirmation webhook

### 5. Database Schema
- **bookings table**: Complete booking information with payment status
- **payments table**: Payment records with Pesapal integration
- Added `payment_status` column to bookings table

## Pricing Logic

### Hotels/Apartments
- Price = Room Price × Number of Rooms × Number of Nights
- Supports check-in/check-out date calculation

### Tours
- Price = (Adults × Adult Price) + (Children × Child Price)
- Separate pricing for adults and children

### Cars
- Basic implementation with deal base price
- Can be extended for duration-based pricing

## Integration Points

### Deal Views
- Updated hotel view to include room_id in booking links
- Tour view already has booking integration
- Car and apartment views can be similarly updated

### Pesapal Configuration
The system expects Pesapal to be configured in `config/pesapal.php` with:
- Consumer key
- Consumer secret
- Currency (defaults to USD)
- Environment (sandbox/production)

## Usage Flow

1. **Customer selects deal** → Views deal details
2. **Clicks "Book Now"** → Redirects to booking confirmation
3. **Fills booking form** → Submits booking details
4. **Booking created** → Redirects to payment page
5. **Pesapal payment** → Customer completes payment
6. **Payment success** → Shows success page
7. **Webhook confirmation** → Updates booking status
8. **Booking confirmed** → Customer receives confirmation

## Security Features

- CSRF protection on all forms
- Input validation and sanitization
- Secure payment processing via Pesapal
- Booking code and payment reference uniqueness
- Comprehensive error logging

## Error Handling

- Comprehensive try-catch blocks
- Detailed logging for debugging
- User-friendly error messages
- Graceful fallbacks for failed operations

## Next Steps

1. **Configure Pesapal** - Set up consumer key/secret
2. **Test Payment Flow** - Test with Pesapal sandbox
3. **Email Notifications** - Add booking confirmation emails
4. **Admin Dashboard** - Add booking management to admin panel
5. **Mobile Optimization** - Ensure responsive design
6. **Analytics** - Add booking tracking and reporting

## Testing

To test the booking system:

1. Create test deals (hotels, tours, cars, apartments)
2. Navigate to deal details page
3. Click "Book Now" button
4. Fill out booking form
5. Complete payment process
6. Verify booking creation and payment status

## Support

For any issues or questions regarding the booking system implementation, refer to the Laravel documentation and Pesapal API documentation.
