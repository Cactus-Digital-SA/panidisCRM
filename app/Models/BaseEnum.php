<?php

namespace App\Models;

trait BaseEnum
{
    // The model function will be inherited by all enums
    public function model(): object
    {
        return (object)[
            'id' => $this->value,
            'name' => $this->label(),
        ];
    }

    // The names function can be inherited by all enums
    public static function names(): array
    {
        return array_map(fn($case) => $case->label(), self::cases());
    }

    // The values function can be inherited by all enums
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    // The array function can be inherited by all enums
    public static function array(): array
    {
        $allData = [];
        foreach (static::cases() as $case) {
            $allData[$case->value] = $case->label();
        }
        return $allData;
    }
}
