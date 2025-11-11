<?php

namespace App\Domains\Projects\Enums;

enum ProjectPriorityEnum : string
{
    case LOW = 'Low priority';
    case MEDIUM = 'Medium priority';
    case HIGH = 'High priority';



    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return string
     */
    public function getLabelClass() : string {
        return match($this) {
            ProjectPriorityEnum::HIGH => 'bg-label-danger',
            ProjectPriorityEnum::MEDIUM => 'bg-label-primary',
            ProjectPriorityEnum::LOW => 'bg-label-success',
        };
    }
}



