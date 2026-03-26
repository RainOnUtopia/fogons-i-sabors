<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light body-reset">{{-- Navegacio principal compartida
    --}}@include('layouts.navigation')

    @isset($header)
        <header class="bg-white shadow-sm mb-4 py-3">
            <div class="container">
                {{ $header }}
            </div>
        </header>
    @endisset

    <main class="container">
        @yield('content')
    </main>
</body>

</html>
