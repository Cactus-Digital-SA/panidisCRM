<?php

namespace App\Domains\Visits\Enums;

enum VisitTypeSourceEnum : string
{
    case IN_PERSON = 'In-person';
    case PHONE = 'Phone';
    case VIDEO = 'Video';
    case EMAIL = 'Email';
    case TRADE_SHOW = 'Trade Show';
    case FACTORY_TOUR = 'Factory Tour';
    case CUSTOMER_VISIT = 'Customer Visit';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}



