<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum StockMovementTypeEnum: string
{
    use EnumToArray;
    case INCOMING = 'incoming';
    case OUTGOING = 'outgoing';

    public function label(): string
    {
        return match ($this) {
            self::INCOMING => 'Incoming',
            self::OUTGOING => 'Outgoing',
        };
    }
}
