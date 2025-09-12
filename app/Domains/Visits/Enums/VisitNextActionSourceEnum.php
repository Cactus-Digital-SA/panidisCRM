<?php

namespace App\Domains\Visits\Enums;

enum VisitNextActionSourceEnum : string
{
    case OPEN = 'Open';

    case SEND_QUOTATION = 'Send Quotation';
    case SEND_INFORMATIONAL_MATERIAL = 'Send Informational Material / Brochure';
    case SCHEDULE_NEXT_CALL = 'Schedule Next Call';
    case SEND_PRICE_LIST = 'Send Price List';
    case INTERNAL_REVIEW = 'Internal Review';
    case WAITING_FOR_CLIENT = 'Waiting for Client';
    case FOLLOW_UP = 'Follow-up';
    case SCHEDULE_PRESENTATION_DEMO = 'Schedule Presentation / Demo';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(
            fn($case) => $case->value,
            array_filter(self::cases(), fn($case) => $case !== self::OPEN)
        );
    }

    public static function selectableCases(): array
    {
        return array_filter(self::cases(), fn($case) => $case !== self::OPEN);
    }

}



