<?php

namespace Tests\Feature\Invoice;

use App\Constants\Roles;
use App\Models\Microsites;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicesByMicrositeTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_by_microsite_success(): void
    {

        $role = Role::factory()->create(['name' => Roles::ADMIN->value]);
        $user = User::factory()->create();
        $user->assignRole($role);
        $microsite = Microsites::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('invoice.invoicesByMicrosite', $microsite));
        $response->assertStatus(200);

    }
}
