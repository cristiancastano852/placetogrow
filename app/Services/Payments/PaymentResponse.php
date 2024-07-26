<?php

namespace App\Services\Payments;

class PaymentResponse
{
    public function __construct(
        public readonly int $processIdentifier,
        public readonly string $url,
    ) {}

    public function toArray(): array
    {
        return [
            'url' => $this->url,
            'process_identifier' => $this->processIdentifier,
        ];
    }
}
