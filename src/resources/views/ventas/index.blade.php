@extends('layouts.app')

@section('title', 'Ventas - CRM')

@section('page-title', 'Gestión de Ventas')

@section('content')
<div class="bg-white p-4 md:p-6 rounded-lg shadow">
    <!-- Encabezado con botones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 md:mb-6">
    <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2 mb-2 md:mb-0">
        <i class="fas fa-cash-register mr-3 text-red-500"></i>
        Ventas
    </h3>       
        <div>
            <a href="{{ route('ventas.create') }}" 
            class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 md:px-4 md:py-2 rounded-lg flex items-center text-sm md:text-base 
                    transform transition duration-200 hover:scale-105">

                <img src="{{ asset('_Iconos/_Default/Icon_Add.svg') }}" 
                    alt="Nueva Venta" 
                    class="w-6 h-6 mr-2">
                Nueva Venta
            </a>
        </div>
    </div>

    <!-- Filtros simplificados -->
    <div class="bg-gray-50 p-3 md:p-4 rounded-lg mb-4 md:mb-6">
        <h4 class="font-medium text-gray-700 mb-2 md:mb-3">Buscar ventas por fecha</h4>
        <form method="GET" action="{{ route('ventas.index') }}" class="flex flex-col md:flex-row gap-2 md:gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha específica</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="w-full p-2 border rounded-md">
            </div>
            <div class="flex items-end gap-2 mt-2 md:mt-0">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg flex items-center text-sm">
                    <i class="fas fa-search mr-1"></i> Buscar
                </button>
                <a href="{{ route('ventas.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg flex items-center text-sm">
                    <i class="fas fa-times mr-1"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de ventas responsive para móviles -->
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-200">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-3 py-2 md:px-4 md:py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-3 py-2 md:px-4 md:py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-3 py-2 md:px-4 md:py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-3 py-2 md:px-4 md:py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Método de Pago</th>
                    <th class="px-3 py-2 md:px-4 md:py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($ventas as $venta)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 md:px-4 md:py-3 border">
                            <div class="font-medium">{{ $venta->id }}</div>
                            <div class="text-xs text-gray-500 md:hidden">
                                {{ $venta->cliente ? $venta->cliente->nombre : 'Cliente anónimo' }}
                            </div>
                        </td>
                        <td class="px-3 py-2 md:px-4 md:py-3 border">{{ $venta->fecha->format('d/m/Y') }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 border">${{ number_format($venta->total, 2) }}</td>
                        <td class="px-3 py-2 md:px-4 md:py-3 border">
                            @if($venta->metodo_pago === 'multipago')
                                <!-- Para pagos múltiples -->
                                <div class="text-sm font-medium text-purple-600">Múltiples métodos</div>
                                <div class="text-xs text-gray-500 mt-1">
                                    @foreach($venta->pagos as $pago)
                                        <div class="mb-1">
                                            {{ ucfirst($pago->metodo_pago) }}: ${{ number_format($pago->monto, 2) }}
                                            @if($pago->metodo_pago === 'transferencia' && $pago->destinatario_transferencia)
                                                <span class="text-blue-600 ml-1">(a {{ $pago->destinatario_transferencia }})</span>
                                            @endif
                                            @if($pago->referencia_pago)
                                                <div class="text-gray-400 text-xs ml-2">Ref: {{ $pago->referencia_pago }}</div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Para pagos únicos -->
                                <div class="text-sm font-medium">
                                    {{ ucfirst($venta->metodo_pago) }}
                                </div>
                                <div class="text-xs text-gray-500 mt-1">
                                    @if($venta->metodo_pago === 'transferencia' && $venta->pagos->isNotEmpty() && $venta->pagos->first()->destinatario_transferencia)
                                        <div class="text-blue-600">A: {{ $venta->pagos->first()->destinatario_transferencia }}</div>
                                    @endif
                                    @if($venta->referencia_pago)
                                        <div class="text-gray-400">Ref: {{ $venta->referencia_pago }}</div>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-3 py-2 md:px-4 md:py-3 border">
                            <div class="flex space-x-1 md:space-x-2">
                                <!-- Botón para imprimir ticket -->
                                <a href="{{ route('ventas.ticket', $venta) }}" 
                                   class="text-purple-500 hover:text-purple-700 p-1" 
                                   title="Imprimir Ticket"
                                   target="_blank">
                                    <i class="fas fa-receipt text-sm md:text-base"></i>
                                </a>
                                
                                <a href="{{ route('ventas.show', $venta) }}" class="text-blue-500 hover:text-blue-700 p-1" title="Ver">
                                    <i class="fas fa-eye text-sm md:text-base"></i>
                                </a>
                                
                                <!-- Botón de editar - solo para administradores -->
                                @if(Auth::user()->rol === 'admin')
                                    <a href="{{ route('ventas.edit', $venta) }}" class="text-green-500 hover:text-green-700 p-1" title="Editar">
                                        <i class="fas fa-edit text-sm md:text-base"></i>
                                    </a>
                                    <form action="{{ route('ventas.destroy', $venta) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar esta venta?')">
                                            <i class="fas fa-trash text-sm md:text-base"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Mostrar iconos deshabilitados para usuarios no administradores -->
                                    <span class="text-gray-400 p-1 cursor-not-allowed" title="Solo administradores pueden editar">
                                        <i class="fas fa-edit text-sm md:text-base"></i>
                                    </span>
                                    <span class="text-gray-400 p-1 cursor-not-allowed" title="Solo administradores pueden eliminar">
                                        <i class="fas fa-trash text-sm md:text-base"></i>
                                    </span>
                                @endif
                            </div>
                            <!-- Información adicional para móviles -->
                            <div class="mt-1 text-xs text-gray-600 md:hidden">
                                <div>Cliente: {{ $venta->cliente ? $venta->cliente->nombre : 'Cliente anónimo' }}</div>
                                <div>Pago: 
                                    @if($venta->metodo_pago === 'multipago')
                                        Múltiples métodos
                                        @foreach($venta->pagos as $pago)
                                            <br>- {{ ucfirst($pago->metodo_pago) }}: ${{ number_format($pago->monto, 2) }}
                                            @if($pago->metodo_pago === 'transferencia' && $pago->destinatario_transferencia)
                                                (a {{ $pago->destinatario_transferencia }})
                                            @endif
                                        @endforeach
                                    @else
                                        {{ ucfirst($venta->metodo_pago) }}
                                        @if($venta->metodo_pago === 'transferencia' && $venta->pagos->isNotEmpty() && $venta->pagos->first()->destinatario_transferencia)
                                            (a {{ $venta->pagos->first()->destinatario_transferencia }})
                                        @endif
                                    @endif
                                </div>
                                <!-- Botón de imprimir ticket para móviles -->
                                <div class="mt-2">
                                    <a href="{{ route('ventas.ticket', $venta) }}" 
                                       class="inline-flex items-center text-purple-600 hover:text-purple-800 text-xs"
                                       target="_blank">
                                        <i class="fas fa-receipt mr-1"></i> Imprimir Ticket
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-3 py-3 md:px-4 md:py-4 border text-center text-gray-500">
                            No se encontraron ventas con los filtros aplicados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación responsive -->
    <div class="mt-4 md:mt-6">
        {{ $ventas->links() }}
    </div>
</div>
@endsection