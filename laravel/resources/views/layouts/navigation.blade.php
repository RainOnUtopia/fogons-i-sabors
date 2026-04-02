<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom navbar-main">
    <div class="container-fluid h-100 d-flex flex-wrap align-items-center">
        {{-- LOGO I NOM DE L'APLICACIÓ --}}
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo FogonsiSabors" class="navbar-logo">
            <span class="fw-bold">
                <span class="navbar-brand-fogons">Fogons</span><span class="navbar-brand-i">i</span><span class="navbar-brand-sabors">Sabors</span>
            </span>
        </a>

        {{-- RESPONSIVE PER MÒVIL --}}
        <button class="navbar-toggler border-0 navbar-toggler-custom" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"
            aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
            {{-- MENÚ DE NAVEGACIÓ HORITZONTAL --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-center gap-5 justify-content-start">
                <li class="nav-item">
                    <a class="nav-link text-dark" href="{{ route('recipes.index') }}">Receptes</a>
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

            {{-- CAMP DE CERCA --}}
            <form class="d-flex me-3" role="search">
                <input class="form-control form-control-sm rounded-pill" type="search" placeholder="Cercar"
                    aria-label="Cercar">
            </form>

            {{-- NOTIFICACIONS, AVATAR I ACCIONS D'USUARI --}}
            <div class="d-flex align-items-center gap-3">
                @auth
                    {{-- BOTÓ DE NOTIFICACIONS --}}
                    <button class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center navbar-icon-btn"
                        type="button" aria-label="Notificacions">
                        <i class="bi bi-bell navbar-bell-icon"></i>
                    </button>

                    {{-- AVATAR I NOM DE L'USUARI AUTENTICAT --}}
                    <a href="{{ route('profile.show') }}"
                        class="user-profile-link d-flex align-items-center gap-2 text-dark fw-medium navbar-profile-reset">
                        <div class="rounded-circle overflow-hidden navbar-avatar-shell">
                            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) . '?v=' . Auth::user()->updated_at?->timestamp : asset('img/user-avatar.svg') }}" alt="Avatar User"
                                class="navbar-avatar-img">
                        </div>
                        <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                    </a>

                    {{-- BOTÓ DE TANCAR SESSIÓ --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center navbar-icon-btn"
                            title="Logout">
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