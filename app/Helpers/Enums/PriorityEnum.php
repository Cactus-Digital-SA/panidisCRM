<?php

namespace App\Helpers\Enums;

enum PriorityEnum : string
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
            PriorityEnum::HIGH => 'bg-label-danger',
            PriorityEnum::MEDIUM => 'bg-label-primary',
            PriorityEnum::LOW => 'bg-label-success',
        };
    }
}



