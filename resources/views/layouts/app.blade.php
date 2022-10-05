<!DOCTYPE html>
<html lang="en">
<head>
    <head>
        <meta charset="UTF-8">
        @yield('title')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script src="{{ asset('js/app.js') }}"></script>
        <script src="{{ asset('js/generales.js') }}"></script>
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/main.min.css') }}" rel="stylesheet">
        <link rel="stylesheet" media="screen, print" href="{{ asset('css/sweetalert2.bundle.css') }}">
        <link rel="stylesheet" media="screen, print" href="{{ asset('css/modal.css') }}">
        <script src="{{ asset('js/sweetalert2.bundle.js') }}"></script>
        <script src="{{ asset('js/modal-loading.js') }}"></script>
        <script src="{{ asset('js/main.min.js') }}"></script>
        <script src="{{ asset('js/locales-all.min.js') }}"></script>
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <!-- Boxicons CDN Link -->
        <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
</head>
<body>
    <div class="container-fluid p-0">
        <div class="h-100 w-100 d-flex overflow-auto">
            @yield('content')
        </div>
    </div>
</body>
</html>
@yield('javascript')
