
@extends('layouts.app')

@section('title', 'Empleados - CRM')

@section('page-title', 'Gestión de Empleados')

@section('content')
<div class="bg-white p-3 md:p-6 rounded-lg shadow">
    <!-- Encabezado con botones -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4 md:mb-6">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2 mb-2 md:mb-0">
            <i class="fas fa-users mr-3 text-blue-500"></i>
            Empleados
        </h3>
        <div>
            <a href="{{ route('empleados.create') }}" 
               class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 md:px-4 md:py-2 rounded-lg flex items-center text-sm md:text-base 
                      transform transition duration-200 hover:scale-105">
                <img src="{{ asset('_Iconos/_Default/Icon_Add.svg') }}" 
                     alt="Agregar Empleado" 
                     class="w-6 h-6 mr-2">
                Nuevo Empleado
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-gray-50 p-3 md:p-4 rounded-lg mb-4 md:mb-6">
        <form method="GET" action="{{ route('empleados.index') }}" class="space-y-2 md:space-y-0 md:flex md:gap-4 md:items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       class="w-full p-2 border rounded-md text-sm" placeholder="Nombre, email o rol">
            </div>

            <div class="flex gap-2 pt-2 md:pt-0">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-2 rounded-lg flex items-center text-sm flex-1 justify-center">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
                <a href="{{ route('empleados.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-2 rounded-lg flex items-center text-sm flex-1 justify-center">
                    <i class="fas fa-times mr-1"></i> Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabla de empleados optimizada para móviles -->
    <div class="overflow-x-auto">
        <!-- Vista para móviles (tarjetas) -->
        <div class="md:hidden space-y-3">
            @forelse($empleados as $empleado)
                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="font-medium text-gray-900">{{ $empleado->nombre }}</div>
                            <div class="text-sm text-gray-500">{{ $empleado->email }}</div>
                        </div>
                        <div class="text-right">
                            <span class="font-medium text-blue-600 capitalize">{{ $empleado->rol }}</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-2">
                        <span class="px-2 py-1 text-xs font-medium rounded-full {{ $empleado->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $empleado->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                        <span class="text-xs text-gray-500">
                            Creado: {{ $empleado->created_at->format('d/m/Y') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                        <div class="flex space-x-2">
                            <a href="{{ route('empleados.edit', $empleado) }}" class="text-blue-500 hover:text-blue-700 p-1" title="Editar">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <form action="{{ route('empleados.destroy', $empleado) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este empleado?')">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-6 text-gray-500">
                    No se encontraron empleados.
                </div>
            @endforelse
        </div>

        <!-- Vista para desktop (tabla) -->
        <table class="min-w-full bg-white border border-gray-200 hidden md:table">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Creación</th>
                    <th class="px-4 py-2 border text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($empleados as $empleado)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 border font-medium">{{ $empleado->nombre }}</td>
                        <td class="px-4 py-3 border">{{ $empleado->email }}</td>
                        <td class="px-4 py-3 border capitalize">{{ $empleado->rol }}</td>
                        <td class="px-4 py-3 border">
                            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $empleado->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $empleado->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 border text-sm">{{ $empleado->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 border">
                            <div class="flex space-x-2">
                                <a href="{{ route('empleados.edit', $empleado) }}" class="text-blue-500 hover:text-blue-700 p-1" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('empleados.destroy', $empleado) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 p-1" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este empleado?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 border text-center text-gray-500">
                            No se encontraron empleados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación responsive -->
    <div class="mt-4 md:mt-6">
        {{ $empleados->links() }}
    </div>
</div>
@endsection
