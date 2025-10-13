@extends('layouts.app')

@section('title', 'Inventario - El Progreso')

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
  <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-[75%_25%] gap-6">

    <!-- ================== COLUMNA IZQUIERDA ================== -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
      <!-- Encabezado -->
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-xl font-semibold flex items-center gap-2 text-gray-800">
          📦 Inventario
        </h2>
        <a href="{{ route('productos.create') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg text-sm shadow transition">
          + Producto nuevo
        </a>
      </div>

      <!-- Tabla -->
      <div class="overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden text-sm">
          <thead class="bg-gray-50 text-gray-700 uppercase text-xs">
            <tr>
              <th class="px-4 py-3 text-left">Producto</th>
              <th class="px-4 py-3 text-center text-green-600">Entradas</th>
              <th class="px-4 py-3 text-center text-red-600">Salidas</th>
              <th class="px-4 py-3 text-center text-blue-600">Stock Actual</th>
              <th class="px-4 py-3 text-center">Estado</th>
              <th class="px-4 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">

            @forelse($existencias as $existencia)
              @php
                $producto = $existencia->producto;
                $estado = $existencia->stock_actual > $existencia->stock_minimo * 1.5 ? 'Alto' :
                          ($existencia->stock_actual > $existencia->stock_minimo ? 'Medio' : 'Bajo');
                $color = match($estado) {
                  'Alto' => 'bg-green-100 text-green-700',
                  'Medio' => 'bg-yellow-100 text-yellow-700',
                  'Bajo' => 'bg-red-100 text-red-700',
                };
              @endphp

              <tr class="hover:bg-gray-50 transition text-center">
                <form action="{{ route('inventario.update', $existencia) }}" method="POST" class="contents">
                  @csrf
                  @method('PUT')

                  <!-- Producto -->
                  <td class="px-4 py-3 font-medium text-gray-800 text-left">
                    📦 {{ $producto->nombre }}
                  </td>

                  <!-- Entradas -->
                  <td class="px-4 py-3">
                    <input type="number" name="entrada" value="0"
                      class="w-20 mx-auto text-center border border-green-300 rounded-md text-sm p-1 focus:ring-green-500 focus:border-green-500 text-green-700">
                  </td>

                  <!-- Salidas -->
                  <td class="px-4 py-3">
                    <input type="number" name="salida" value="0"
                      class="w-20 mx-auto text-center border border-red-300 rounded-md text-sm p-1 focus:ring-red-500 focus:border-red-500 text-red-700">
                  </td>

                  <!-- Stock Actual -->
                  <td class="px-4 py-3">
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold inline-block min-w-[55px]">
                      {{ $existencia->stock_actual }}
                    </span>
                  </td>

                  <!-- Estado -->
                  <td class="px-4 py-3">
                    <span class="{{ $color }} px-3 py-1 rounded-full text-xs font-semibold inline-block min-w-[60px]">
                      {{ $estado }}
                    </span>
                  </td>

                  <!-- Botones -->
                  <td class="px-4 py-3 flex justify-center gap-2">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-xs font-medium shadow transition flex items-center gap-1">
                      💾 <span>Guardar</span>
                    </button>
                    <a href="{{ route('inventario.movimientos', $existencia->producto_id) }}"
                       class="bg-gray-700 hover:bg-gray-800 text-white px-3 py-1 rounded-md text-xs font-medium shadow transition flex items-center gap-1">
                      🕓 <span>Ver</span>
                    </a>
                  </td>
                </form>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-4 py-3 text-center text-gray-500">
                  No hay productos registrados.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div class="mt-4">
        {{ $existencias->links() }}
      </div>
    </div>

    <!-- ================== COLUMNA DERECHA (REDUCIDA) ================== -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-5 h-fit w-full">
      <h3 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">📊 Reportes y Totales</h3>

      <!-- Ventas -->
      <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-3 text-center">
        <p class="text-sm font-medium text-green-800">💵 Ventas del Día</p>
        <h4 class="text-3xl font-bold text-green-700 mt-1">{{ number_format($ventasDia, 0) }}</h4>
      </div>

      <!-- Merma -->
      <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-3 text-center">
        <p class="text-sm font-medium text-red-800">📉 Merma del Día</p>
        <h4 class="text-3xl font-bold text-red-700 mt-1">{{ number_format($mermaDia, 0) }}</h4>
      </div>

      <!-- Stock agregado -->
      <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-3 text-center">
        <p class="text-sm font-medium text-blue-800">📦 Stock Agregado</p>
        <h4 class="text-3xl font-bold text-blue-700 mt-1">{{ number_format($stockAgregado, 0) }}</h4>
      </div>

      <!-- Acumulado semanal -->
      <div class="bg-orange-50 border border-orange-200 rounded-xl p-4 text-center">
        <p class="text-sm font-medium text-orange-800">📅 Acumulado Semana</p>
        <h4 class="text-3xl font-bold text-orange-700 mt-1">{{ number_format($acumuladoSemana, 0) }}</h4>
      </div>
    </div>
  </div>
</div>
@endsection
