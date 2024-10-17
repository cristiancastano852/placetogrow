<?php

namespace App\Factories;

use App\Constants\MicrositesTypes;
use App\Models\Microsites;
use App\Models\Payment;
use App\Services\Payments\DonationPaymentService;

// use App\Services\Payments\InvoicePaymentService;
// use App\Services\Payments\SubscriptionPaymentService;
class PaymentDataProviderFactory
{
    public function make(Payment $payment, Microsites $microsite)
    {
        return match ($microsite->site_type) {
            //return donationt patment
            MicrositesTypes::Donaciones->name => new DonationPaymentService($payment, $microsite),
            MicrositesTypes::Subscripciones->name => new DonationPaymentService($payment, $microsite),
            MicrositesTypes::Facturas->name => new DonationPaymentService($payment, $microsite),

            // MicrositesTypes::Donaciones->name => new DonationPaymentService($payment, $microsite),
            // MicrositesTypes::Subscripciones->name => new SubscriptionPaymentService($payment, $microsite),
            // MicrositesTypes::Facturas->name => new InvoicePaymentService($payment, $microsite),
            default => throw new \InvalidArgumentException("Unsupported microsite type: {$microsite->type}"),
        };
    }
}
