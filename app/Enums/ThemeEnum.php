<?php

namespace App\Enums;

enum ThemeEnum
{
    const LIGHT = 0;
    const DARK = 1;

    public static function values(): array
    {
        return [
            self::LIGHT => 'LIGHT',
            self::DARK => 'DARK',
        ];
    }

    public static function getAll(): array
    {
        return array_map(fn($key, $value) => ['value' => $key, 'name' => $value], array_keys(self::values()), self::values());
    }
}
