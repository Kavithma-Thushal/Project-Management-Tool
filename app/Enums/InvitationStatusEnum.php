<?php

namespace App\Enums;

enum InvitationStatusEnum
{
    const PENDING = 0;
    const APPROVED = 1;
    const REJECTED = 2;

    /**
     * Get all values of the enum.
     *
     * @return array
     */
    public static function values(): array
    {
        return [
            self::PENDING,
            self::APPROVED,
            self::REJECTED,
        ];
    }
}
