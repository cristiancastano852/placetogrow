<?php

namespace App\Jobs;

use App\Mail\PaymentStartedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendConfirmationToClient implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(private readonly array $buyer) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Mail::to($this->buyer['email'])->send(new PaymentStartedMail($this->buyer));
    }

    public function buyerName(): ?string
    {
        return $this->buyer['name'];
    }
}
