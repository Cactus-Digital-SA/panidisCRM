<?php

namespace App\Domains\Clients\Repositories\Eloquent\Models;

use App\Models\BaseEnum;

enum ClientStatusEnum: int
{
    use BaseEnum;

    case OPEN = 1;
    case CONVERTED = 2;
    case LOST = 3;

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Ανοιχτό',
            self::CONVERTED => 'Μετατράπηκε',
            self::LOST => 'Χαμένο',
        };
    }

}
