<?php

namespace Tests\Feature\Invoice;

use App\Models\Invoice;
use App\Models\Microsites;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicesByUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_invoices_by_user_success(): void
    {
        $user = User::factory()->create();
        $microsite = Microsites::factory()->create();
        $role = Role::factory()->create(['name' => 'admin']);
        $user->roles()->attach($role);
        $invoice = Invoice::factory()->create(['microsite_id' => $microsite->id, 'email' => $user->email]);
        $response = $this->actingAs($user)
            ->get(route('invoice.invoicesByUser'));
        $response->assertStatus(200);

    }
}
