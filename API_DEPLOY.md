# Mobile API deploy checklist (Hostinger)

The app calls: `https://www.zanzibarbookings.com/api/v1/...`

## 1. Upload these Laravel files (latest)

- `bootstrap/app.php` (must register `api: routes/api.php`)
- `routes/api.php`
- `app/Http/Controllers/Api/` (entire folder)
- `app/Http/Resources/` (`DealResource.php`, `RoomResource.php`, …)
- `app/Services/FlightService.php` (featured flights helper)
- `app/Support/HashidsHelper.php`
- `app/Models/User.php` (with `HasApiTokens`)
- `config/cors.php`, `config/sanctum.php`, `config/flights.php`
- Sanctum migration under `database/migrations/`

## 2. On the server (SSH or Hostinger terminal)

```bash
cd ~/domains/zanzibarbookings.com/public_html   # or your Laravel root
composer require laravel/sanctum --no-interaction
composer dump-autoload -o
php artisan migrate --force
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan route:list --path=api/v1
```

You must see routes like `api/v1/home`, `api/v1/ping`, `api/v1/flights/featured`, `api/v1/flights/airports`.

## 3. Verify in a browser / curl

1. `https://www.zanzibarbookings.com/api/v1/ping` → `{"ok":true,...}`
2. `https://www.zanzibarbookings.com/api/v1/home` → JSON with `featured`, `modules`, and `hero.video` / `hero.poster`
3. `https://www.zanzibarbookings.com/api/v1/deals/{id}` → photos, rooms (+images), features, includes/excludes, policies, reviews, **`nearby_deals`** (plain text, no HTML)
4. `https://www.zanzibarbookings.com/api/v1/flights/airports` → airport dropdown options
5. `https://www.zanzibarbookings.com/api/v1/flights/featured` → popular routes (may take a few seconds)
6. `https://www.zanzibarbookings.com/api/v1/blogs` and `/blogs/{id}` → blog list + article (also `blogs` on `/home`)
7. `https://www.zanzibarbookings.com/api/v1/pages` and `/pages/{slug}` → About, Commitment, Terms, Privacy, Partner (plain text)

If `ping` works but another route fails, check `storage/logs/laravel.log`.

## 4. Common Hostinger causes of empty 500

- New PHP classes uploaded but **`composer dump-autoload` not run**
- `bootstrap/app.php` not updated (API routes never registered)
- Sanctum not installed / `personal_access_tokens` table missing (affects auth; public home should still work)
- `APP_DEBUG=false` hides the error body — always check `storage/logs/laravel.log`
- Document root must be Laravel **`public/`** (with `.htaccess` Authorization pass-through)

## 5. After API is healthy

Rebuild / rerun the Flutter app. It already points at:

`https://www.zanzibarbookings.com/api/v1`
