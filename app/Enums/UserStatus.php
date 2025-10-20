<?php

namespace App\Enums;

enum UserStatus: int
{
    case Pending = 0;
    case Active = 1;
    case Inactive = 2;
    case Suspended = 3;

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Suspended => 'Suspended',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'badge-warning',
            self::Active => 'badge-success',
            self::Inactive => 'badge-neutral',
            self::Suspended => 'badge-error',
        };
    }
}
