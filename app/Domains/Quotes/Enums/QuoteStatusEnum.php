<?php

namespace App\Domains\Quotes\Enums;

enum QuoteStatusEnum : string
{
    case DRAFT = 'Draft';
    case SENT = 'Sent';
    case ACCEPTED = 'Accepted';
    case DECLINED = 'Declined';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SENT => 'Sent',
            self::ACCEPTED => 'Accepted',
            self::DECLINED => 'Declined',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'ti ti-pencil',
            self::SENT => 'ti ti-mail',
            self::ACCEPTED => 'ti ti-circle-check',
            self::DECLINED => 'ti ti-circle-x',
        };
    }

    public function getLabelClass(): string
    {
        return match ($this) {
            self::DRAFT => 'badge bg-label-secondary',
            self::SENT => 'badge bg-label-info',
            self::ACCEPTED => 'badge bg-label-success',
            self::DECLINED => 'badge bg-label-danger',
        };
    }
}



