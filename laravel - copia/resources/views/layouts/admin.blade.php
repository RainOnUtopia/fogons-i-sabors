<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- TITOL DE LA PAGINA - DEFINIT PER CADA VISTA ADMIN --}}
    <title>@yield('title', config('app.name', 'Laravel'))</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light body-reset">
    {{-- NAVEGACIÓ PRINCIPAL COMPARTIDA --}}
    @include('layouts.navigation')

    {{-- CONTINGUT PRINCIPAL DE LA SECCIO ADMIN --}}
    @yield('content')
</body>

</html>
