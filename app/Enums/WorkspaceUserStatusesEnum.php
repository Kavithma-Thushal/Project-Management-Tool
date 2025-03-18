<?php

namespace App\Enums;

enum WorkspaceUserStatusesEnum
{
    const BLOCKED = 'blocked';
    const ACTIVE = 'active';
    const PENDING = 'pending';


    public static function values(): array
    {
        return [
            self::BLOCKED,
            self::ACTIVE,
            self::PENDING,
        ];
    }
}
