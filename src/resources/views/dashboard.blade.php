@extends('layouts.app') {{-- Usa tu layout principal --}}

@section('title', 'Panel de Control')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">

    <!-- Encabezado -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Panel de Control</h1>
        <p class="text-gray-600">Bienvenido, aquí puedes ver un resumen de tu sistema</p>
    </div>

    <!-- Tarjetas de métricas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Ventas -->
        <div class="bg-white shadow rounded-xl p-5 flex items-center">
            <div class="p-3 bg-red-100 rounded-lg">
                <i class="fas fa-cash-register text-red-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Ventas</h2>
                <p class="text-2xl font-bold text-gray-900">1,250</p>
            </div>
        </div>

        <!-- Gastos -->
        <div class="bg-white shadow rounded-xl p-5 flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
                <i class="fas fa-money-bill-wave text-green-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Gastos</h2>
                <p class="text-2xl font-bold text-gray-900">$18,400</p>
            </div>
        </div>

        <!-- Productos -->
        <div class="bg-white shadow rounded-xl p-5 flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
                <i class="fas fa-box-open text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Productos</h2>
                <p class="text-2xl font-bold text-gray-900">325</p>
            </div>
        </div>

        <!-- Inventario -->
        <div class="bg-white shadow rounded-xl p-5 flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
                <i class="fas fa-warehouse text-blue-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-lg font-semibold text-gray-700">Inventario</h2>
                <p class="text-2xl font-bold text-gray-900">4,500</p>
            </div>
        </div>
    </div>

    <!-- Sección de reportes -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tabla de últimas ventas -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Últimas Ventas</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 text-sm uppercase">
                        <th class="p-3">Cliente</th>
                        <th class="p-3">Producto</th>
                        <th class="p-3">Monto</th>
                        <th class="p-3">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="p-3">Juan Pérez</td>
                        <td class="p-3">Gorra Negra</td>
                        <td class="p-3">$450</td>
                        <td class="p-3">2025-08-25</td>
                    </tr>
                    <tr class="border-t">
                        <td class="p-3">María López</td>
                        <td class="p-3">Tenis Blancos</td>
                        <td class="p-3">$1,200</td>
                        <td class="p-3">2025-08-24</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Reporte gráfico -->
        <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Ingresos Mensuales</h2>
            <canvas id="chartIngresos"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartIngresos');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
            datasets: [{
                label: 'Ingresos',
                data: [12000, 15000, 18000, 14000, 19000, 22000],
                backgroundColor: 'rgba(37, 99, 235, 0.7)'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection
