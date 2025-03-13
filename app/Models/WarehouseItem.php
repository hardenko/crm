<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseItem extends Model
{
    use HasFactory;

    protected $table = 'warehouse';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}
