<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cambio;
use App\Models\CambioDetalle;
use App\Models\Existencia;
use App\Models\InventarioMovimiento;
use Carbon\Carbon;

class CambioController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'venta_original_id' => 'nullable|exists:ventas,id',
            'sucursal_id' => 'required|exists:sucursales,id',
            'usuario_id'  => 'required|exists:users,id',
            'cliente_id'  => 'nullable|exists:clientes,id',
            'fecha'       => 'nullable|date',
            'metodo_ajuste' => 'nullable|in:efectivo,transferencia,tarjeta,nota_credito',
            'referencia_pago' => 'nullable|string|max:255',
            'notas' => 'nullable|string|max:255',

            'items_devueltos' => 'array',
            'items_devueltos.*.producto_id' => 'required|exists:productos,id',
            'items_devueltos.*.cantidad' => 'required|integer|min:1',
            'items_devueltos.*.precio_unitario' => 'required|numeric|min:0',

            'items_entregados' => 'array',
            'items_entregados.*.producto_id' => 'required|exists:productos,id',
            'items_entregados.*.cantidad' => 'required|integer|min:1',
            'items_entregados.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $fecha = isset($data['fecha']) ? Carbon::parse($data['fecha']) : now();

        return DB::transaction(function () use ($data, $fecha) {

            $totalDev = 0;
            $totalEnt = 0;

            // Validar existencia y stock para entregados
            if (!empty($data['items_entregados'])) {
                foreach ($data['items_entregados'] as $item) {
                    $exist = Existencia::where('producto_id', $item['producto_id'])
                        ->where('sucursal_id', $data['sucursal_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$exist) abort(422, "Producto {$item['producto_id']} no está registrado en la sucursal.");
                    if ($exist->stock_actual < $item['cantidad']) abort(422, "Stock insuficiente para producto {$item['producto_id']}.");
                    $totalEnt += $item['cantidad'] * $item['precio_unitario'];
                }
            }

            if (!empty($data['items_devueltos'])) {
                foreach ($data['items_devueltos'] as $item) {
                    $totalDev += $item['cantidad'] * $item['precio_unitario'];
                }
            }

            $cambio = Cambio::create([
                'venta_original_id' => $data['venta_original_id'] ?? null,
                'sucursal_id' => $data['sucursal_id'],
                'usuario_id'  => $data['usuario_id'],
                'cliente_id'  => $data['cliente_id'] ?? null,
                'fecha'       => $fecha,
                'total_devuelto' => $totalDev,
                'total_entregado' => $totalEnt,
                'diferencia'  => $totalEnt - $totalDev,
                'metodo_ajuste' => $data['metodo_ajuste'] ?? null,
                'referencia_pago' => $data['referencia_pago'] ?? null,
                'notas' => $data['notas'] ?? null,
            ]);

            // DEVUELTOS (+stock)
            if (!empty($data['items_devueltos'])) {
                foreach ($data['items_devueltos'] as $item) {
                    CambioDetalle::create([
                        'cambio_id' => $cambio->id,
                        'producto_id' => $item['producto_id'],
                        'tipo' => 'devuelto',
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'total_linea' => $item['cantidad'] * $item['precio_unitario'],
                    ]);

                    $exist = Existencia::where('producto_id', $item['producto_id'])
                        ->where('sucursal_id', $data['sucursal_id'])
                        ->lockForUpdate()
                        ->first();

                    if (!$exist) {
                        // Si no está registrado aún, se crea registro de existencias con stock inicial = devuelto
                        $exist = Existencia::create([
                            'producto_id' => $item['producto_id'],
                            'sucursal_id' => $data['sucursal_id'],
                            'stock_actual' => 0,
                            'stock_minimo' => 0,
                        ]);
                    }

                    $exist->stock_actual += $item['cantidad'];
                    $exist->save();

                    InventarioMovimiento::create([
                        'producto_id' => $item['producto_id'],
                        'sucursal_id' => $data['sucursal_id'],
                        'usuario_id'  => $data['usuario_id'],
                        'tipo'        => 'cambio_devuelto',
                        'cantidad'    => $item['cantidad'],
                        'motivo'      => 'Cambio #' . $cambio->id,
                        'referencia_tipo' => 'cambio',
                        'referencia_id'   => $cambio->id,
                    ]);
                }
            }

            // ENTREGADOS (−stock)
            if (!empty($data['items_entregados'])) {
                for ($i = 0; $i < count($data['items_entregados']); $i++) {
                    $item = $data['items_entregados'][$i];

                    CambioDetalle::create([
                        'cambio_id' => $cambio->id,
                        'producto_id' => $item['producto_id'],
                        'tipo' => 'entregado',
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $item['precio_unitario'],
                        'total_linea' => $item['cantidad'] * $item['precio_unitario'],
                    ]);

                    $exist = Existencia::where('producto_id', $item['producto_id'])
                        ->where('sucursal_id', $data['sucursal_id'])
                        ->lockForUpdate()
                        ->first();

                    $exist->stock_actual -= $item['cantidad'];
                    $exist->save();

                    InventarioMovimiento::create([
                        'producto_id' => $item['producto_id'],
                        'sucursal_id' => $data['sucursal_id'],
                        'usuario_id'  => $data['usuario_id'],
                        'tipo'        => 'cambio_entregado',
                        'cantidad'    => $item['cantidad'],
                        'motivo'      => 'Cambio #' . $cambio->id,
                        'referencia_tipo' => 'cambio',
                        'referencia_id'   => $cambio->id,
                    ]);
                }
            }

            return response()->json($cambio->load('detalles'), 201);
        });
    }
}
