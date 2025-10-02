<?php
// [file name]: ProductoController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Existencia;
use App\Models\InventarioMovimiento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $query = Producto::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('nombre', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        $productos = $query->orderBy('nombre')->paginate(10);
        return view('productos.index', compact('productos'));
    }

    public function create()
    {
        $producto = new Producto();
        return view('productos.create', compact('producto'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'activo' => 'sometimes|boolean'
        ]);

        // Generar SKU provisional (sin ID aún)
        $skuProvisional = $this->generarSKUProvisional($data['nombre']);
        
        $data['sku'] = $skuProvisional;
        $data['activo'] = 1;
        
        // Usar transacción para asegurar la consistencia
        DB::beginTransaction();
        
        try {
            $producto = Producto::create($data);
            
            // Ahora que tenemos el ID, actualizamos el SKU con el formato correcto
            $skuDefinitivo = $this->generarSKUDefinitivo($data['nombre'], $producto->id);
            $producto->update(['sku' => $skuDefinitivo]);
            
            // Obtener el usuario autenticado
            $user = Auth::user();
            
            // Validar que el usuario tenga una sucursal asignada
            if (!$user->sucursal_id) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'No se puede crear el producto. Usuario no tiene una sucursal asignada.');
            }
            
            $sucursalId = $user->sucursal_id;
            
            // Crear existencia automáticamente
            $existencia = Existencia::create([
                'producto_id' => $producto->id,
                'sucursal_id' => $sucursalId,
                'stock_actual' => 0,
                'stock_minimo' => 10
            ]);
            
            // Registrar movimiento de inventario
            InventarioMovimiento::create([
                'producto_id' => $producto->id,
                'sucursal_id' => $sucursalId,
                'usuario_id' => $user->id,
                'tipo' => 'entrada',
                'cantidad' => 50,
                'motivo' => 'Creación de producto - Stock inicial',
                'referencia_tipo' => 'producto',
                'referencia_id' => $producto->id
            ]);
            
            DB::commit();
            
            return redirect()->route('productos.index')
                ->with('success', 'Producto creado exitosamente. Se ha generado stock inicial automáticamente.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error al crear el producto: ' . $e->getMessage());
        }
    }

    // Función para generar SKU provisional (sin ID)
    private function generarSKUProvisional($nombre)
    {
        // Obtener las primeras 3 letras del nombre en mayúsculas
        $inicialesNombre = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nombre), 0, 3));
        
        // Obtener timestamp para hacerlo único provisionalmente
        $timestamp = time();
        
        // Combinar todo
        $sku = $inicialesNombre . substr($timestamp, -3);
        
        return $sku;
    }

    // Función para generar SKU definitivo con ID
    private function generarSKUDefinitivo($nombre, $id)
    {
        // Obtener las primeras 3 letras del nombre en mayúsculas
        $inicialesNombre = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $nombre), 0, 3));
        
        // Formatear el ID a 3 dígitos
        $idFormateado = str_pad($id, 3, '0', STR_PAD_LEFT);
        
        // Combinar todo
        $sku = $inicialesNombre . $idFormateado;
        
        return $sku;
    }

    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    public function update(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'sku' => 'required|string|max:100|unique:productos,sku,' . $producto->id,
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'activo' => 'sometimes|boolean'
        ]);

        $data['activo'] = $producto->activo;
        $producto->update($data);
        
        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy(Producto $producto)
    {
        // Primero eliminar las existencias relacionadas
        Existencia::where('producto_id', $producto->id)->delete();
        
        // También eliminar movimientos de inventario relacionados
        InventarioMovimiento::where('producto_id', $producto->id)->delete();
        
        // Ahora sí eliminar el producto
        $producto->delete();
        
        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente.');
    }
}