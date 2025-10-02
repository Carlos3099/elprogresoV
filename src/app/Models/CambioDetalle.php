<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CambioDetalle extends Model
{
    protected $fillable = [
        'cambio_id', 'producto_id', 'tipo', 'cantidad', 'precio_unitario', 'total_linea'
    ];

    public function cambio(): BelongsTo { return $this->belongsTo(Cambio::class); }
    public function producto(): BelongsTo { return $this->belongsTo(Producto::class); }
}
