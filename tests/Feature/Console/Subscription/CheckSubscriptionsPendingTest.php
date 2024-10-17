<?php

namespace Tests\Feature\Console\Subscription;

use App\Constants\SubscriptionStatus;
use App\Jobs\ProcessPendingSubscriptionJob;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class CheckSubscriptionsPendingTest extends TestCase
{
    use RefreshDatabase;

    public function test_check_subscriptions_pending_no_subscriptions(): void
    {
        $this->artisan('app:check-subscriptions-pending')
            ->assertExitCode(0);
    }

    public function test_check_subscriptions_pending_with_subscriptions(): void
    {
        Bus::fake();

        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->create();

        $subscription = Subscription::factory()->create([
            'status' => SubscriptionStatus::PENDING->value,
        ]);

        $this->artisan('app:check-subscriptions-pending')
            ->assertExitCode(0);

        Bus::assertDispatched(ProcessPendingSubscriptionJob::class);
    }
}
