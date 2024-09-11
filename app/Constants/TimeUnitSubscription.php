<?php

declare(strict_types=1);

namespace App\Constants;

enum TimeUnitSubscription: string
{
    case DAY = 'Days';
    case MONTH = 'Months';
    case YEAR = 'Years';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
