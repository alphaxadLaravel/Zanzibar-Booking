<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard | {{env('APP_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{env('APP_DESCRIPTION')}}">

    <link rel="shortcut icon" href="{{asset('logo.png')}}">
    <link href="{{asset('assets/css/vendors.min.css')}}" rel="stylesheet" type="text/css">
    <script src="{{asset('assets/js/config.js')}}"></script>
    <link href="{{asset('assets/css/app.min.css')}}" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="wrapper">
        @include('admin.layouts.sidebar')

        @include('admin.layouts.header')

        <div class="content-page">

            <div class="container-fluid">
                @yield('content')
            </div>

            @include('admin.layouts.footer')

        </div>
    </div>

    <script src="{{asset('assets/js/vendors.min.js')}}"></script>
    <script src="{{asset('assets/js/app.js')}}"></script>
    <script src="{{asset('assets/js/pages/dashboard.js')}}"></script>

</body>
</html>