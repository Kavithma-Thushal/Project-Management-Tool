<?php

namespace App\Enums;

enum PriorityTypeEnum
{
    const LOW = 0;
    const HIGH = 1;

    public static function values(): array
    {
        return [
            self::LOW => 'LOW',
            self::HIGH => 'HIGH',
        ];
    }

    public static function getAll(): array
    {
        return array_map(fn($key, $value) => ['value' => $key, 'name' => $value], array_keys(self::values()), self::values());
    }
}
