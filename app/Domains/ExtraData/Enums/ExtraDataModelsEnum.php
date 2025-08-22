<?php

namespace App\Domains\ExtraData\Enums;

use App\Models\BaseEnum;
use App\Models\ModelMorphEnum;

enum ExtraDataModelsEnum : string
{
    use BaseEnum;

    case USER = ModelMorphEnum::USER->value;

    public function label(): string
    {
        return match ($this) {
            self::USER => 'User',
        };
    }

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }


}



