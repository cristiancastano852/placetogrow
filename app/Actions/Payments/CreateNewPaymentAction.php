<?php

namespace App\Actions\Payments;

use App\Models\Microsites;
use App\Models\Payment;
use App\Models\User;
use App\Repositories\PaymentRepository;

class CreateNewPaymentAction
{
    protected $paymentRepository;

    public function __construct(PaymentRepository $paymentRepository)
    {
        $this->paymentRepository = $paymentRepository;
    }

    public function execute(array $data, User $user, Microsites $microsite, string $gateway): Payment
    {
        return $this->paymentRepository->create($data, $user, $microsite, $gateway);
    }

    public function buyer($request)
    {
        return $this->paymentRepository->buyer($request)->getBuyerData();
    }
}
