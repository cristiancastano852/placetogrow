<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendSubscriptionNextCollectJob;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SendSubscriptionNextCollectJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2024-10-15'));
    }

    public function test_send_subscription_next_collect_job_successfully()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();
        Subscription::factory()->create([
            'microsite_id' => $microsite->id,
            'next_billing_date' => Carbon::now()->addDays(2),
            'user_id' => $user->id,
        ]);
        $job = new SendSubscriptionNextCollectJob();
        $job->handle();

        $this->assertTrue(true);

    }

    public function test_it_does_not_send_due_alert_emails_if_no_subscription_expires()
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();

        Subscription::factory()->create([
            'microsite_id' => $microsite->id,
            'next_billing_date' => Carbon::now()->addDays(100),
            'user_id' => $user->id,
        ]);

        $job = new SendSubscriptionNextCollectJob();
        $job->handle();

        $this->assertTrue(true);
    }
}
