<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Pago;

class Venta extends Model
{
    protected $fillable = [
        'sucursal_id', 'usuario_id', 'cliente_id', 'fecha',
        'subtotal', 'descuento', 'impuestos', 'total',
        'metodo_pago', 'referencia_pago', 'notas'
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function sucursal(): BelongsTo { return $this->belongsTo(Sucursal::class); }
    public function usuario(): BelongsTo { return $this->belongsTo(User::class, 'usuario_id'); }
    public function cliente(): BelongsTo { return $this->belongsTo(Cliente::class); }
    public function detalles(): HasMany { return $this->hasMany(VentaDetalle::class); }

    //NUEVA RELACIÓN CON PAGOS
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    //NUEVO ACCESOR PARA SABER SI ES PAGO MÚLTIPLE
    public function getEsPagoMultipleAttribute(): bool
    {
        return $this->metodo_pago === 'multiple';
    }

    // NUEVO ACCESOR PARA TOTAL PAGADO (SUMA DE PAGOS)
    public function getTotalPagadoAttribute(): float
    {
        return $this->pagos->sum('monto');
    }

    //NUEVO ACCESOR PARA VALIDAR INTEGRIDAD
    public function getPagoCompletoAttribute(): bool
    {
        return $this->totalPagado >= $this->total;
    }
}
