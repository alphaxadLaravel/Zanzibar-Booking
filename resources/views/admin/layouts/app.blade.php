<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{asset('logo.png')}}">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
    <link href="{{asset('assets/css/vendors.min.css')}}" rel="stylesheet" type="text/css">
    <script src="{{asset('assets/js/config.js')}}"></script>
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css">
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        @include('admin.layouts.sidebar')

        @include('admin.layouts.header')

        <div class="content-page">

            <div class="container-fluid mb-3">
                @yield('content')
            </div>

            @include('admin.layouts.footer')

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form').forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    const submitter = event.submitter;
                    const button = submitter && submitter.matches('[data-loading-text]')
                        ? submitter
                        : form.querySelector('[type="submit"][data-loading-text]');

                    if (!button || button.disabled) {
                        return;
                    }

                    if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                        return;
                    }

                    const loadingText = button.dataset.loadingText;
                    if (!loadingText) {
                        return;
                    }

                    form.querySelectorAll('[type="submit"]').forEach(function (btn) {
                        btn.disabled = true;
                    });

                    button.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' +
                        loadingText;
                });
            });
        });

        // Logout function
        function handleLogout() {
            if (confirm('Are you sure you want to logout?')) {
                // Redirect to logout route
                window.location.href = '{{ route("logout") }}';
            }
        }
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css"
        rel="stylesheet">
    <script src="{{asset('assets/js/vendors.min.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="{{asset('assets/js/pages/dashboard.js')}}"></script>

    @stack('scripts')

</body>

</html>