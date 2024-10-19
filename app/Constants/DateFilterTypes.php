<?php

namespace App\Constants;

enum DateFilterTypes: string
{
    case START = 'start';
    case END = 'end';

    public static function toArray(): array
    {
        return array_column(self::cases(), 'value');
    }
}
