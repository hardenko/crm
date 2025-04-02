<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum ClientLegalFormEnum: string
{
    use EnumToArray;
    case INDIVIDUAL = 'individual';
    case SP = 'sp';
    case PE = 'pe';
    case LLC = 'llc';
    case NGO = 'ngo';
    case CHARITABLE_FOUNDATION = 'charitable_foundation';

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'Individual',
            self::SP =>'Sole proprietor (SP)',
            self::PE =>'Private Enterprise (PE)',
            self::LLC =>'Limited Liability Company (LLC)',
            self::NGO =>'Non-Governmental Organization (NGO)',
            self::CHARITABLE_FOUNDATION =>'Charitable Foundation',
        };
    }
}
