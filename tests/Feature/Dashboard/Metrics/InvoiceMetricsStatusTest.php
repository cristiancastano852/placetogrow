<?php

namespace Tests\Feature\Dashboard\Metrics;

use App\Constants\InvoiceStatus;
use App\Constants\Roles;
use App\Models\Category;
use App\Models\Invoice;
use App\Models\Microsites;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceMetricsStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculation_of_invoice_metrics_status_to_user_admin(): void
    {

        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => Roles::ADMIN->name]);
        $user->assignRole($role);
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->for(($user))
            ->create(
                [
                    'name' => 'test-name',

                ]
            );
        Invoice::factory()->create([
            'status' => InvoiceStatus::PENDING->name,
            'microsite_id' => $microsite->id,
        ]);
        $this->actingAs($user);
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_calculation_of_invoice_metrics_status_to_user_customer(): void
    {

        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => Roles::CUSTOMER->name]);

        $user->assignRole($role);
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->for(($user))
            ->create(
                [
                    'name' => 'test-name',

                ]
            );
        Invoice::factory()->create([
            'status' => InvoiceStatus::PENDING->name,
            'microsite_id' => $microsite->id,
        ]);
        $this->actingAs($user);
        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
    }

    public function test_calculation_of_invoice_metrics_status_with_filters(): void
    {

        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => Roles::ADMIN->name]);
        $user->assignRole($role);
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->for(($user))
            ->create(
                [
                    'name' => 'test-name',

                ]
            );
        Invoice::factory()->create([
            'status' => InvoiceStatus::PENDING->name,
            'microsite_id' => $microsite->id,
        ]);
        $this->actingAs($user);
        $startDate = now()->subDays(7)->format('Y-m-d');
        $endDate = now()->format('Y-m-d');
        $response = $this->get(route('dashboard', ['startDate' => $startDate, 'endDate' => $endDate]));
        $response->assertStatus(200);
    }
}
