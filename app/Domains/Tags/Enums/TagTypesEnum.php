<?php

namespace App\Domains\Tags\Enums;

use App\Models\BaseEnum;

enum TagTypesEnum: int
{
    use BaseEnum;

    case PRODUCT = 1;

    public function label(): string
    {
        return match ($this) {
            self::PRODUCT => 'Product Tag',

        };
    }

}
