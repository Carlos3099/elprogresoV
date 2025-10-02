<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Empleado extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'empleados';

    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'activo'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Verificar si el empleado es gerente
     */
    public function isGerente()
    {
        return $this->rol === 'gerente';
    }

    /**
     * Verificar si el empleado es vendedor
     */
    public function isVendedor()
    {
        return $this->rol === 'vendedor';
    }

    /**
     * Verificar si el empleado es empleado básico
     */
    public function isEmpleado()
    {
        return $this->rol === 'empleado';
    }
}