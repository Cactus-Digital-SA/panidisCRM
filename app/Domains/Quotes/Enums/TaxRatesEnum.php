<?php

namespace App\Domains\Quotes\Enums;

enum TaxRatesEnum : string
{
    case VAT_0 = '0';
    case VAT_13 = '13';
    case VAT_24 = '24';



    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::VAT_0 => '0%',
            self::VAT_13 => '13%',
            self::VAT_24 => '24%',
        };
    }

    public function asDecimal(): float
    {
        return (float) $this->value / 100;
    }

}



