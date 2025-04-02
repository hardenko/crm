<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum ClientTypeEnum: string
{
    use EnumToArray;
    case PAYER = 'payer';
    case RECEIVER = 'receiver';
    case SUPPLIER = 'supplier';

    public function label(): string
    {
        return match ($this) {
            self::PAYER => 'Payer',
            self::RECEIVER =>'Receiver',
            self::SUPPLIER =>'Supplier',
        };
    }
}
