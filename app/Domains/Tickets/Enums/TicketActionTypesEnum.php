<?php

namespace App\Domains\Tickets\Enums;

enum TicketActionTypesEnum : string
{
    case TASKS = 'Tasks';
    case QUOTES = 'Quotes';
    case VISITS = 'Visits';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Επιστρέφει όλα τα slugs
     */
    public static function slugs(): array
    {
        return array_map(fn(self $case) => $case->slug(), self::cases());
    }

    public function slug(): string
    {
        return match ($this) {
            self::TASKS => 'tasks',
            self::QUOTES => 'quotes',
            self::VISITS => 'visits',
        };
    }

    /**
     * Εύρεση enum instance από slug
     */
    public static function fromSlug(string $slug): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->slug() === strtolower($slug)) {
                return $case;
            }
        }

        return null;
    }
}



