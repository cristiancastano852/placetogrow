<?php

namespace App\Constants;

enum DocumentTypes
{
    case CC;
    case NIT;
    case CE;
    case PPT;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'name');
    }
}
