
@extends('layouts.app')

@section('title', 'Productos - CRM')

@section('page-title', 'Gestión de Productos')

@section('content')
<div class="bg-white p-3 md:p-6 rounded-lg shadow">
    <!-- Encabezado con botones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 md:mb-6">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2 mb-2 md:mb-0">
                   <i class="fas fa-cookie-bite mr-4 text-yellow-600"></i>
            Productos
        </h3>
<div>
    <a href="{{ route('productos.create') }}" 
       class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 md:px-4 md:py-2 rounded-lg flex items-center text-sm md:text-base 
              transform transition duration-200 hover:scale-105">

        <img src="{{ asset('_Iconos/_Default/Icon_Add.svg') }}" 
             alt="Agregar Producto" 
             class="w-6 h-6 mr-2">
        Nuevo Producto
    </a>
</div>

    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-3 md:p-4 rounded-lg mb-4 md:mb-6">
        <form method="GET" action="{{ route('productos.index') }}" class="space-y-2 md:space-y-0 md:flex md:gap-4 md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full p-2 border rounded-md text-sm" placeholder="SKU, nombre o descripción">
            </div>

            <!-- ELIMINAMOS EL FILTRO POR ESTADO -->
            <!-- <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                <select name="activo" class="w-full p-2 border rounded-md text-sm">
                    <option value="">Todos</option>
                    <option value="1" {{ request('activo') == '1' ? 'selected' : '' }}>Activos</option>
                    <option value="0" {{ request('activo') == '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
            </div> -->

            <div class="flex gap-2 pt-2 md:pt-0">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg flex items-center text-sm flex-1 justify-center">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
                <a href="{{ route('productos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg flex items-center text-sm flex-1 justify-center">
                    <i class="fas fa-times mr-1"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de productos optimizada para móviles -->
    <div class="overflow-x-auto">
        <!-- Vista para móviles (tarjetas) -->
        <div class="md:hidden space-y-3">
            @forelse($productos as $producto)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-medium text-gray-900">{{ $producto->nombre }}</div>
                            <div class="text-sm text-gray-500">{{ $producto->sku }}</div>
                        </div>
                        <div class="text-right">
                            <span class="font-medium text-green-600">${{ number_format($producto->precio, 2) }}</span>
                        </div>
                    </div>

                    <div class="text-sm text-gray-600 mb-2">{{ Str::limit($producto->descripcion, 80) }}</div>

                    <div class="flex items-center mb-2">
                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full {{ $producto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('productos.edit', $producto) }}" class="text-blue-500 hover:text-blue-700 p-1" title="Editar">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-gray-500">
                    No se encontraron productos.
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
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($productos as $producto)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 border">{{ $producto->sku }}</td>
                        <td class="px-4 py-3 border font-medium">{{ $producto->nombre }}</td>
                        <td class="px-4 py-3 border text-sm">{{ Str::limit($producto->descripcion, 50) }}</td>
                        <td class="px-4 py-3 border font-medium text-green-600">${{ number_format($producto->precio, 2) }}</td>
                        <td class="px-4 py-3 border">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $producto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 border">
                            <div class="flex space-x-2">
                                <a href="{{ route('productos.edit', $producto) }}" class="text-blue-500 hover:text-blue-700 p-1" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('productos.destroy', $producto) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 border text-center text-gray-500">
                            No se encontraron productos.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación responsive -->
    <div class="mt-4 md:mt-6">
        {{ $productos->links() }}
    </div>
</div>
@endsection
