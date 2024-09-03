<?php

namespace Tests\Feature\Payments\Subscription;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SubscriptionTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        Http::fake([
            'https://checkout-co.placetopay.dev/api/session' => Http::response([
                "status" => [
                    "status" => "OK",
                    "reason" => "Session created",
                    "message" => "Session created successfully",
                    "date" => "2021-10-06T20:00:00-05:00",
                ],
                "requestId" => "123456",
                "processUrl" => "https://checkout-co.placetopay.dev/session/123456",
                "session" => "1234",
            ]),
        ]);
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
