<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum UserRoleType: string
{
    use EnumToArray;
    case Admin = 'admin';
    case Manager = 'manager';
    case Wh_manager = 'wh_manager';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Manager =>'Manager',
            self::Wh_manager => 'Wh manager',
        };
    }
}
