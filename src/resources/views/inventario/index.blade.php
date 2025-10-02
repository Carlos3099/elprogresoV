@extends('layouts.app')

@section('title', 'Inventario - CRM')

@section('page-title', 'Gestión de Inventario')

@section('content')
<div class="bg-white p-3 md:p-6 rounded-lg shadow">
    <!-- Encabezado con botones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 md:mb-6">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2 mb-2 md:mb-0">
            <i class="fas fa-box-open mr-3 text-purple-500"></i>
            Inventario - Sucursal: {{ Auth::user()->sucursal->nombre ?? 'N/A' }}
        </h3>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-3 md:p-4 rounded-lg mb-4 md:mb-6">
        <form method="GET" action="{{ route('inventario.index') }}" class="space-y-2 md:space-y-0 md:flex md:gap-4 md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full p-2 border rounded-md text-sm" placeholder="SKU, nombre o descripción">
            </div>

            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="activo" class="w-full p-2 border rounded-md text-sm">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div>

            <div class="flex gap-2 pt-2 md:pt-0">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg flex items-center text-sm flex-1 justify-center">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
                <a href="{{ route('inventario.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg flex items-center text-sm flex-1 justify-center">
                    <i class="fas fa-times mr-1"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de inventario optimizada para móviles -->
    <div class="overflow-x-auto">
        <!-- Vista para móviles (tarjetas) -->
        <div class="md:hidden space-y-3">
            @forelse($existencias as $existencia)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-medium text-gray-900">{{ $existencia->producto->nombre }}</div>
                            <div class="text-sm text-gray-500">{{ $existencia->producto->sku }}</div>
                        </div>
                        <div class="text-right">
                            <span class="font-medium text-green-600">${{ number_format($existencia->producto->precio, 2) }}</span>
                            <div class="text-sm text-gray-600 mt-1">Stock: {{ $existencia->stock_actual }}</div>
                            <div class="text-xs text-red-600">Mínimo: {{ $existencia->stock_minimo }}</div>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 mb-2">{{ Str::limit($existencia->producto->descripcion, 80) }}</div>

                    <div class="flex items-center mb-2">
                        <span class="text-xs text-gray-500">{{ $existencia->producto->proveedor }}</span>
                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $existencia->producto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $existencia->producto->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('inventario.edit', $existencia) }}" class="text-blue-500 hover:text-blue-700 p-1" title="Editar Stock">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-gray-500">
                    No se encontraron items en el inventario.
                </div>
            @endforelse
        </div>

        <!-- Vista para desktop (tabla) -->
        <table class="min-w-full bg-white border border-gray-200 hidden md:table">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Actual</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock Mínimo</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Proveedor</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($existencias as $existencia)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 border">{{ $existencia->producto->sku }}</td>
                        <td class="px-4 py-3 border font-medium">{{ $existencia->producto->nombre }}</td>
                        <td class="px-4 py-3 border text-sm">{{ Str::limit($existencia->producto->descripcion, 50) }}</td>
                        <td class="px-4 py-3 border font-medium text-green-600">${{ number_format($existencia->producto->precio, 2) }}</td>
                        <td class="px-4 py-3 border font-medium {{ $existencia->stock_actual > $existencia->stock_minimo ? 'text-blue-600' : 'text-red-600' }}">
                            {{ $existencia->stock_actual }}
                        </td>
                        <td class="px-4 py-3 border text-sm">{{ $existencia->stock_minimo }}</td>
                        <td class="px-4 py-3 border">{{ $existencia->producto->proveedor }}</td>
                        <td class="px-4 py-3 border">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $existencia->producto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $existencia->producto->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 border">
                            <div class="flex space-x-2">
                                <a href="{{ route('inventario.edit', $existencia) }}" class="text-blue-500 hover:text-blue-700 p-1" title="Editar Stock">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-4 border text-center text-gray-500">
                            No se encontraron items en el inventario.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación responsive -->
    <div class="mt-4 md:mt-6">
        {{ $existencias->links() }}
    </div>
</div>
@endsection