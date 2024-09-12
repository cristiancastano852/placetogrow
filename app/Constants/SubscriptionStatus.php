<?php

namespace App\Constants;

enum SubscriptionStatus: string
{
    case ACTIVE = 'ACTIVE';
    case INACTIVE = 'INACTIVE';
    case CANCELED = 'CANCELED';
    case SUSPENDED = 'SUSPENDED';
    case EXPIRED = 'EXPIRED';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
