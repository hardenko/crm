<?php

namespace App\Models;

use App\Enums\StockMovementTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Exception;

class StockMovement extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'type' => StockMovementTypeEnum::class,
        ];
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'supplier_id');
    }
}
