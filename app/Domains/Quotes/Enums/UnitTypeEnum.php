<?php

namespace App\Domains\Quotes\Enums;

enum UnitTypeEnum : string
{
    case PIECE = 'piece';
    case PAIR = 'pair';
    case METER = 'meter';
    case KILOGRAM = 'kilogram';
//    case GRAM = 'gram';
//    case LITER = 'liter';
//    case HOUR = 'hour';
//    case PACKAGE = 'package';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<string, string>
     */
    public function label(): string
    {
        return match($this) {
            self::PIECE => 'Τεμάχια',
            self::METER => 'Μέτρα',
            self::KILOGRAM => 'Κιλά',
            self::PAIR => 'Ζεύγη',
        };
    }

}



