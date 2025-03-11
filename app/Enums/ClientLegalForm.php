<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum ClientLegalForm: string
{
    use EnumToArray;
    case Individual = 'Individual';
    case SP = 'Sole proprietor (SP)';
    case PE = 'Private Enterprise (PE)';
    case LLC = 'Limited Liability Company (LLC)';
    case NGO = 'Non-Governmental Organization (NGO)';
    case Charitable_Foundation = 'Charitable Foundation';

    public function label(): string
    {
        return match ($this) {
            self::Individual => 'Individual',
            self::SP =>'Sole proprietor (SP)',
            self::PE =>'Private Enterprise (PE)',
            self::LLC =>'Limited Liability Company (LLC)',
            self::NGO =>'Non-Governmental Organization (NGO)',
            self::Charitable_Foundation =>'Charitable Foundation',
        };
    }
}
