<?php

namespace Tests\Feature\Payments;

use App\Constants\Currency;
use App\Constants\DocumentTypes;
use App\Constants\PaymentGateway;
use App\Constants\PaymentStatus;
use App\Models\Category;
use App\Models\Microsites;
use App\Models\Payment;
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

    public function TestitStoresPaymentSuccessfully(): void
    {
        $this -> withoutExceptionHandling();
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
        ];

        $response = $this->post(route('payments.store'), $data);

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

    public function testItStoresPaymentPaypalSuccessfully(): void
    {

        $this -> withoutExceptionHandling();
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
        ];

        $response = $this->post(route('payments.store'), $data);

        $response->assertSessionHasNoErrors()
            ->assertRedirect($responseData['processUrl']);

        $this->assertDatabaseHas('payments', [
            'user_id' => $user->id,
            'microsite_id' => $microsites->id,
            'description' => $data['description'],
            'amount' => 10000,
            'gateway' => PaymentGateway::PAYPAL->value,
            'status' => PaymentStatus::PENDING->value,
            'process_identifier' => $responseData['requestId'],
        ]);
    }

    public function testItShowsPaymentDetailsSuccessfully(): void
    {
        $this->withoutExceptionHandling();
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
            ->willReturn(new PaymentResponse(1, 'https://placetopay.com'));

        $this->app->instance(PaymentService::class, $paymentService);
        $this->mock(PaymentService::class, function ($mock) use ($payment) {
            $mock->shouldReceive('query')->andReturn($payment);
        });

        $response = $this->get(route('payments.show', $payment));

        $response->assertStatus(200);
        $response->assertViewIs('payments.show');

    }
}
