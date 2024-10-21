<?php

declare(strict_types=1);

namespace App\Constants;

enum Roles: string
{
    case ADMIN = 'Admin';
    case CUSTOMER = 'Customer';
    case GUEST = 'Guests';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
