<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Dades del formulari de contacte.
     */
    public array $data;

    /**
     * Crea una nova instància del missatge.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Obté el sobre del missatge.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Hem rebut el teu missatge — Fogons i Sabors',
        );
    }

    /**
     * Obté la definició del contingut del missatge.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-confirmation',
        );
    }

    /**
     * Obté els adjunts del missatge.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
