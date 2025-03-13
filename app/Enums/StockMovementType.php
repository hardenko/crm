<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum StockMovementType: string
{
    use EnumToArray;
    case Incoming = 'incoming';
    case Outgoing = 'outgoing';

    public function label(): string
    {
        return match ($this) {
            self::Incoming => 'Incoming',
            self::Outgoing => 'Outgoing',
        };
    }

    public function isIncoming(): bool
    {
        return $this === self::Incoming;
    }

    public function isOutgoing(): bool
    {
        return $this === self::Outgoing;
    }
}
