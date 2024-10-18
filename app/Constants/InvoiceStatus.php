<?php

namespace App\Constants;

enum InvoiceStatus
{
    case PENDING;
    case PAID;
    case FAILED;
    case EXPIRED;
    case IN_PROCESS;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'name');
    }
}
