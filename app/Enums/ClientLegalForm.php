<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum ClientLegalForm: string
{
    use EnumToArray;
    case Individual = 'individual';
    case SP = 'sp';
    case PE = 'pe';
    case LLC = 'llc';
    case NGO = 'ngo';
    case charitableFoundation = 'charitable_foundation';

    public function label(): string
    {
        return match ($this) {
            self::Individual => 'Individual',
            self::SP =>'Sole proprietor (SP)',
            self::PE =>'Private Enterprise (PE)',
            self::LLC =>'Limited Liability Company (LLC)',
            self::NGO =>'Non-Governmental Organization (NGO)',
            self::charitableFoundation =>'Charitable Foundation',
        };
    }
}
