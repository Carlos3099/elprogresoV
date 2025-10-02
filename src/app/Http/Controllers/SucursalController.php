<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sucursal;

class SucursalController extends Controller
{
    public function index()
    {
        return response()->json(Sucursal::orderBy('nombre')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255|unique:sucursales,nombre',
            'direccion' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:50',
        ]);

        $sucursal = Sucursal::create($data);
        return response()->json($sucursal, 201);
    }
}
