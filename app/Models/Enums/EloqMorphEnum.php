<?php

namespace App\Models\Enums;

use App\Domains\Auth\Repositories\Eloquent\Models\User;
use App\Domains\Notes\Repositories\Eloquent\Models\Note;
use Psy\Readline\Hoa\File;

enum EloqMorphEnum : string
{
    case NOTES = 'notes';
    case FILES = 'files';
    case ASSIGNEES = 'assignees';

    /**
     * @return array
     */
    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public function getModelClass(): string
    {
        return match($this) {
            self::NOTES => Note::class,
            self::FILES => File::class,
            self::ASSIGNEES => User::class,
        };
    }
}
