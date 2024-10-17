<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentStartedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $buyer) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Started',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mails.payment_started',
            with: ['buyer' => $this->buyer],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
