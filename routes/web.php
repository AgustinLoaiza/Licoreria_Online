<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\ReporteController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Rutas publicas
Route::middleware('guest')->group(function() {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Rutas protegidas
Route::middleware('auth')->group(function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboards por rol
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.administrador')->middleware('role:administrador');
    Route::get('/delivery/dashboard', [DashboardController::class, 'delivery'])->name('dashboard.repartidor')->middleware('role:repartidor');
    Route::get('/cliente/dashboard', [DashboardController::class, 'cliente'])->name('dashboard.cliente')->middleware('role:cliente');

    // CRUD de usuarios que solo puede hacer el administrador
    Route::resource('usuarios', UsuarioController::class)->middleware('role:administrador,cliente,repartidor');
    // Perfil de cliente
    Route::get('/perfil', [UsuarioController::class, 'editPerfilCliente'])->name('cliente.perfil.edit')->middleware('role:cliente');
    Route::put('/perfil', [UsuarioController::class, 'updatePerfilCliente'])->name('cliente.perfil.update')->middleware('role:cliente');

    // Perfil de repartidor
    Route::get('/repartidor/perfil', [UsuarioController::class, 'editPerfilRepartidor'])->name('repartidor.perfil.edit')->middleware('role:repartidor');
    Route::put('/repartidor/perfil', [UsuarioController::class, 'updatePerfilRepartidor'])->name('repartidor.perfil.update')->middleware('role:repartidor');

    // CRUD de productos
    Route::resource('productos', ProductoController::class)->middleware('role:administrador,cliente,repartidor');

    // PRODUCTOS PARA CLIENTES (catálogo)
    Route::get('/catalogo', [ProductoController::class, 'catalogo'])->name('productos.catalogo');

    // PRODUCTOS PARA ADMIN/REPARTIDOR (gestión)
    Route::middleware('role:administrador,repartidor')->prefix('administrador')->group(function () {
        Route::get('/productos', [ProductoController::class, 'index'])->name('productos.admin.index');
        Route::get('/productos/create', [ProductoController::class, 'create'])->name('productos.create');
        Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');
        Route::post('/productos/{producto}/reabastecer', [ProductoController::class, 'reabastecer'])->name('productos.reabastecer')->middleware('role:administrador');
        Route::post('/productos/{producto}/cambiar-estado', [ProductoController::class, 'cambiarEstado'])->name('productos.cambiar-estado')->middleware('role:administrador,repartidor');
        Route::get('/productos/{producto}', [ProductoController::class, 'show'])->name('productos.show');
        Route::get('/catalogo/{producto}', [ProductoController::class, 'catalogoShow'])->name('productos.catalogo.show');
        Route::get('/productos/{producto}/edit', [ProductoController::class, 'edit'])->name('productos.edit');
        Route::put('/productos/{producto}', [ProductoController::class, 'update'])->name('productos.update');
        Route::delete('/productos/{producto}', [ProductoController::class, 'destroy'])->name('productos.destroy');
    });
    // PRODUCTOS PARA CLIENTES (vista individual)
    Route::get('/catalogo/{producto}', [ProductoController::class, 'catalogoShow'])
        ->name('productos.catalogo.show')
        ->middleware('role:cliente');

    // CRUD de pedidos
    Route::resource('pedidos', PedidoController::class);
    Route::post('/pedidos/{pedido}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiar-estado');

    Route::put('/deliveries/{delivery}/cambiar-estado', [DeliveryController::class, 'cambiarEstado'])->name('deliveries.cambiar-estado')->middleware('role:repartidor');
    Route::resource('deliveries', DeliveryController::class)->middleware('role:administrador');

    Route::resource('ventas', VentaController::class)->middleware('role:administrador');
    Route::post('/ventas/{venta}/subir-comprobante', [VentaController::class, 'subirComprobante'])->name('ventas.subir-comprobante')->middleware('role:cliente');
    Route::post('/ventas/{venta}/verificar', [VentaController::class, 'verificarPago'])->name('ventas.verificar')->middleware('role:administrador');

    // Asignar repartidor (administrador)
    Route::post('/pedidos/{pedido}/asignar-repartidor', [PedidoController::class, 'asignarRepartidor'])->name('pedidos.asignar-repartidor')->middleware('role:administrador');
    
    // Repartidor se asigna a sí mismo
    Route::post('/pedidos/{pedido}/asignarme', [PedidoController::class, 'asignarme'])->name('pedidos.asignarme')->middleware('role:repartidor');

    // Rutas para exportar reportes en PDF
    Route::get('/productos/export/pdf', [ProductoController::class, 'exportPdf'])->name('productos.export.pdf')->middleware('role:administrador');
    Route::get('/ventas/export/pdf', [VentaController::class, 'exportPdf'])->name('ventas.export.pdf')->middleware('role:administrador');

    Route::get('/', function() {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        return match (Auth::user()->rol) {
            'administrador' => redirect()->route('dashboard.administrador'),
            'repartidor' => redirect()->route('dashboard.repartidor'),
            default => redirect()->route('dashboard.cliente'),
        };
    });
});
