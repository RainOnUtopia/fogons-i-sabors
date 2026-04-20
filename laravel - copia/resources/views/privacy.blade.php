<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Política de Privacitat — Fogons i Sabors</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-light body-reset d-flex flex-column min-vh-100 page-grid-bg">
    {{-- Navegació principal compartida --}}@include('layouts.navigation')

    {{-- HERO (SECCIÓ PRINCIPAL) --}}
    <section class="py-5 text-center">
        <div class="container">
            <span class="home-hero-tag d-inline-flex align-items-center gap-2 mb-2">
                <i class="bi bi-shield-lock-fill"></i>
                PRIVACITAT
            </span>
            <h1 class="home-section-title mb-3">Política de Privacitat</h1>
            <p class="home-hero-desc" style="max-width: 700px; margin: 0 auto;">
                La teva privacitat és important per a nosaltres. Aquí t'expliquem com gestionem les teves dades.
            </p>
        </div>
    </section>

    {{-- CONTINGUT DE LA POLÍTICA --}}
    <section class="container mb-5">
        <div class="card-ui p-4 p-md-5 mx-auto" style="max-width: 900px;">

            {{-- 1. INTRODUCCIÓ --}}
            <div class="mb-5">
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                    <i class="bi bi-info-circle text-primary-ui me-2"></i>1. Introducció
                </h2>
                <p style="color: var(--text-secondary); line-height: 1.8;">
                    Aquesta política de privacitat descriu com Fogons i Sabors recull, utilitza
                    i protegeix la informació personal dels seus usuaris. En utilitzar la nostra
                    plataforma, acceptes les pràctiques descrites en aquest document.
                </p>
            </div>

            {{-- 2. DADES QUE RECOPILEM --}}
            <div class="mb-5">
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                    <i class="bi bi-database text-primary-ui me-2"></i>2. Dades que recopilem
                </h2>
                <p style="color: var(--text-secondary); line-height: 1.8;">
                    Recopilem les dades que ens proporciones voluntàriament en registrar-te,
                    com el teu nom, adreça de correu electrònic i avatar de perfil. També
                    recopilem informació sobre les receptes que publiques, les valoracions
                    que fas i els comentaris que deixes a la plataforma.
                </p>
            </div>

            {{-- 3. COM UTILITZEM LES TEVES DADES --}}
            <div class="mb-5">
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                    <i class="bi bi-gear text-primary-ui me-2"></i>3. Com utilitzem les teves dades
                </h2>
                <p style="color: var(--text-secondary); line-height: 1.8; margin-bottom: 12px;">
                    Utilitzem les teves dades personals per a les finalitats següents:
                </p>
                <ul style="color: var(--text-secondary); line-height: 2; padding-left: 20px;">
                    <li>Gestionar el teu compte d'usuari i perfil públic</li>
                    <li>Permetre la publicació i gestió de receptes</li>
                    <li>Facilitar la participació en duels culinaris</li>
                    <li>Enviar-te comunicacions relacionades amb el servei</li>
                    <li>Millorar l'experiència d'usuari a la plataforma</li>
                </ul>
            </div>

            {{-- 4. COMPARTICIÓ DE DADES --}}
            <div class="mb-5">
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                    <i class="bi bi-share text-primary-ui me-2"></i>4. Compartició de dades
                </h2>
                <p style="color: var(--text-secondary); line-height: 1.8;">
                    No venem ni compartim les teves dades personals amb tercers amb finalitats
                    comercials. Només podem compartir informació en els casos següents: per complir
                    amb obligacions legals, per protegir els drets de la plataforma, o amb proveïdors
                    de serveis que ens ajuden a operar la plataforma (sota acords de confidencialitat).
                </p>
            </div>

            {{-- 5. SEGURETAT --}}
            <div class="mb-5">
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                    <i class="bi bi-lock text-primary-ui me-2"></i>5. Seguretat de les dades
                </h2>
                <p style="color: var(--text-secondary); line-height: 1.8;">
                    Implementem mesures de seguretat tècniques i organitzatives per protegir
                    les teves dades personals contra l'accés no autoritzat, la pèrdua o la
                    destrucció. Les contrasenyes s'emmagatzemen de forma xifrada i les connexions
                    es realitzen mitjançant protocols segurs.
                </p>
            </div>

            {{-- 6. ELS TEUS DRETS --}}
            <div class="mb-5">
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                    <i class="bi bi-person-check text-primary-ui me-2"></i>6. Els teus drets
                </h2>
                <p style="color: var(--text-secondary); line-height: 1.8; margin-bottom: 12px;">
                    Com a usuari, tens dret a:
                </p>
                <ul style="color: var(--text-secondary); line-height: 2; padding-left: 20px;">
                    <li>Accedir a les teves dades personals</li>
                    <li>Rectificar dades incorrectes o incompletes</li>
                    <li>Sol·licitar l'eliminació del teu compte i dades</li>
                    <li>Oposar-te al tractament de les teves dades</li>
                    <li>Sol·licitar la portabilitat de les teves dades</li>
                </ul>
            </div>

            {{-- 7. COOKIES --}}
            <div class="mb-5">
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                    <i class="bi bi-cookie text-primary-ui me-2"></i>7. Cookies
                </h2>
                <p style="color: var(--text-secondary); line-height: 1.8;">
                    Utilitzem cookies essencials per al funcionament de la plataforma, com les
                    cookies de sessió i autenticació. Aquestes cookies són necessàries per
                    garantir una experiència segura i funcional. No utilitzem cookies de seguiment
                    publicitari de tercers.
                </p>
            </div>

            {{-- 8. CANVIS A AQUESTA POLÍTICA --}}
            <div class="mb-5">
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                    <i class="bi bi-pencil-square text-primary-ui me-2"></i>8. Canvis a aquesta política
                </h2>
                <p style="color: var(--text-secondary); line-height: 1.8;">
                    Ens reservem el dret de modificar aquesta política de privacitat en qualsevol
                    moment. Qualsevol canvi serà publicat en aquesta pàgina amb la data
                    d'actualització corresponent. Recomanem revisar periòdicament aquesta política.
                </p>
            </div>

            {{-- 9. CONTACTE --}}
            <div>
                <h2 style="font-family: var(--font-serif); font-size: 1.5rem; font-weight: 700; color: var(--text-main); margin-bottom: 12px;">
                    <i class="bi bi-envelope-open text-primary-ui me-2"></i>9. Contacte
                </h2>
                <p style="color: var(--text-secondary); line-height: 1.8; margin-bottom: 0;">
                    Si tens qualsevol dubte sobre aquesta política de privacitat o sobre el
                    tractament de les teves dades, pots contactar amb nosaltres a través de la
                    nostra <a href="{{ route('contact') }}" class="footer-link">pàgina de contacte</a>.
                </p>
            </div>

        </div>
    </section>

    {{-- DATA D'ACTUALITZACIÓ --}}
    <section class="container mb-5 text-center">
        <p style="color: var(--text-muted); font-size: 0.9rem;">
            <i class="bi bi-calendar3 me-1"></i>
            Última actualització: {{ date('d/m/Y') }}
        </p>
    </section>

    {{-- FOOTER --}}
    <footer class="bg-white border-top mt-auto py-4">
        <div class="container d-flex flex-column flex-md-row align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center gap-2">
                <img src="{{ asset('img/logo.png') }}" alt="Logo FogonsiSabors" class="footer-logo">
                <span class="fw-bold footer-brand-text">Fogons <span class="navbar-brand-i">i</span> <span
                        class="home-hero-accent">Sabors</span></span>
            </div>
            <div class="text-secondary footer-copy">&copy; {{ date('Y') }} Fogons i Sabors. Tots els drets reservats.
            </div>
            <div class="d-flex gap-3">
                <a href="{{ route('about') }}" class="footer-link">Sobre nosaltres</a>
                <a href="{{ route('contact') }}" class="footer-link">Contacte</a>
                <a href="{{ route('privacy') }}" class="footer-link">Privacitat</a>
            </div>
        </div>
    </footer>
</body>

</html>
