<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendSubscriptionExpiryAlertJob;
use App\Mail\SubscriptionExpiryAlertMail;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendSubscriptionExpityAlertJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2024-10-15'));
    }

    public function test_send_subscription_expiry_alert_job_successfully()
    {
        Mail::fake();

        $user = User::factory()->create();
        $this->actingAs($user);
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();

        $subscription = Subscription::factory()->create([
            'microsite_id' => $microsite->id,
            'expiration_date' => Carbon::now()->addDays(7),
            'user_id' => $user->id,
        ]);

        $job = new SendSubscriptionExpiryAlertJob();
        $job->handle();

        Mail::assertSent(SubscriptionExpiryAlertMail::class, function ($mail) use ($subscription) {
            return $mail->hasTo($subscription->user->email) &&
                $mail->subscription->id === $subscription->id;
        });
    }

    public function test_it_does_not_send_due_alert_emails_if_no_subscription_expires()
    {
        Mail::fake();
        $user = User::factory()->create();
        $this->actingAs($user);
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();

        $subscription = Subscription::factory()->create([
            'microsite_id' => $microsite->id,
            'expiration_date' => Carbon::now()->addDays(10),
            'user_id' => $user->id,
        ]);

        $job = new SendSubscriptionExpiryAlertJob();
        $job->handle();

        Mail::assertNotSent(SubscriptionExpiryAlertMail::class);
    }
}
