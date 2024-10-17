<?php

namespace Tests\Feature\Jobs;

use App\Constants\InvoiceStatus;
use App\Jobs\SendInvoiceDueAlertJob;
use App\Mail\InvoiceDueAlertMail;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsites;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendInvoiceDueAlertJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2024-10-15'));
    }

    public function test_it_sends_due_alert_emails_for_invoices_due_in_seven_days()
    {
        Mail::fake();

        $user = User::factory()->create();

        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();
        $this->actingAs($user);
        $invoice = Invoice::factory()->create([
            'microsite_id' => $microsite->id,
            'expiration_date' => Carbon::now()->addDays(7),
            'status' => InvoiceStatus::PENDING->name, ]);

        $job = new SendInvoiceDueAlertJob();
        $job->handle();

        Mail::assertSent(InvoiceDueAlertMail::class, function ($mail) use ($invoice) {
            return $mail->hasTo($invoice->email) &&
                   $mail->invoice->id === $invoice->id;
        });

    }

    public function test_it_does_not_send_due_alert_emails_if_no_due_invoices()
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user);
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();
        $invoice = Invoice::factory()->create([
            'microsite_id' => $microsite->id,
            'expiration_date' => Carbon::now()->addDays(10),
            'status' => InvoiceStatus::PENDING->name, ]);

        $job = new SendInvoiceDueAlertJob();
        $job->handle();

        Mail::assertNotSent(InvoiceDueAlertMail::class);

    }
}
