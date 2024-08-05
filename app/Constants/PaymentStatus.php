<?php

namespace App\Constants;

enum PaymentStatus: string
{
    case PENDING = 'PENDING';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
    case APPROVED_PARTIAL = 'APPROVED_PARTIAL';
    case PARTIAL_EXPIRED = 'PARTIAL_EXPIRED';
    case UNKNOWN = 'UNKNOWN';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
