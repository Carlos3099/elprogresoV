<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentaDetalle extends Model
{
    protected $fillable = ['venta_id', 'producto_id', 'cantidad', 'precio_unitario', 'total_linea'];

    public function venta(): BelongsTo { return $this->belongsTo(Venta::class); }
    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
}
