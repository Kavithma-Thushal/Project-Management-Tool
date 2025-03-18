<?php

namespace App\Enums;

enum TaskTimelineTypeEnum
{
    const STATUS_CHANGE = 0;
    const COMMENT = 1;

    public static function values(): array
    {
        return [
            self::STATUS_CHANGE,
            self::COMMENT
        ];
    }
}
