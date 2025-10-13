<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Existencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'sucursal_id',
        'stock_actual',
        'stock_minimo',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
