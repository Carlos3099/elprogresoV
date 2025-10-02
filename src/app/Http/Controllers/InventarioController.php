<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Existencia;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $activo = $request->query('activo');
        
        // Obtener existencias de la sucursal del usuario con información del producto
        $query = Existencia::with(['producto', 'sucursal'])
            ->where('sucursal_id', Auth::user()->sucursal_id);

        if ($search) {
            $query->whereHas('producto', function($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if ($activo !== null) {
            $query->whereHas('producto', function($q) use ($activo) {
                $q->where('activo', $activo);
            });
        }

        $existencias = $query->orderBy('stock_actual', 'desc')->paginate(10);
        
        return view('inventario.index', compact('existencias'));
    }

    public function show(Existencia $existencia)
    {
        // Verificar que la existencia pertenezca a la sucursal del usuario
        if ($existencia->sucursal_id != Auth::user()->sucursal_id) {
            abort(403, 'No tienes acceso a este inventario');
        }
        
        return view('inventario.show', compact('existencia'));
    }

    public function edit(Existencia $existencia)
    {
        // Verificar que la existencia pertenezca a la sucursal del usuario
        if ($existencia->sucursal_id != Auth::user()->sucursal_id) {
            abort(403, 'No tienes acceso a este inventario');
        }
        
        return view('inventario.edit', compact('existencia'));
    }

    public function update(Request $request, Existencia $existencia)
    {
        // Verificar que la existencia pertenezca a la sucursal del usuario
        if ($existencia->sucursal_id != Auth::user()->sucursal_id) {
            abort(403, 'No tienes acceso a este inventario');
        }

        $data = $request->validate([
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0'
        ]);

        $existencia->update($data);
        
        return redirect()->route('inventario.index')
            ->with('success', 'Inventario actualizado exitosamente.');
    }

    // ELIMINAR método create() ya que no se crean existencias directamente
    // ELIMINAR método store() ya que no se crean existencias directamente
    // ELIMINAR método destroy() ya que no se eliminan existencias directamente
}