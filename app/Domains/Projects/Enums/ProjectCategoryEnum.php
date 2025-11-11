<?php

namespace App\Domains\Projects\Enums;

enum ProjectCategoryEnum : string
{
    case INTERNAL = 'Internal';
    case EXTERNAL = 'External';

    public function options(): array
    {
        return match ($this) {
            self::INTERNAL => [
                ProjectCategoryStatusEnum::CANDIDATE,
                ProjectCategoryStatusEnum::AGREED,
            ],
            self::EXTERNAL => [
                ProjectCategoryStatusEnum::CANDIDATE,
                ProjectCategoryStatusEnum::AGREED,
            ],
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



