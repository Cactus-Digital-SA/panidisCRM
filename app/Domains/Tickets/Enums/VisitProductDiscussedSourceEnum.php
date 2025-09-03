<?php

namespace App\Domains\Tickets\Enums;

enum VisitProductDiscussedSourceEnum : string
{
    case PIVOT_HINGE = 'Pivot Hinge';
    case CONCEALED_HINGE = 'Concealed Hinge';
    case MINIMAL_SERIES = 'Minimal Series';
    case GLASS_SYSTEM_HARDWARE = 'Glass System - Hardware';
    case HANDLES = 'Handles';
    case NON_ARCHITECTURAL_HARDWARE = 'Non-Architectural Hardware';
    case TRADITIONAL_HINGES = 'Traditional Hinges';
    case CUSTOM_MADE_SOLUTIONS = 'Custom Made Solutions';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

}



