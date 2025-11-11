<?php

namespace App\Domains\Projects\Enums;

enum ProjectCategoryStatusEnum : string
{
    case CANDIDATE = 'Candidate';
    case AGREED = 'Agreed';

//    case PENDING = 'Pending'; // extra option

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        return [
            self::CANDIDATE->value => 'Υποψήφιο',
            self::AGREED->value => 'Συμφωνήθηκε',
//            self::PENDING->value => 'Σε Αναμονή',
        ];
    }

}



