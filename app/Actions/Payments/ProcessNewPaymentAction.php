<?php

namespace App\Actions\Payments;

use App\Contracts\PaymentService;
use App\Models\Payment;

class ProcessNewPaymentAction
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function execute(Payment $payment, string $gateway, array $buyerData)
    {
        /** @var PaymentService $paymentService */
        $paymentService = app(PaymentService::class, [
            'payment' => $payment,
            'gateway' => $gateway,
        ]);

        return $paymentService->create($buyerData);
    }
}
