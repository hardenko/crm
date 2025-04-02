<?php

namespace App\Models;

use App\Enums\ClientLegalFormEnum;
use App\Enums\ClientTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'client_type' => ClientTypeEnum::class,
            'legal_form' => ClientLegalFormEnum::class,
        ];
    }
}
