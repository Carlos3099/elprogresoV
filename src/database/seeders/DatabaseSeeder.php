<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Sucursal;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crear la sucursal principal
        $sucursalMatriz = Sucursal::firstOrCreate(
            ['nombre' => 'Matriz'],
            ['direccion' => 'Centro', 'telefono' => '']
        );

        // Crear usuario administrador
        User::firstOrCreate(
            ['email' => 'admin@crm.test'],
            [
                'nombre'      => 'Admin',
                'password'    => Hash::make('admin123'), // 👈 cambia la contraseña si quieres
                'rol'         => 'admin',
                'sucursal_id' => $sucursalMatriz->id,
                'activo'      => true,
            ]
        );
    }
}
