<?php

namespace App\Domains\Quotes\Enums;

enum PaymentTermsEnum : string
{
    case NET_30 = 'Net 30';
    case NET_45 = 'Net 45';
    case NET_60 = 'Net 60';
    case ADVANCE = 'Advance';
    case CASH_ON_DELIVERY = 'Cash on Delivery';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match($this) {
            self::NET_30 => 'Net 30 Days',
            self::NET_45 => 'Net 45 Days',
            self::NET_60 => 'Net 60 Days',
            self::ADVANCE => 'Advance Payment',
            self::CASH_ON_DELIVERY => 'Cash on Delivery',
        };
    }
}



