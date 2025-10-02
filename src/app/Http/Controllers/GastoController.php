<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller; // ← Esto debe estar disponible

use App\Models\Gasto;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GastoController extends Controller
{


    public function index(Request $request)
    {
        // Filtros opcionales
        $categoria = $request->query('categoria');
        $fecha = $request->query('fecha');
        $fecha_desde = $request->query('fecha_desde');
        $fecha_hasta = $request->query('fecha_hasta');

        // Obtener la sucursal del usuario autenticado
        $sucursal_id = Auth::user()->sucursal_id;

        $gastos = Gasto::with(['sucursal', 'usuario'])
            ->where('sucursal_id', $sucursal_id) // Solo gastos de la sucursal del usuario
            ->when($categoria, fn($q) => $q->where('categoria', $categoria))
            ->when($fecha, fn($q) => $q->whereDate('fecha', $fecha))
            ->when($fecha_desde, fn($q) => $q->whereDate('fecha', '>=', $fecha_desde))
            ->when($fecha_hasta, fn($q) => $q->whereDate('fecha', '<=', $fecha_hasta))
            ->latest('fecha')
            ->paginate(10)
            ->withQueryString();


        // Categorías disponibles
        $categorias = [
            'servicios' => 'Servicios',
            'renta' => 'Renta',
            'insumos' => 'Insumos',
            'nomina' => 'Nómina',
            'mantenimiento' => 'Mantenimiento',
            'otros' => 'Otros'
        ];

              // Fecha seleccionada: hoy por defecto
        $fecha = $request->input('fecha') ?: now()->toDateString();
        $categoria = $request->input('categoria');

        // Query base con filtros
        $query = Gasto::with('sucursal')
            ->when($categoria, fn ($q) => $q->where('categoria', $categoria))
            ->whereDate('fecha', $fecha)
            ->orderBy('fecha', 'desc');

        // Paginar resultados y preservar filtros en la URL
        $gastos = $query->paginate(15)->appends($request->query());

        // Total del día (sin afectar por la paginación)
        $totalDia = (clone $query)->sum('monto');

        return view('gastos.index', compact('gastos', 'categorias', 'totalDia', 'fecha'));
     
    }

    public function create()
    {
        $gasto = new Gasto();
        
        // Categorías disponibles
        $categorias = [
            'servicios' => 'Servicios',
            'renta' => 'Renta',
            'insumos' => 'Insumos',
            'nomina' => 'Nómina',
            'mantenimiento' => 'Mantenimiento',
            'otros' => 'Otros'
        ];

        // Métodos de pago disponibles
        $metodosPago = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta' => 'Tarjeta'
        ];

        return view('gastos.create', compact('gasto', 'categorias', 'metodosPago'));
    }

    public function store(Request $request)
    {
        // Validar datos (sin sucursal_id ni usuario_id en las reglas)
        $data = $request->validate([
            'fecha' => 'required|date',
            'categoria' => 'required|in:servicios,renta,insumos,nomina,mantenimiento,otros',
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Obtener la sucursal y usuario automáticamente del usuario autenticado
        $sucursal_id = Auth::user()->sucursal_id;
        $usuario_id = Auth::user()->id;

        // Verificar que el usuario tenga una sucursal asignada
        if (!$sucursal_id) {
            return redirect()->back()
                ->with('error', 'No tienes una sucursal asignada. Contacta al administrador.')
                ->withInput();
        }

        // Procesar comprobante si se subió
        $comprobanteUrl = null;
        if ($request->hasFile('comprobante')) {
            $comprobanteUrl = $request->file('comprobante')->store('comprobantes', 'public');
        }

        // Crear el gasto
        $gasto = Gasto::create([
            'sucursal_id' => $sucursal_id,
            'usuario_id' => $usuario_id,
            'fecha' => $data['fecha'],
            'categoria' => $data['categoria'],
            'descripcion' => $data['descripcion'],
            'monto' => $data['monto'],
            'metodo_pago' => $data['metodo_pago'],
            'comprobante_url' => $comprobanteUrl,
        ]);

        return redirect()
            ->route('gastos.index')
            ->with('success', 'Gasto registrado correctamente.');
    }

    public function show(Gasto $gasto)
    {
        // Verificar que el gasto pertenezca a la sucursal del usuario
        if ($gasto->sucursal_id !== Auth::user()->sucursal_id) {
            return redirect()
                ->route('gastos.index')
                ->with('error', 'No tienes permisos para ver este gasto.');
        }

        $gasto->load(['sucursal', 'usuario']);
        
        return view('gastos.show', compact('gasto'));
    }

    public function edit(Gasto $gasto)
    {
        // Verificar que el gasto pertenezca a la sucursal del usuario
        if ($gasto->sucursal_id !== Auth::user()->sucursal_id) {
            return redirect()
                ->route('gastos.index')
                ->with('error', 'No tienes permisos para editar este gasto.');
        }

        // Categorías disponibles
        $categorias = [
            'servicios' => 'Servicios',
            'renta' => 'Renta',
            'insumos' => 'Insumos',
            'nomina' => 'Nómina',
            'mantenimiento' => 'Mantenimiento',
            'otros' => 'Otros'
        ];

        // Métodos de pago disponibles
        $metodosPago = [
            'efectivo' => 'Efectivo',
            'transferencia' => 'Transferencia',
            'tarjeta' => 'Tarjeta'
        ];

        return view('gastos.edit', compact('gasto', 'categorias', 'metodosPago'));
    }

    public function update(Request $request, Gasto $gasto)
    {
        // Verificar que el gasto pertenezca a la sucursal del usuario
        if ($gasto->sucursal_id !== Auth::user()->sucursal_id) {
            return redirect()
                ->route('gastos.index')
                ->with('error', 'No tienes permisos para editar este gasto.');
        }

        // Validar datos
        $data = $request->validate([
            'fecha' => 'required|date',
            'categoria' => 'required|in:servicios,renta,insumos,nomina,mantenimiento,otros',
            'descripcion' => 'required|string|max:255',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|in:efectivo,transferencia,tarjeta',
            'comprobante' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Procesar comprobante si se subió uno nuevo
        if ($request->hasFile('comprobante')) {
            // Eliminar comprobante anterior si existe
            if ($gasto->comprobante_url) {
                Storage::disk('public')->delete($gasto->comprobante_url);
            }
            
            $comprobanteUrl = $request->file('comprobante')->store('comprobantes', 'public');
            $data['comprobante_url'] = $comprobanteUrl;
        }

        // Actualizar el gasto (no actualizamos sucursal_id ni usuario_id)
        $gasto->update($data);

        return redirect()
            ->route('gastos.index')
            ->with('success', 'Gasto actualizado correctamente.');
    }

    public function destroy(Gasto $gasto)
    {
        // Verificar que el gasto pertenezca a la sucursal del usuario
        if ($gasto->sucursal_id !== Auth::user()->sucursal_id) {
            return redirect()
                ->route('gastos.index')
                ->with('error', 'No tienes permisos para eliminar este gasto.');
        }

        // Eliminar comprobante si existe
        if ($gasto->comprobante_url) {
            Storage::disk('public')->delete($gasto->comprobante_url);
        }

        // Eliminar el gasto
        $gasto->delete();

        return redirect()
            ->route('gastos.index')
            ->with('success', 'Gasto eliminado correctamente.');
    }

    public function downloadComprobante(Gasto $gasto)
    {
        // Verificar que el gasto pertenezca a la sucursal del usuario
        if ($gasto->sucursal_id !== Auth::user()->sucursal_id) {
            return redirect()
                ->route('gastos.index')
                ->with('error', 'No tienes permisos para acceder a este comprobante.');
        }

        if (!$gasto->comprobante_url) {
            return redirect()
                ->route('gastos.show', $gasto)
                ->with('error', 'No hay comprobante disponible para este gasto.');
        }

        if (!Storage::disk('public')->exists($gasto->comprobante_url)) {
            return redirect()
                ->route('gastos.show', $gasto)
                ->with('error', 'El archivo del comprobante no existe.');
        }

       return response()->download(Storage::disk('public')->path($gasto->comprobante_url));
    }
}