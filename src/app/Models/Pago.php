<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'venta_id',
        'metodo_pago',
        'monto',
        'referencia_pago',
        'estado',
        'fecha_pago',
        'destinatario_transferencia' // Agregar este campo
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'monto' => 'decimal:2',
        'fecha_pago' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the venta that owns the pago.
     */
    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }

    /**
     * Scope para pagos completados.
     */
    public function scopeCompletados($query)
    {
        return $query->where('estado', 'completado');
    }

    /**
     * Scope para pagos pendientes.
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para pagos fallidos.
     */
    public function scopeFallidos($query)
    {
        return $query->where('estado', 'fallido');
    }

    /**
     * Scope para un método de pago específico.
     */
    public function scopePorMetodo($query, $metodo)
    {
        return $query->where('metodo_pago', $metodo);
    }

    // NUEVO SCOPE PARA PAGOS MÚLTIPLES
    public function scopeDeVentasMultiples($query)
    {
        return $query->whereHas('venta', function ($q) {
            $q->where('metodo_pago', 'multiple');
        });
    }
}