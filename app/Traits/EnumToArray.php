<?php

namespace App\Traits;

trait EnumToArray
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_combine(self::values(), self::names());
    }

    public static function options(): array
    {
        return array_combine(
            array_map(fn($type) => $type->value, self::cases()),
            array_map(fn($type) => $type->label(), self::cases())
        );
    }
}
