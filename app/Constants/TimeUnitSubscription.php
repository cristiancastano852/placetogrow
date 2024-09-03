<?php

declare(strict_types=1);

namespace App\Constants;

enum TimeUnitSubscription: string
{
    case DAY = 'day';
    case MONTH = 'month';
    case YEAR = 'year';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
