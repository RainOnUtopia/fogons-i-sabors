<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactMail extends Mailable
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
            subject: 'Nou missatge de contacte — Fogons i Sabors',
            replyTo: $this->data['email'],
        );
    }

    /**
     * Obté la definició del contingut del missatge.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
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
