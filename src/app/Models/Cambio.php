<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cambio extends Model
{
    protected $fillable = [
        'venta_original_id', 'sucursal_id', 'usuario_id', 'cliente_id',
        'fecha', 'total_devuelto', 'total_entregado', 'diferencia',
        'metodo_ajuste', 'referencia_pago', 'notas'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function sucursal(): BelongsTo { return $this->belongsTo(Sucursal::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'usuario_id'); }
    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function ventaOriginal(): BelongsTo { return $this->belongsTo(Venta::class, 'venta_original_id'); }
    public function detalles(): HasMany { return $this->hasMany(CambioDetalle::class); }
}
