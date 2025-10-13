<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Existencia;
use App\Models\Producto;
use App\Models\InventarioMovimiento;
use Illuminate\Support\Facades\Auth;

class InventarioController extends Controller
{
    /**
     * Mostrar el listado del inventario con reportes.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $activo = $request->query('activo');
        $sucursalId = Auth::user()->sucursal_id;

        // =============================
        // 📦 CONSULTA PRINCIPAL DE INVENTARIO
        // =============================
        $query = Existencia::with(['producto'])
            ->where('sucursal_id', $sucursalId);

        // 🔍 Filtro por búsqueda (SKU / nombre / descripción)
        if ($search) {
            $query->whereHas('producto', function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                    ->orWhere('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // 🟢 Filtro por estado (Activo / Inactivo)
        if ($activo !== null && $activo !== '') {
            $query->whereHas('producto', function ($q) use ($activo) {
                $q->where('activo', $activo);
            });
        }

        // 📊 Ordenar por stock actual
        $existencias = $query->orderBy('stock_actual', 'desc')->paginate(10);

        // =============================
        // 📊 DATOS PARA REPORTES (PANEL DERECHO)
        // =============================
        $ventasDia = InventarioMovimiento::where('tipo', 'salida')
            ->whereDate('created_at', now())
            ->where('sucursal_id', $sucursalId)
            ->sum('cantidad');

        $mermaDia = InventarioMovimiento::where('tipo', 'merma')
            ->whereDate('created_at', now())
            ->where('sucursal_id', $sucursalId)
            ->sum('cantidad');

        $stockAgregado = InventarioMovimiento::where('tipo', 'entrada')
            ->whereDate('created_at', now())
            ->where('sucursal_id', $sucursalId)
            ->sum('cantidad');

        $acumuladoSemana = InventarioMovimiento::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('sucursal_id', $sucursalId)
            ->sum('cantidad');

        // =============================
        // 📦 DEVOLVER VISTA
        // =============================
        return view('inventario.index', compact(
            'existencias',
            'ventasDia',
            'mermaDia',
            'stockAgregado',
            'acumuladoSemana'
        ));
    }

    /**
     * Mostrar detalle de una existencia específica.
     */
    public function show(Existencia $existencia)
    {
        if ($existencia->sucursal_id != Auth::user()->sucursal_id) {
            abort(403, 'No tienes acceso a este inventario');
        }

        return view('inventario.show', compact('existencia'));
    }

    /**
     * Editar una existencia específica.
     */
    public function edit(Existencia $existencia)
    {
        if ($existencia->sucursal_id != Auth::user()->sucursal_id) {
            abort(403, 'No tienes acceso a este inventario');
        }

        return view('inventario.edit', compact('existencia'));
    }

    /**
     * Actualizar el stock (entradas / salidas).
     */
    public function update(Request $request, Existencia $existencia)
    {
        // =============================
        // 🔒 VALIDACIÓN DE PERMISOS
        // =============================
        if ($existencia->sucursal_id != Auth::user()->sucursal_id) {
            abort(403, 'No tienes acceso a este inventario');
        }

        // =============================
        // 🧾 VALIDAR CAMPOS DEL FORMULARIO
        // =============================
        $data = $request->validate([
            'entrada' => 'nullable|integer|min:0',
            'salida' => 'nullable|integer|min:0',
        ]);

        $user = Auth::user();
        $entradas = $data['entrada'] ?? 0;
        $salidas = $data['salida'] ?? 0;

        // =============================
        // 📊 CALCULAR NUEVO STOCK
        // =============================
        $nuevoStock = $existencia->stock_actual + $entradas - $salidas;
        if ($nuevoStock < 0) $nuevoStock = 0;

        $existencia->update(['stock_actual' => $nuevoStock]);

        // =============================
        // 🧾 REGISTRAR MOVIMIENTOS EN HISTORIAL
        // =============================
        if ($entradas > 0) {
            InventarioMovimiento::create([
                'producto_id' => $existencia->producto_id,
                'sucursal_id' => $existencia->sucursal_id,
                'usuario_id' => $user->id,
                'tipo' => 'entrada',
                'cantidad' => $entradas,
                'motivo' => 'Entrada manual desde inventario',
                'referencia_tipo' => 'existencia',
                'referencia_id' => $existencia->id,
            ]);
        }

        if ($salidas > 0) {
            InventarioMovimiento::create([
                'producto_id' => $existencia->producto_id,
                'sucursal_id' => $existencia->sucursal_id,
                'usuario_id' => $user->id,
                'tipo' => 'salida',
                'cantidad' => $salidas,
                'motivo' => 'Salida manual desde inventario',
                'referencia_tipo' => 'existencia',
                'referencia_id' => $existencia->id,
            ]);
        }

        // =============================
        // ✅ RESPUESTA
        // =============================
        return redirect()->route('inventario.index')->with('success', 'Inventario actualizado exitosamente.');
    }

    /**
     * 🕓 Mostrar historial de movimientos por producto.
     */
    public function movimientos($productoId)
    {
        $producto = Producto::with(['movimientos' => function ($q) {
            $q->orderBy('created_at', 'desc');
        }])->findOrFail($productoId);

        return view('inventario.movimientos', compact('producto'));
    }
}
