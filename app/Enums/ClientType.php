<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum ClientType: string
{
    use EnumToArray;
    case Payer = 'payer';
    case Receiver = 'receiver';

    public function label(): string
    {
        return match ($this) {
            self::Payer => 'Payer',
            self::Receiver =>'Receiver',
        };
    }
}
