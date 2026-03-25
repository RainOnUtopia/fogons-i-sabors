<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom" style="height: 65px;">
            <div class="container-fluid h-100 d-flex align-items-center">
                {{-- Logo + nom --}}
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo FogonsiSabors"
                        style="width: 34px; height: 34px; object-fit: cover; border-radius: 8px; border: 2px solid #dc3544;">
                    <span class="fw-bold">
                        <span style="color: #000000;">Fogons</span><span style="color: #ffbb55;">i</span><span style="color: #be3144;">Sabors</span>
                    </span>
                </a>

                {{-- Responsive toggler --}}
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
                        aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    {{-- Menu horitzontal --}}
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center gap-5 justify-content-start">
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#">Receptes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="#">Duels</a>
                        </li>
                        @auth
                            @if(Auth::user()->role === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link text-dark" href="{{ route('admin.dashboard') }}">Admin</a>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    {{-- Camp de cerca --}}
                    <form class="d-flex me-3" role="search">
                        <input class="form-control form-control-sm rounded-pill" type="search" placeholder="Cercar…" aria-label="Cercar">
                    </form>

                    {{-- Notificacions, avatar, nom, sortir --}}
                    <div class="d-flex align-items-center gap-3">
                        @auth
                            <button class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center" type="button" aria-label="Notificacions" style="width: 32px; height: 32px;">
                                <i class="bi bi-bell" style="font-size: 16px;"></i>
                            </button>

                            <div class="rounded-circle overflow-hidden" style="width: 32px; height: 32px; flex-shrink: 0;">
                                <img src="{{ asset('img/user-avatar.svg') }}" alt="Avatar User" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>

                            <span class="d-none d-md-inline text-dark fw-medium">{{ Auth::user()->name }}</span>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        @endauth

                        @guest
                            <a class="btn btn-outline-dark btn-sm" href="{{ route('login') }}">Accedir</a>
                            @if (Route::has('register'))
                                <a class="btn btn-danger btn-sm" href="{{ route('register') }}">Registrar-se</a>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

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