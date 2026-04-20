<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que verifica que l'usuari autenticat té el rol 'admin'.
 * S'aplica a totes les rutes del grup /admin.
 */
class CheckAdminRole
{
    /**
     * Gestiona una petició entrant.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Accés denegat: Aquesta secció és només per a administradors.');
        }

        return $next($request);
    }
}
