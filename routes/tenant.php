<?php

declare(strict_types=1);


use App\Http\Controllers\Tenant\ClienteController;
use App\Http\Controllers\Tenant\PedidoController;
use App\Http\Controllers\Tenant\ProductoController;
use App\Http\Controllers\Tenant\TiendaController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    
    Route::get('/', [TiendaController::class, 'index'])->name('tienda.index');
    Route::get('/categorias/{slug}', [TiendaController::class, 'categoria'])->name('tienda.categoria');
    Route::get('/producto/{slug}', [TiendaController::class, 'show'])->name('tienda.producto');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

    require __DIR__ . '/auth.php';

    Route::get('/ajustes', [\App\Http\Controllers\Tenant\AjusteController::class, 'index'])->name('ajustes.index')->middleware('auth');
    Route::post('/ajustes', [\App\Http\Controllers\Tenant\AjusteController::class, 'store'])->name('ajustes.store')->middleware('auth');

    // Categorias
    Route::resource('categorias', \App\Http\Controllers\Tenant\CategoriaController::class)->middleware('auth');

    // Productos
    Route::resource('productos', \App\Http\Controllers\Tenant\ProductoController::class)->middleware('auth');
    Route::delete('/productos/imagen/{imagen}', [ProductoController::class, 'eliminarImagen'])->name('productos.imagen.destroy')->middleware('auth');

    // Administación de Pedidos (Protegido por Auth)
    Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index')->middleware('auth');
    Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show')->middleware('auth');
    Route::patch('/pedidos/{pedido}/estado', [PedidoController::class, 'updateEstado'])->name('pedidos.estado')->middleware('auth');
    Route::delete('/pedidos/{pedido}', [PedidoController::class, 'destroy'])->name('pedidos.destroy')->middleware('auth');
    Route::get('/pedidos/{pedido}/imprimir', [PedidoController::class, 'imprimir'])->name('pedidos.imprimir')->middleware('auth');

    // Administracion de Clientes
    Route::resource('clientes', ClienteController::class)->except(['create', 'store'])->middleware('auth');
});
