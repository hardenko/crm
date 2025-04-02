<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum PaymentStatusTypeEnum: string
{
    use EnumToArray;
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED =>'Completed',
            self::FAILED => 'Failed',
        };
    }
}
