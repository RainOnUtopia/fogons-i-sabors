<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fogons i Sabors</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>

<body class="bg-light d-flex flex-column min-vh-100">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="#">Fogons i Sabors</a>
            <div class="ms-auto">
                @if (Route::has('login'))
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
                        @else
                            <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-secondary me-2">{{ __('auth.login_title') }}</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary">{{ __('auth.register_title') }}</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <main class="flex-grow-1 d-flex align-items-center hero-section text-center">
        <div class="container py-5">
            <h1 class="display-4 fw-bold text-dark mb-3">Benvingut a Fogons i Sabors</h1>
            <p class="lead text-muted mb-5">El teu receptari digital de confiança. Comparteix, descobreix i cuina amb
                nosaltres.</p>
            @guest
                <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 gap-3">Comença ara!</a>
            @endguest
            @if(!Auth::guest())
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
                @else
                    <a href="{{ url('/dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
                @endif
            @endif
        </div>
    </main>

    <footer class="bg-white text-center py-4 mt-auto border-top">
        <div class="container">
            <p class="text-muted mb-0">&copy; {{ date('Y') }} Fogons i Sabors. Tots els drets reservats.</p>
        </div>
    </footer>
</body>

</html>