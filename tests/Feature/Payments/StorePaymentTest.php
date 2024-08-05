<?php

namespace Tests\Feature\Payments;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\Payment;
use App\Models\Role;
use App\Models\User;
use App\Services\Payments\Gateways\PlacetoPayGateway;
use App\Services\Payments\PaymentResponse;
use App\Services\Payments\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class StorePaymentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testitStoresPaymentSuccessfully(): void
    {
        $responseData = [
            'status' => [
                'status' => 'OK',
                'reason' => 'PC',
                'message' => 'Respuesta falsa',
                'date' => '2021-11-30T15:08:27-05:00',
            ],
            'requestId' => 1,
            'processUrl' => 'https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a',
        ];

        Http::fake(fn (Request $request) => Http::response($responseData, 200));

        $user = User::factory()->create();

        $microsites = Microsites::factory()
            ->for(Category::factory()->create())
            ->for(($user))
            ->create(
                [
                    'name' => 'test-name',

                ]
            );

        $data = [
            'description' => fake()->sentence(),
            'amount' => 10000,
            'microsite_id' => $microsites->id,
            'user_id' => $user->id,
            'currency' => Currency::USD->name,
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => fake()->freeEmail,
            'document_number' => 12123123123,
            'document_type' => DocumentTypes::CC->name,
            'gateway' => PaymentGateway::PLACETOPAY->value,
            'fields_data' => [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => fake()->freeEmail,
                'document_number' => 12123123123,
                'document_type' => DocumentTypes::CC->name,
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('payments.store'), $data);

        $response->assertSessionHasNoErrors()
            ->assertRedirect($responseData['processUrl']);

        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'microsite_id' => $microsites->id,
            'description' => $data['description'],
            'amount' => 10000,
            'gateway' => PaymentGateway::PLACETOPAY->value,
            'status' => PaymentStatus::PENDING->value,
            'process_identifier' => $responseData['requestId'],
        ]);
    }

    public function testItHandlesClientError(): void
    {
        $responseData = [
            'message' => 'Client error occurred',
        ];

        Http::fake(fn (Request $request) => Http::response($responseData, 400));

        $user = User::factory()->create();

        $microsites = Microsites::factory()
            ->for(Category::factory()->create())
            ->for($user)
            ->create([
                'name' => 'test-name',
            ]);

        $data = [
            'description' => fake()->sentence(),
            'amount' => 10000,
            'microsite_id' => $microsites->id,
            'user_id' => $user->id,
            'currency' => Currency::USD->name,
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => fake()->freeEmail,
            'document_number' => 12123123123,
            'document_type' => DocumentTypes::CC->name,
            'gateway' => PaymentGateway::PLACETOPAY->value,
            'fields_data' => [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => fake()->freeEmail,
                'document_number' => 12123123123,
                'document_type' => DocumentTypes::CC->name,
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('payments.store'), $data);
        //estatus 500
        //validate status 500
        $response->assertStatus(500);
        // $response->assertSessionHasErrors(['message' => 'Client error occurred']);
        // $response->assertRedirect();
    }

    public function testItHandlesUnknownError(): void
    {
        $responseData = [
            'message' => 'An unknown error occurred',
        ];

        Http::fake(fn (Request $request) => Http::response($responseData, 418)); // 418 I'm a teapot

        $user = User::factory()->create();

        $microsites = Microsites::factory()
            ->for(Category::factory()->create())
            ->for($user)
            ->create([
                'name' => 'test-name',
            ]);

        $data = [
            'description' => fake()->sentence(),
            'amount' => 10000,
            'microsite_id' => $microsites->id,
            'user_id' => $user->id,
            'currency' => Currency::USD->name,
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => fake()->freeEmail,
            'document_number' => 12123123123,
            'document_type' => DocumentTypes::CC->name,
            'gateway' => PaymentGateway::PLACETOPAY->value,
            'fields_data' => [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => fake()->freeEmail,
                'document_number' => 12123123123,
                'document_type' => DocumentTypes::CC->name,
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('payments.store'), $data);
        $response->assertStatus(500);
        // $response->assertSessionHasErrors(['message' => 'An unknown error occurred']);
    }

    public function testItStoresPaymentPaypalSuccessfully(): void
    {
        $this->withoutExceptionHandling();
        $responseData = [
            'status' => [
                'status' => 'OK',
                'reason' => 'PC',
                'message' => 'Respuesta falsaasd',
                'date' => '2021-11-30T15:08:27-05:00',
            ],
            'requestId' => 1,
            'processUrl' => 'https://checkout-co.placetopay.com/session/1/cc9b8690b1f7228c78b759ce27d7e80a',
        ];

        Http::fake(fn (Request $request) => Http::response($responseData, 200));

        $user = User::factory()->create();

        $microsites = Microsites::factory()
            ->for(Category::factory()->create())
            ->for(($user))
            ->create(
                [
                    'name' => 'test-name',

                ]
            );

        $data = [
            'description' => fake()->sentence(),
            'amount' => 10000,
            'microsite_id' => $microsites->id,
            'user_id' => $user->id,
            'currency' => Currency::USD->name,
            'name' => 'John',
            'last_name' => 'Doe',
            'email' => fake()->freeEmail,
            'document_number' => 12123123123,
            'document_type' => DocumentTypes::CC->name,
            'gateway' => PaymentGateway::PAYPAL->value,
            'fields_data' => [
                'name' => 'John',
                'last_name' => 'Doe',
                'email' => fake()->freeEmail,
                'document_number' => 12123123123,
                'document_type' => DocumentTypes::CC->name,
            ],
        ];

        $response = $this->actingAs($user)
            ->post(route('payments.store'), $data);

        $response->assertSessionHasNoErrors()
            ->assertRedirect($responseData['processUrl']);

        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'microsite_id' => $microsites->id,
            'description' => $data['description'],
            'amount' => 10000,
            'status' => PaymentStatus::PENDING->value,
            'process_identifier' => $responseData['requestId'],
        ]);
    }

    public function testItShowsPaymentDetailsSuccessfully(): void
    {
        $user = User::factory()->create();
        $microsite = Microsites::factory()
            ->for(Category::factory()->create())
            ->for($user)
            ->create();

        $payment = Payment::factory()->create([
            'user_id' => $user->id,
            'microsite_id' => $microsite->id,
            'status' => PaymentStatus::PENDING->value,
        ]);

        $paymentService = $this->createMock(PaymentService::class);
        $placetopay = $this->createMock(PlacetoPayGateway::class);
        $placetopay->method('process')
            ->willReturn(new PaymentResponse(1, 'https://placetopay.com', 'success'));

        $this->app->instance(PaymentService::class, $paymentService);
        $this->mock(PaymentService::class, function ($mock) use ($payment) {
            $mock->shouldReceive('query')->andReturn($payment);
        });

        $response = $this->get(route('payments.show', $payment));
        $response->assertStatus(200);
    }

    public function testItShowsTransactionsSuccessfully(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'Admin']);
        $user->roles()->attach($role);
        $response = $this->actingAs($user)
            ->get(route('payments.transactions'));

        $response->assertStatus(200);

        $userCustomer = User::factory()->create();
        $role = Role::factory()->create(['name' => 'Customer']);
        $userCustomer->roles()->attach($role);
        $response = $this->actingAs($userCustomer)
            ->get(route('payments.transactions'));
        $response->assertStatus(200);

        $userGuest = User::factory()->create();

        $role = Role::factory()->create(['name' => 'Guests']);
        $userGuest->roles()->attach($role);
        $response = $this->actingAs($userGuest)
            ->get(route('payments.transactions'));
        $response->assertStatus(200);
    }

    public function testItShowsTransactionsByMicrositeSuccessfully(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $role = Role::factory()->create(['name' => 'Admin']);
        $user->roles()->attach($role);
        $response = $this->actingAs($user)
            ->get(route('payments.transactionsByMicrosite', 1));
        $response->assertStatus(200);
    }
}
