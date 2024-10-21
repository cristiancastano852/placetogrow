<?php

namespace App\Mail;

use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SubscriptionNextCollectAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public Subscription $subscription;

    public function __construct(Subscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('subscription.next_collect_alert_mail.subject', [
                'microsite' => $this->subscription->microsite->name,
                'plan' => $this->subscription->plan->name,
            ]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mails.subscription.next_collect_alert',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
