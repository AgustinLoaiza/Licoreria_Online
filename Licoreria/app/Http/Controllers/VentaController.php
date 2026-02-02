<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use App\Models\Pedido;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Estadísticas
        $total_ventas = Pedido::where('estado', 'pagado')->sum('monto_total');
        $ventas_hoy = Pedido::where('estado', 'pagado')->whereDate('created_at', today())->sum('monto_total');
        $ventas_count_hoy = Venta::where('estado', 'pagado')->whereDate('created_at', today())->count();
        $pagos_pendientes = Venta::where('estado', 'pendiente')->count();
        $ventas_verificadas = Venta::where('estado', 'verificado')->count();
        $total_verificadas = Pedido::where('estado', 'verificado')->sum('monto_total');

        // Filtros y paginación
        $query = Venta::with('pedido.usuario');

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('metodo')) {
            $query->where('metodo_pago', $request->metodo);
        }
        if ($request->filled('fecha')) {
            $query->whereDate('created_at', $request->fecha);
        }
        if ($request->filled('search')) {
            $query->whereHas('pedido.usuario', function($q) use ($request) {
                $q->where('nombre', 'ILIKE', '%' . $request->search . '%');
            });
        }

        $ventas = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('ventas.index', compact(
            'total_ventas',
            'ventas_hoy',
            'ventas_count_hoy',
            'pagos_pendientes',
            'ventas_verificadas',
            'total_verificadas',
            'ventas'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pedidos = Pedido::whereDoesntHave('venta')->where('estado', '!=', 'cancelado')->with('usuario')->get();
        return view('ventas.create', compact('pedidos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pedido_id' => 'required|exists:pedidos,id|unique:ventas,pedido_id',
            'metodo_pago' => 'required|in:efectivo,qr,tarjeta',
            'comprobante' => 'nullable|image|max:2048',
        ]);

        $validated['estado'] = 'pendiente';
        $validated['fecha_pago'] = now();

        Venta::create($validated);

        return redirect()->route('ventas.index')->with('success', 'Venta creada exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        $venta->load('pedido.usuario', 'pedido.detalle_pedidos.producto');
        return view('ventas.show', compact('venta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        return view('ventas.edit', compact('venta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venta $venta)
    {
        $validated = $request->validate([
            'metodo_pago' => 'required|in:efectivo,qr,tarjeta',
            'comprobante' => 'nullable|image|max:2048',
            'estado' => 'required|in:pendiente,pagado',
        ]);

        if ($validated['estado'] === 'pagado' && $venta->estado !== 'pagado') {
            $validated['fecha_pago'] = now();
        }

        $venta->update($validated);

        return redirect()->route('ventas.show', $venta)->with('success', 'Venta actualizada exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        $venta->delete();
        return redirect()->route('ventas.index')->with('success', 'Venta eliminada exitosamente.');
    }

    public function subirComprobante(Request $request, Venta $venta)
    {
        $request->validate([
            'comprobante' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('comprobante')) {
            $path = $request->file('comprobante')->store('comprobantes', 'public');
            $venta->update([
                'comprobante' => $path,
                'estado' => 'pendiente',
            ]);

            return back()->with('success', 'Comprobante subido exitosamente.');
        }

        return back()->withErrors(['comprobante' => 'Error al subir el comprobante.']);
    }

    public function verificarPago(Venta $venta)
    {
        $venta->update([
            'estado' => 'pagado',
            'fecha_pago' => now(),
        ]);

        return response()->json(['success' => 'Pago verificado exitosamente.']);
    }
}
