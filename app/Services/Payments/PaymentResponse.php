<?php

namespace App\Services\Payments;

class PaymentResponse
{
    public function __construct(
        public readonly int $processIdentifier,
        public readonly ?string $url,
        public readonly string $status,
        public readonly ?string $message = null
    ) {}

    public function toArray(): array
    {
        return [
            'process_identifier' => $this->processIdentifier, 'url' => $this->url,
            'status' => $this->status, 'message' => $this->message,
        ];
    }

    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
