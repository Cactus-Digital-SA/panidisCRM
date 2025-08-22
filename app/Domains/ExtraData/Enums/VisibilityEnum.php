<?php

namespace App\Domains\ExtraData\Enums;

enum VisibilityEnum : string
{
    case ALL = 'All';
    case ADMIN = 'Admin';
    case NONE = 'None';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


}



