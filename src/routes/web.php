<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\EmpleadoController;


/*
|--------------------------------------------------------------------------
| Rutas públicas (sin autenticación)
|--------------------------------------------------------------------------
*/
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rutas protegidas por autenticación
|--------------------------------------------------------------------------
*/

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Ventas
    Route::prefix('ventas')->group(function () {
        Route::get('/', [VentaController::class, 'index'])->name('ventas.index');
        Route::get('/crear', [VentaController::class, 'create'])->name('ventas.create');
        Route::post('/', [VentaController::class, 'store'])->name('ventas.store');
        Route::get('/{venta}', [VentaController::class, 'show'])->name('ventas.show');
        Route::get('/{venta}/editar', [VentaController::class, 'edit'])->name('ventas.edit');
        Route::put('/{venta}', [VentaController::class, 'update'])->name('ventas.update');
        Route::delete('/{venta}', [VentaController::class, 'destroy'])->name('ventas.destroy');
        Route::get('/{venta}/ticket', [VentaController::class, 'ticket'])->name('ventas.ticket');
    });

    // Gastos
    Route::prefix('gastos')->group(function () {
        Route::get('/', [GastoController::class, 'index'])->name('gastos.index');
        Route::get('/crear', [GastoController::class, 'create'])->name('gastos.create');
        Route::post('/', [GastoController::class, 'store'])->name('gastos.store');
        Route::get('/{gasto}', [GastoController::class, 'show'])->name('gastos.show');
        Route::get('/{gasto}/editar', [GastoController::class, 'edit'])->name('gastos.edit');
        Route::put('/{gasto}', [GastoController::class, 'update'])->name('gastos.update');
        Route::delete('/{gasto}', [GastoController::class, 'destroy'])->name('gastos.destroy');
        Route::get('/{gasto}/descargar-comprobante', [GastoController::class, 'downloadComprobante'])
            ->name('gastos.download.comprobante');
    });


    // empleado
    Route::prefix('empleados')->group(function () {
        Route::get('/', [EmpleadoController::class, 'index'])->name('empleados.index');
        Route::get('/crear', [EmpleadoController::class, 'create'])->name('empleados.create');
        Route::post('/', [EmpleadoController::class, 'store'])->name('empleados.store');
        Route::get('/{empleado}', [EmpleadoController::class, 'show'])->name('empleados.show');
        Route::get('/{empleado}/editar', [EmpleadoController::class, 'edit'])->name('empleados.edit');
        Route::put('/{empleado}', [EmpleadoController::class, 'update'])->name('empleados.update');
        Route::delete('/{empleado}', [EmpleadoController::class, 'destroy'])->name('empleados.destroy');
    });

    // Productos
    Route::prefix('productos')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->name('productos.index');
        Route::get('/crear', [ProductoController::class, 'create'])->name('productos.create');
        Route::post('/', [ProductoController::class, 'store'])->name('productos.store');
        Route::get('/{producto}', [ProductoController::class, 'show'])->name('productos.show');
        Route::get('/{producto}/editar', [ProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/{producto}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    });

    // Inventario
    Route::prefix('inventario')->group(function () {
        Route::get('/', [InventarioController::class, 'index'])->name('inventario.index');
        Route::get('/{existencia}', [InventarioController::class, 'show'])->name('inventario.show');
        Route::get('/{existencia}/edit', [InventarioController::class, 'edit'])->name('inventario.edit');
        Route::put('/{existencia}', [InventarioController::class, 'update'])->name('inventario.update');
    });

    // Reportes
    Route::get('reportes', [ReporteController::class, 'index'])->name('reportes.index');
/*
|--------------------------------------------------------------------------
| Redirección para rutas no existentes
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return redirect()->route('login');
});
