<?php

namespace App\Domains\ErpSales\Enums;

enum MonthEnum: int
{
    case January   = 1;
    case February  = 2;
    case March     = 3;
    case April     = 4;
    case May       = 5;
    case June      = 6;
    case July      = 7;
    case August    = 8;
    case September = 9;
    case October   = 10;
    case November  = 11;
    case December  = 12;

    public static function fromApi(string $monthSales, string|int $yearNum): ?self
    {
        $month = self::tryFrom((int) $yearNum);

        if ($month && $month->name === $monthSales) {
            return $month;
        }

        return self::tryFrom((int) $yearNum);
    }

    public function label(): string
    {
        return $this->name;
    }

    public function number(): int
    {
        return $this->value;
    }
}
