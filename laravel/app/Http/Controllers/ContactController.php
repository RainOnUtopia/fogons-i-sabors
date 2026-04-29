<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador per gestionar el formulari de contacte.
 * 
 * S'encarrega de rebre les dades del formulari de contacte, validar-les
 * i enviar-les per correu electrònic a través del service de contacte.
 * 
 * @package App\Http\Controllers
 */
class ContactController extends Controller
{
    /**
     * Instància del servei de contacte.
     * 
     * @var ContactService
     */
    protected ContactService $contactService;

    /**
     * Crea una nova instància del controlador.
     * 
     * @param ContactService $contactService Servei d'enviament de correus.
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Processa l'enviament del formulari de contacte.
     * 
     * Valida les dades d'entrada i crida al servei per enviar el correu.
     * 
     * @param ContactRequest $request Petició amb les dades validades.
     * @return RedirectResponse Redirecció a la pàgina anterior amb missatge d'èxit.
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        // Envia el correu a través del servei
        $this->contactService->sendContactEmail($request->validated());

        // Redirigeix amb un missatge d'èxit en català
        return back()->with('success', 'El teu missatge s\'ha enviat correctament. Ens posarem en contacte amb tu aviat.');
    }
}
