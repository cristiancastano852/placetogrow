<?php

namespace Tests\Mocks;

use App\Services\Payments\Gateways\PlacetoPayGateway;
use App\Services\Payments\PaymentResponse;
use Closure;

class PlacetoPayGatewayMock extends PlacetoPayGateway
{
    public function __construct(protected Closure $closure)
    {
        $expiration = now()->addMinutes(20);
        parent::__construct($expiration);
    }

    public function process(): PaymentResponse
    {
        return ($this->closure)();
    }
}
