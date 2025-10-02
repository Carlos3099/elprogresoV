<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Sucursal;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'tipo' => 'sometimes|in:diario,semanal',
            'fecha' => 'sometimes|date',
            'sucursal_id' => 'sometimes|nullable|exists:sucursales,id'
        ]);
        
        $tipo = $validated['tipo'] ?? 'diario';
        $fecha = $validated['fecha'] ?? now()->format('Y-m-d');
        $sucursal_id = $validated['sucursal_id'] ?? null;
        
        // Obtener lista de sucursales
        $sucursales = Sucursal::orderBy('nombre')->get();
        
        // Si no es administrador, usar su sucursal asignada
        if (Auth::user()->rol !== 'admin') {
            $sucursal_id = Auth::user()->sucursal_id;
        }
        
        if ($tipo === 'diario') {
            $datos = $this->obtenerReporteDiario($fecha, $sucursal_id);
        } else {
            $datos = $this->obtenerReporteSemanal($fecha, $sucursal_id);
        }
        
        return view('reportes.index', compact('tipo', 'fecha', 'datos', 'sucursal_id', 'sucursales'));
    }
    
    private function obtenerReporteDiario($fecha, $sucursal_id = null)
    {
        $ventasQuery = DB::table('ventas');
        
        // Filtrar por sucursal si está especificada
        if ($sucursal_id) {
            $ventasQuery->where('sucursal_id', $sucursal_id);
        }
        
        $ventas = $ventasQuery
            ->select(
                DB::raw('COUNT(*) as total_ventas'),
                DB::raw('SUM(total) as monto_total'),
                DB::raw('AVG(total) as promedio_venta'),
                DB::raw('SUM(subtotal) as subtotal_total'),
                DB::raw('SUM(descuento) as descuento_total'),
                DB::raw('SUM(impuestos) as impuestos_total')
            )
            ->whereDate('fecha', $fecha)
            ->first();
        
        // Consulta para artículos vendidos con filtro de sucursal
        $articulosQuery = DB::table('venta_detalles')
            ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id');
            
        if ($sucursal_id) {
            $articulosQuery->where('ventas.sucursal_id', $sucursal_id);
        }
        
        $articulos = $articulosQuery
            ->select(
                DB::raw('SUM(venta_detalles.cantidad) as total_articulos'),
                DB::raw('COUNT(DISTINCT venta_detalles.producto_id) as productos_diferentes')
            )
            ->whereDate('ventas.fecha', $fecha)
            ->first();
        
        // Consulta para métodos de pago con filtro de sucursal
        $metodosPagoQuery = DB::table('pagos')
            ->join('ventas', 'pagos.venta_id', '=', 'ventas.id');
            
        if ($sucursal_id) {
            $metodosPagoQuery->where('ventas.sucursal_id', $sucursal_id);
        }
        
        $metodosPago = $metodosPagoQuery
            ->select(
                'pagos.metodo_pago',
                DB::raw('SUM(pagos.monto) as total_metodo')
            )
            ->where('pagos.estado', 'completado')
            ->whereDate('ventas.fecha', $fecha)
            ->groupBy('pagos.metodo_pago')
            ->get();
        
        // Consulta para transferencias con filtro de sucursal
        $transferenciasQuery = DB::table('pagos')
            ->join('ventas', 'pagos.venta_id', '=', 'ventas.id');
            
        if ($sucursal_id) {
            $transferenciasQuery->where('ventas.sucursal_id', $sucursal_id);
        }
        
        $transferencias = $transferenciasQuery
            ->select(
                'pagos.destinatario_transferencia',
                DB::raw('SUM(pagos.monto) as total_transferencia')
            )
            ->where('pagos.metodo_pago', 'transferencia')
            ->where('pagos.estado', 'completado')
            ->whereDate('ventas.fecha', $fecha)
            ->whereNotNull('pagos.destinatario_transferencia')
            ->groupBy('pagos.destinatario_transferencia')
            ->get();
        
        // Gastos con filtro de sucursal
        try {
            $gastosQuery = DB::table('gastos');
            
            if ($sucursal_id) {
                $gastosQuery->where('sucursal_id', $sucursal_id);
            }
            
            $gastos = $gastosQuery
                ->select(DB::raw('SUM(monto) as total_gastos'))
                ->whereDate('fecha', $fecha)
                ->first();
            $gastosTotal = $gastos->total_gastos ?? 0;
        } catch (\Exception $e) {
            $gastosTotal = 0;
        }
        
        // Consulta para ventas por proveedor con filtro de sucursal
        $ventasPorProveedorQuery = DB::table('venta_detalles')
            ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
            ->join('productos', 'venta_detalles.producto_id', '=', 'productos.id');
            
        if ($sucursal_id) {
            $ventasPorProveedorQuery->where('ventas.sucursal_id', $sucursal_id);
        }
        
        $ventasPorProveedor = $ventasPorProveedorQuery
            ->select(
                'productos.proveedor',
                DB::raw('SUM(venta_detalles.cantidad) as total_productos'),
                DB::raw('SUM(venta_detalles.precio_unitario * venta_detalles.cantidad) as monto_total')
            )
            ->whereDate('ventas.fecha', $fecha)
            ->groupBy('productos.proveedor')
            ->get();
        
        // Organizar los datos por proveedor
        $proveedores = [
            'Ethan' => ['productos' => 0, 'monto' => 0],
            'Karen' => ['productos' => 0, 'monto' => 0]
        ];
        
        foreach ($ventasPorProveedor as $venta) {
            if (isset($proveedores[$venta->proveedor])) {
                $proveedores[$venta->proveedor]['productos'] = $venta->total_productos;
                $proveedores[$venta->proveedor]['monto'] = $venta->monto_total;
            }
        }
        
        return [
            'ventas' => $ventas,
            'articulos' => $articulos,
            'metodosPago' => $metodosPago,
            'transferencias' => $transferencias,
            'gastos' => $gastosTotal,
            'proveedores' => $proveedores
        ];
    }
    
    private function obtenerReporteSemanal($fecha, $sucursal_id = null)
    {
        $fechaInicio = Carbon::parse($fecha);
        $fechaFin = $fechaInicio->copy()->addDays(6);

        $ventasPorDiaQuery = DB::table('ventas');
        
        // Filtrar por sucursal si está especificada
        if ($sucursal_id) {
            $ventasPorDiaQuery->where('sucursal_id', $sucursal_id);
        }
        
        if (config('database.default') === 'mysql') {
            $ventasPorDia = $ventasPorDiaQuery
                ->select(
                    DB::raw('COUNT(*) as total_ventas'),
                    DB::raw('SUM(total) as monto_total'),
                    DB::raw('AVG(total) as promedio_venta'),
                    DB::raw('DATE(fecha) as fecha_venta'),
                    DB::raw('ANY_VALUE(DAYNAME(fecha)) as dia_semana')
                )
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->groupBy(DB::raw('DATE(fecha)'))
                ->orderBy('fecha_venta')
                ->get();
        } else {
            $ventasPorDia = $ventasPorDiaQuery
                ->select(
                    DB::raw('COUNT(*) as total_ventas'),
                    DB::raw('SUM(total) as monto_total'),
                    DB::raw('AVG(total) as promedio_venta'),
                    DB::raw('DATE(fecha) as fecha_venta')
                )
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->groupBy(DB::raw('DATE(fecha)'))
                ->orderBy('fecha_venta')
                ->get()
                ->map(function ($item) {
                    $carbonDate = Carbon::parse($item->fecha_venta);
                    $item->dia_semana = $carbonDate->dayName;
                    return $item;
                });
        }

        // Consulta para ventas por proveedor con filtro de sucursal
        $ventasPorProveedorQuery = DB::table('venta_detalles')
            ->join('ventas', 'venta_detalles.venta_id', '=', 'ventas.id')
            ->join('productos', 'venta_detalles.producto_id', '=', 'productos.id');
            
        if ($sucursal_id) {
            $ventasPorProveedorQuery->where('ventas.sucursal_id', $sucursal_id);
        }
        
        $ventasPorProveedor = $ventasPorProveedorQuery
            ->select(
                'productos.proveedor',
                DB::raw('SUM(venta_detalles.cantidad) as total_productos'),
                DB::raw('SUM(venta_detalles.precio_unitario * venta_detalles.cantidad) as monto_total')
            )
            ->whereBetween('ventas.fecha', [$fechaInicio, $fechaFin])
            ->groupBy('productos.proveedor')
            ->get();
        
        // Organizar los datos por proveedor
        $proveedores = [
            'Ethan' => ['productos' => 0, 'monto' => 0],
            'Karen' => ['productos' => 0, 'monto' => 0]
        ];
        
        foreach ($ventasPorProveedor as $venta) {
            if (isset($proveedores[$venta->proveedor])) {
                $proveedores[$venta->proveedor]['productos'] = $venta->total_productos;
                $proveedores[$venta->proveedor]['monto'] = $venta->monto_total;
            }
        }

        return [
            'ventas_por_dia' => $ventasPorDia,
            'proveedores' => $proveedores
        ];
    }
}