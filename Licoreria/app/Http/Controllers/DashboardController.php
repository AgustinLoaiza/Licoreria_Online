<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Pedido;
use App\Models\Usuario;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $stats = [
            'total_ventas' => Venta::where('estado', 'pagado')->count(),
            'ventas_hoy' => Venta::whereDate('created_at', today())->count(),
            'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'total_clientes' => Usuario::where('rol', 'cliente')->count(),
            'productos_bajo_stock' => Producto::where('cantidad', '<', 10)->count(),
            'ingresos_mes' => Pedido::whereHas('venta', function($q) {$q->where('estado', 'pagado')->whereMonth('created_at', now()->month);})->sum('monto_total'),
        ];

        $pedidos_recientes = Pedido::with('usuario')->orderBy('created_at', 'desc')->take(5)->get();
        $pagos_pendientes = Venta::where('estado', 'pendiente')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard.administrador', compact('stats', 'pedidos_recientes', 'pagos_pendientes'));
    }

    public function cliente()
    {
        $usuario = Auth::user();

        $pedidos = Pedido::where('usuario_id', $usuario->id)->orderBy('created_at', 'desc')->paginate(5);
        $pedidos_pendientes = Pedido::where('usuario_id', $usuario->id)->where('estado', 'pendiente')->count();
        $pedidos_entregados = Pedido::where('usuario_id', $usuario->id)->where('estado', 'entregado')->count();

        return view('dashboard.cliente', compact('pedidos', 'pedidos_pendientes', 'pedidos_entregados'));
    }

    public function delivery()
    {
        $usuario = Auth::user();
        $delivery = $usuario->delivery;

        if (!$delivery) {
            abort(403, 'Usted no tiene perfil de repartidor.');
        }

        $pedidos_asignados = Pedido::where('delivery_id', $delivery->id)->orderBy('created_at', 'desc')->paginate(5);
        $historial = Pedido::where('delivery_id', $delivery->id)->whereIn('estado', ['entregado', 'cancelado'])->orderBy('updated_at', 'desc')->paginate(5);
        $pedidos_activos = $pedidos_asignados->count();

        return view('dashboard.repartidor', compact('pedidos_asignados', 'historial', 'delivery', 'pedidos_activos'));
    }
}
