# Mobile API deploy checklist (Hostinger)

The app calls: `https://www.zanzibarbookings.com/api/v1/...`

Right now production returns **HTTP 500** for `/api/v1/home` (empty body). Fix server first, then the app will load data.

## 1. Upload these Laravel files

- `bootstrap/app.php` (must register `api: routes/api.php`)
- `routes/api.php`
- `app/Http/Controllers/Api/` (entire folder)
- `app/Http/Resources/` (entire folder)
- `app/Support/HashidsHelper.php`
- `app/Models/User.php` (with `HasApiTokens`)
- `config/cors.php`
- `config/sanctum.php`
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

You must see routes like `api/v1/home`, `api/v1/ping`.

## 3. Verify in a browser / curl

1. `https://www.zanzibarbookings.com/api/v1/ping` → `{"ok":true,...}`
2. `https://www.zanzibarbookings.com/api/v1/home` → JSON with `featured` / `modules`

If `ping` works but `home` fails, check `storage/logs/laravel.log` for the exact error.

## 4. Common Hostinger causes of empty 500

- New PHP classes uploaded but **`composer dump-autoload` not run**
- `bootstrap/app.php` not updated (API routes never registered)
- Sanctum not installed / `personal_access_tokens` table missing (affects auth; public home should still work)
- `APP_DEBUG=false` hides the error body — always check `storage/logs/laravel.log`
- Document root must be Laravel **`public/`** (with `.htaccess` Authorization pass-through)

## 5. After API is healthy

Rebuild / rerun the Flutter app. It already points at:

`https://www.zanzibarbookings.com/api/v1`
