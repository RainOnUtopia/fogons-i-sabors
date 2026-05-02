<?php

namespace Tests\Feature;

use App\Mail\ContactConfirmationMail;
use App\Mail\ContactMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_sends_admin_and_confirmation_emails(): void
    {
        Mail::fake();
        Config::set('mail.from.address', 'admin@fogons.local');

        $response = $this->post(route('contact.store'), [
            'name' => 'Prova',
            'email' => 'user@example.com',
            'message' => 'Hola! Aquest és un missatge prou llarg.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Mail::assertSent(ContactMail::class);
        Mail::assertSent(ContactConfirmationMail::class);
    }

    public function test_contact_form_validates_minimum_message_length(): void
    {
        Mail::fake();

        $response = $this->from(route('contact'))->post(route('contact.store'), [
            'name' => 'Prova',
            'email' => 'user@example.com',
            'message' => 'curt',
        ]);

        $response->assertRedirect(route('contact'));
        $response->assertSessionHasErrors(['message']);
        Mail::assertNothingSent();
    }
}
