<?php

namespace App\Providers;

use App\Constants\PaymentGateway;
use App\Contracts\PaymentGateway as PaymentGatewayContract;
use App\Contracts\PaymentService as PaymentServiceContract;
use App\Services\Payments\Gateways\PaypalGateway;
use App\Services\Payments\Gateways\PlacetoPayGateway;
use App\Services\Payments\PaymentService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentServiceContract::class, function (Application $app, array $data) {
            ['payment' => $payment, 'gateway' => $gateway] = $data;

            $gateway = $app->make(PaymentGatewayContract::class, ['gateway' => $gateway]);

            return new PaymentService($payment, $gateway);
        });

        $this->app->bind(PaymentGatewayContract::class, function (Application $app, array $data) {
            return match (PaymentGateway::from($data['gateway'])) {
                PaymentGateway::PLACETOPAY => new PlacetoPayGateway(),
                PaymentGateway::PAYPAL => new PaypalGateway(),
            };
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}
}
