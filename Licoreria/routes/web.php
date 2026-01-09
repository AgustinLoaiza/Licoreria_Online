<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\DeliveryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return view('welcome');
// });

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

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('dashboard.administrador')->middleware('role:administrador');
    Route::get('/delivery/dashboard', [DashboardController::class, 'delivery'])->name('dashboard.repartidor')->middleware('role:repartidor');
    Route::get('/cliente/dashboard', [DashboardController::class, 'cliente'])->name('dashboard.cliente')->middleware('role:cliente');

    Route::resource('usuarios', UsuarioController::class)->middleware('role:administrador');
    Route::resource('productos', ProductoController::class)->middleware('role:administrador,repartidor');
    Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index')->middleware('role:cliente');
    Route::resource('pedidos', PedidoController::class);
    Route::post('/pedidos/{pedido}/cambiar-estado', [PedidoController::class, 'cambiarEstado'])->name('pedidos.cambiarEstado');
    Route::resource('deliveries', DeliveryController::class)->middleware('role:administrador');
    Route::resource('ventas', VentaController::class)->middleware('role:administrador');
    Route::post('/ventas/{venta}/subir-comprobante', [VentaController::class, 'subirComprobante'])->name('ventas.subirComprobante')->middleware('role:cliente');
    Route::post('/ventas/{venta}/verificar', [VentaController::class, 'verificarPago'])->name('ventas.verificar')->middleware('role:administrador');

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
