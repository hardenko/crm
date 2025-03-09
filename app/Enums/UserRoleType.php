<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum UserRoleType: string
{
    use EnumToArray;
    case Admin = 'admin';
    case Manager = 'manager';
    case Warehouse = 'warehouse';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Manager =>'Manager',
            self::Warehouse => 'Warehouse',
        };
    }
}
