<?php

namespace App\Services;

use App\Mail\ContactMail;
use App\Mail\ContactConfirmationMail;
use Illuminate\Support\Facades\Mail;

class ContactService
{
    /**
     * Envia els correus de contacte.
     */
    public function sendContactEmail(array $data): void
    {
        // 1. Envia el correu a l'administrador
        $adminEmail = env('ADMIN_CONTACT_EMAIL', config('mail.from.address'));
        Mail::to($adminEmail)->send(new ContactMail($data));

        // 2. Envia el correu de confirmació a l'usuari
        Mail::to($data['email'])->send(new ContactConfirmationMail($data));
    }
}
