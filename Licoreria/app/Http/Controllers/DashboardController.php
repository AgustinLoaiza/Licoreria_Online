<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venta;
use App\Models\Pedido;
use App\Models\Usuario;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $total_ventas = Pedido::whereHas('venta', function($q) {
            $q->where('estado', 'pagado');
        })->sum('monto_total');

        // Venta de los ultimos 7 meses
        $ventasMensuales = DB::table('ventas')
            ->join('pedidos', 'ventas.pedido_id', '=', 'pedidos.id')
            ->selectRaw("to_char(ventas.created_at, 'Mon YYYY') as mes, SUM(pedidos.monto_total) as total")
            ->where('ventas.estado', 'pagado')
            ->where('ventas.created_at', '>=', now()->subMonths(6)->startOfMonth())
            ->groupBy(DB::raw("to_char(ventas.created_at, 'Mon YYYY')"), DB::raw("date_trunc('month', ventas.created_at)"))
            ->orderBy(DB::raw("date_trunc('month', ventas.created_at)"))
            ->pluck('total', 'mes')
            ->toArray();

        // Asegurar que siempre haya 7 meses aunque 0
        $labels = [];
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $mes = now()->subMonths($i)->format('M Y');
            $labels[] = $mes;
            $data[] = isset($ventasMensuales[$mes]) ? (float)$ventasMensuales[$mes] : 0;
        }

        return view('dashboard.administrador', compact('stats', 'total_ventas', 'pedidos_recientes', 'pagos_pendientes', 'labels', 'data'));
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
        // Estadísticas rápidas del día
        $entregadosHoy = Pedido::where('delivery_id', $delivery->id)
            ->where('estado', 'entregado')
            ->whereDate('updated_at', now()->toDateString())
            ->count();

        // Si tienes un campo de comisión, cámbialo aquí
        $gananciasHoy = Pedido::where('delivery_id', $delivery->id)
            ->where('estado', 'entregado')
            ->whereDate('updated_at', now()->toDateString())
            ->sum('monto_total');
            
        $pedidos_activos = $pedidos_asignados->count();

        return view('dashboard.repartidor', compact('pedidos_asignados', 'historial', 'delivery', 'pedidos_activos', 'entregadosHoy', 'gananciasHoy'));
    }
}
