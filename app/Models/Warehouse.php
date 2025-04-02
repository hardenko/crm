<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Warehouse extends Model
{
    protected $table = 'warehouse';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}
