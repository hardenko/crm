<?php

namespace App\Enums;

use App\Traits\EnumToArray;

enum PaymentStatusType: string
{
    use EnumToArray;
    case Pending = 'pending';
    case Completed = 'completed';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Completed => 'Completed',
            self::Failed => 'Failed',
        };
    }
}
