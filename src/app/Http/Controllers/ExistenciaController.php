<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Existencia;

class ExistenciaController extends Controller
{
    public function registrar(Request $request)
    {
        $data = $request->validate([
            'sucursal_id' => 'required|exists:sucursales,id',
            'producto_id' => 'required|exists:productos,id',
            'stock_inicial' => 'nullable|integer|min:0',
            'stock_minimo' => 'nullable|integer|min:0',
        ]);

        $existencia = Existencia::firstOrCreate(
            ['producto_id' => $data['producto_id'], 'sucursal_id' => $data['sucursal_id']],
            ['stock_actual' => $data['stock_inicial'] ?? 0, 'stock_minimo' => $data['stock_minimo'] ?? 0]
        );

        return response()->json($existencia, 201);
    }
}
