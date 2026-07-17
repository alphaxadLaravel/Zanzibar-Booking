<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Zanzibar Bookings</title>
    <link rel="shortcut icon" type="image/png" href="{{ asset('logo.png') }}" />
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('html/assets/vendor/bootstrap-4.0.0/dist/css/bootstrap.min.css') }}" />
    <style>
        :root {
            --primary: hsl(175, 67%, 33%);
            --primary-hover: hsl(175, 67%, 40%);
            --text-muted: #6c757d;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: linear-gradient(160deg, #f8fffe 0%, #eef7f6 45%, #ffffff 100%);
            color: #212529;
        }

        .error-header {
            padding: 1.25rem 0;
            background: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
        }

        .error-header .brand-link {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .error-header .brand-logo {
            height: 42px;
            width: auto;
        }

        .error-main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }

        .error-card {
            width: 100%;
            max-width: 640px;
            text-align: center;
            background: #fff;
            border-radius: 16px;
            padding: 3rem 2rem;
            box-shadow: 0 12px 40px rgba(23, 128, 117, 0.08);
            border: 1px solid rgba(23, 128, 117, 0.08);
        }

        .error-icon {
            width: 88px;
            height: 88px;
            margin: 0 auto 1.5rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            background: rgba(23, 128, 117, 0.1);
            color: var(--primary);
        }

        .error-code {
            font-size: 4rem;
            font-weight: 800;
            line-height: 1;
            color: var(--primary);
            margin-bottom: 0.75rem;
            letter-spacing: -2px;
        }

        .error-title {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .error-message {
            color: var(--text-muted);
            font-size: 1.05rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .error-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            justify-content: center;
        }

        .btn-primary-custom {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: background 0.2s ease;
        }

        .btn-primary-custom:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #fff;
            text-decoration: none;
        }

        .btn-outline-custom {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            transition: all 0.2s ease;
        }

        .btn-outline-custom:hover {
            background: var(--primary);
            color: #fff;
            text-decoration: none;
        }

        .error-footer {
            padding: 1.5rem;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            background: #fff;
        }

        .error-debug {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #fff5f5;
            border: 1px solid #f5c6cb;
            border-radius: 8px;
            text-align: left;
            font-size: 0.85rem;
            word-break: break-word;
            color: #721c24;
        }

        @media (max-width: 576px) {
            .error-card {
                padding: 2rem 1.25rem;
            }

            .error-code {
                font-size: 3rem;
            }

            .error-title {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <header class="error-header">
        <div class="container">
            <a href="/" class="brand-link">
                <img src="{{ asset('logo.png') }}" alt="Zanzibar Bookings" class="brand-logo" />
            </a>
        </div>
    </header>

    <main class="error-main">
        <div class="error-card">
            <div class="error-icon">
                <i class="mdi @yield('icon', 'mdi-alert-circle-outline')"></i>
            </div>
            <div class="error-code">@yield('code')</div>
            <h1 class="error-title">@yield('title')</h1>
            <p class="error-message">@yield('message')</p>

            <div class="error-actions">
                @yield('actions')
            </div>

            @hasSection('debug')
                @yield('debug')
            @elseif(config('app.debug') && isset($exception))
                <div class="error-debug">
                    <strong>Debug:</strong> {{ $exception->getMessage() }}
                </div>
            @endif
        </div>
    </main>

    <footer class="error-footer">
        &copy; {{ date('Y') }} Zanzibar Bookings. All rights reserved.
    </footer>
</body>
</html>
