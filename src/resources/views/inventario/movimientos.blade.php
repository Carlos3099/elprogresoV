@extends('layouts.app')

@section('title', 'Movimientos - ' . $producto->nombre)

@section('content')
<div class="min-h-screen bg-gray-50 p-6">
  <div class="max-w-5xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-gray-200">
    
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <div>
        <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
          🕓 Historial de Movimientos
        </h2>
        <p class="text-sm text-gray-600 mt-1">
          Producto: <span class="font-medium text-blue-700">{{ $producto->nombre }}</span>
        </p>
      </div>
      <a href="{{ route('inventario.index') }}" 
         class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-lg text-sm shadow transition">
        ← Volver al Inventario
      </a>
    </div>

    <!-- Resumen superior -->
    @php
      $totalEntradas = $producto->movimientos->where('tipo', 'entrada')->sum('cantidad');
      $totalSalidas = $producto->movimientos->where('tipo', 'salida')->sum('cantidad');
      $totalMermas = $producto->movimientos->where('tipo', 'merma')->sum('cantidad');
    @endphp

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
      <div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
        <p class="text-sm text-green-800 font-medium">Entradas Totales</p>
        <h4 class="text-2xl font-bold text-green-700 mt-1">{{ $totalEntradas }}</h4>
      </div>
      <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center">
        <p class="text-sm text-red-800 font-medium">Salidas Totales</p>
        <h4 class="text-2xl font-bold text-red-700 mt-1">{{ $totalSalidas }}</h4>
      </div>
      <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center">
        <p class="text-sm text-gray-800 font-medium">Mermas</p>
        <h4 class="text-2xl font-bold text-gray-700 mt-1">{{ $totalMermas }}</h4>
      </div>
    </div>

    <!-- Tabla de movimientos -->
    <div class="overflow-x-auto">
      <table class="min-w-full border border-gray-200 text-sm rounded-lg overflow-hidden">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
          <tr>
            <th class="px-4 py-3 text-left">Tipo</th>
            <th class="px-4 py-3 text-left">Cantidad</th>
            <th class="px-4 py-3 text-left">Motivo</th>
            <th class="px-4 py-3 text-left">Usuario</th>
            <th class="px-4 py-3 text-left">Fecha</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($producto->movimientos as $movimiento)
            @php
              $color = match($movimiento->tipo) {
                'entrada' => 'text-green-700 bg-green-100',
                'salida' => 'text-red-700 bg-red-100',
                'merma' => 'text-gray-700 bg-gray-100',
                default => 'text-blue-700 bg-blue-100',
              };
            @endphp

            <tr class="hover:bg-gray-50 transition">
              <td class="px-4 py-3">
                <span class="{{ $color }} px-3 py-1 rounded-full text-xs font-semibold">
                  {{ ucfirst($movimiento->tipo) }}
                </span>
              </td>
              <td class="px-4 py-3 font-semibold text-gray-800">{{ $movimiento->cantidad }}</td>
              <td class="px-4 py-3 text-gray-600">{{ $movimiento->motivo }}</td>
              <td class="px-4 py-3 text-gray-600">{{ $movimiento->usuario->name ?? '—' }}</td>
              <td class="px-4 py-3 text-gray-500">{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                No hay movimientos registrados para este producto.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>
</div>
@endsection
