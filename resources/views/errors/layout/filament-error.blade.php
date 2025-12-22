<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} – @yield('title', 'Error')</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/filament/filament/app.css')}}"/>
    <style>
        :root{

            --primary-500: 59, 130, 246;
            --primary-600: 37, 99, 235;
            --primary-700: 29, 78, 216;

            --gray-50: 250, 250, 250;
            --gray-400: 161, 161, 170;
            --gray-700: 63, 63, 70;
            --gray-950: 9, 9, 11;
            --c-500: var(--primary-500);
        }
    </style>
</head>
<body class="h-full">
    @yield('content')

    <script src="{{ asset('js/filament/filament/app.js')}}"></script>
</body>
</html>
