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
</head>

<body>
    <div class="wrapper">
        @include('admin.layouts.sidebar')

        @include('admin.layouts.header')

        <div class="content-page">

            <div class="container-fluid mb-3">

                <div class="mt-3">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                </div>
                @yield('content')
            </div>

            @include('admin.layouts.footer')

        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const buttons = document.querySelectorAll('[data-loading-text]');

            buttons.forEach(function(button) {
                const form = button.closest('form');

                if (form) {
                    form.addEventListener('submit', function(e) {
                        if (form.checkValidity()) {
                            button.disabled = true;
                            button.innerHTML =
                                `<img src="{{ asset('assets/images/fast.svg') }}" alt="Loading..." class="me-2"> ` +
                                button.dataset.loadingText;
                        }
                    });
                }
            });
        });  
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.4.47/css/materialdesignicons.min.css"
        rel="stylesheet">
    <script src="{{asset('assets/js/vendors.min.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="{{asset('assets/js/pages/dashboard.js')}}"></script>

    @stack('scripts')

</body>

</html>