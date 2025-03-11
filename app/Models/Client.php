<?php

namespace App\Models;

use App\Enums\ClientLegalForm;
use App\Enums\ClientType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'client_type' => ClientType::class,
            'legal_form' => ClientLegalForm::class,
        ];
    }

}
