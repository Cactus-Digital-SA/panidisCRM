<?php

namespace App\Domains\Visits\Enums;

enum VisitNextActionSourceEnum : string
{
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
        return array_column(self::cases(), 'value');
    }

}



