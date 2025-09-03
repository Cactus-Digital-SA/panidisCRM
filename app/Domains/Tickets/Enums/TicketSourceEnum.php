<?php

namespace App\Domains\Tickets\Enums;

enum TicketSourceEnum : string
{
    case EMPLOYEE = 'Employee';
    case CLIENT = 'Client';
    case EMAIL = 'Email';
    case SYSTEM = 'System';



    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}



