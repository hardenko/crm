<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use HasFactory;

    protected $table = 'warehouse';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}
