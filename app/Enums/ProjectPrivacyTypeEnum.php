<?php

namespace App\Enums;

enum ProjectPrivacyTypeEnum
{
    const PRIVATE = 'private';
    const PUBLIC = 'public';

    public static function values(): array
    {
        return [
            self::PRIVATE,
            self::PUBLIC
        ];
    }

}
