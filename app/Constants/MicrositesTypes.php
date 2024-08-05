<?php

namespace App\Constants;

enum MicrositesTypes
{
    case Facturas;
    case Donaciones;
    case Subscripciones;

    public static function toArray(): array
    {
        return array_column(self::cases(), 'name');
    }
}
