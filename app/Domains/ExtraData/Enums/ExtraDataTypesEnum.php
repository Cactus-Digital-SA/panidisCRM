<?php

namespace App\Domains\ExtraData\Enums;

enum ExtraDataTypesEnum : string
{
    case TEXT = 'Text';
    case INT = 'Integer';
    case SELECT = 'Select';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


}



