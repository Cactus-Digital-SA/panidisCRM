<?php

namespace App\Domains\Quotes\Enums;

enum UnitTypeEnum : string
{
    case PIECE = 'piece';
    case KILOGRAM = 'kg';
    case GRAM = 'g';
    case LITER = 'l';
    case METER = 'm';
    case HOUR = 'hour';
    case PACKAGE = 'package';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}



