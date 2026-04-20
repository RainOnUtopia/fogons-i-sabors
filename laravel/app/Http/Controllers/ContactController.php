<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * El servei de contacte.
     */
    protected ContactService $contactService;

    /**
     * Crea una nova instància del controlador.
     */
    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Processa l'enviament del formulari de contacte.
     */
    public function store(ContactRequest $request): RedirectResponse
    {
        // Envia el correu a través del servei
        $this->contactService->sendContactEmail($request->validated());

        // Redirigeix amb un missatge d'èxit en català
        return back()->with('success', 'El teu missatge s\'ha enviat correctament. Ens posarem en contacte amb tu aviat.');
    }
}
