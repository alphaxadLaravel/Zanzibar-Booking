# Zanzibar-Booking

Laravel 12 booking platform for Zanzibar Bookings (Blade + Livewire website).

## Mobile API

JSON API for the Flutter app lives under `/api/v1` (Laravel Sanctum).

Key areas:
- `POST /api/v1/auth/login|register`
- `GET /api/v1/home`, `GET /api/v1/deals`
- `POST /api/v1/book/room`, `POST /api/v1/book/deal`
- `POST /api/v1/bookings/process`
- `POST /api/v1/payments/{id}/pesapal` → returns `iframe_html` for in-app WebView
- `GET|POST /api/v1/payments/mobile-callback` → deep-links back to the app
- Flights: `/api/v1/flights/search`, `/book`, `/{ref}/pay`

See sibling project `../Zanzibar-Mobile`.

## Local run

```bash
composer install
php artisan migrate
npm install
composer run dev
```
