<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Usuario;
use App\Models\Delivery;
use App\Models\Detalle_pedido;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->rol === 'administrador') {
            $pedidos = Pedido::with(['usuario', 'delivery'])->paginate(10);
        } elseif ($user->rol === 'repartidor') {
            $pedidos = Pedido::where('delivery_id', $user->id)->paginate(10);
        } else {
            $pedidos = Pedido::where('usuario_id', $user->id)->with(['usuario', 'delivery'])->orderBy('created_at', 'desc')->paginate(10);
        }

        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = Producto::where('activo', true)->get();
        $clientes = Usuario::where('rol', 'cliente')->get();
        $repartidores = Delivery::with('usuario')->where('estado', 'disponible')->get();

        return view('pedidos.create', compact('productos', 'clientes', 'repartidores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (is_string($request->productos)) {
            $request->merge([
                'productos' => json_decode($request->productos, true)
            ]);
        }

        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id',
            'delivery_id' => 'nullable|exists:deliveries,id',
            'nombre_pedido' => 'nullable|string|max:100',
            'direccion' => 'required|string|max:255',
            'productos' => 'required|array|min:1',
            'productos.*.id' => 'required|exists:productos,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        $pedido = Pedido::create([
            'usuario_id' => $validated['usuario_id'],
            'delivery_id' => $validated['delivery_id'] ?? null,
            'nombre_pedido' => $validated['nombre_pedido'] ?? null,
            'direccion' => $validated['direccion'],
            'estado' => 'pendiente',
            'monto_total' => 0,
        ]);

        Venta::create([
            'pedido_id' => $pedido->id,
            'metodo_pago' => $request->metodo_pago,
            'estado' => 'pendiente',
            'fecha_pago' => null,
        ]);

        $total = 0;

        foreach ($validated['productos'] as $item) {
            $producto = Producto::find($item['id']);

            if ($producto->cantidad < $item['cantidad']) {
                return back()->withErrors(['productos' => "Cantidad insuficiente para el producto: {$producto->nombre}"])->withInput();
            }

            $subtotal = $producto->precio * $item['cantidad'];
            $total += $subtotal;

            Detalle_pedido::create([
                'pedido_id' => $pedido->id,
                'producto_id' => $producto->id,
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $producto->precio,
                'subtotal' => $subtotal,
            ]);

            $producto->decrement('cantidad', $item['cantidad']);
        }

        $pedido->update(['monto_total' => $total]);

        if ($pedido->delivery_id) {
            Delivery::find($pedido->delivery_id)->update(['estado' => 'ocupado']);
        }

        return redirect()->route('pedidos.show', $pedido)->with('success', 'Pedido creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedido $pedido)
    {
        $pedido->load(['usuario', 'delivery.usuario', 'detalles.producto', 'venta']);
        return view('pedidos.show', compact('pedido'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pedido $pedido)
    {
        $productos = Producto::where('activo', true)->get();
        $repartidores = Delivery::with('usuario')->where('estado', 'disponible')->get();

        return view('pedidos.edit', compact('pedido', 'productos', 'repartidores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedido $pedido)
    {
        $validated = $request->validate([
            'delivery_id' => 'nullable|exists:deliveries,id',
            'nombre_pedido' => 'nullable|string|max:100',
            'direccion' => 'required|string|max:255',
            'estado' => 'required|in:pendiente,enviado,entregado,cancelado',
        ]);

        $pedido->update($validated);

        if ($pedido->wasChanged('delivery_id')) {
            if ($pedido->getOriginal('delivery_id')) {
                Delivery::find($pedido->getOriginal('delivery_id'))->update(['estado' => 'disponible']);
            }

            if ($pedido->delivery_id) {
                Delivery::find($pedido->delivery_id)->update(['estado' => 'ocupado']);
            }
        }

        if (in_array($validated['estado'], ['entregado', 'cancelado']) && $pedido->delivery_id) {
            Delivery::find($pedido->delivery_id)->update(['estado' => 'disponible']);
        }

        return redirect()->route('pedidos.show', $pedido)->with('success', 'Pedido actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pedido $pedido)
    {
        foreach ($pedido->detalles as $detalle) {
            $producto = $detalle->producto;
            $producto->increment('cantidad', $detalle->cantidad);
        }

        if ($pedido->delivery_id) {
            Delivery::find($pedido->delivery_id)->update(['estado' => 'disponible']);
        }

        $pedido->delete();

        return redirect()->route('pedidos.index')->with('success', 'Pedido eliminado exitosamente.');
    }

    // TODO Asignar repartidor (si es administrador)
    public function asignarRepartidor(Request $request, Pedido $pedido)
    {
        $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
        ]);
        $pedido->delivery_id = $request->delivery_id;
        $pedido->estado = 'enviado';
        $pedido->save();

        Delivery::find($request->delivery_id)->update(['estado' => 'ocupado']);

        return back()->with('success', 'Repartidor asignado exitosamente.');
    }

    // TODO Repartidor se asigna a sí mismo
    public function asignarme(Pedido $pedido)
    {
        $user = Auth::user();
        if ($user->rol !== 'repartidor') {
            return back()->withErrors(['error' => 'Solo los repartidores pueden asignarse pedidos.']);
        }

        if (!$pedido->delivery_id) {
            $delivery = Delivery::where('usuario_id', $user->id)->first();
            if (!$delivery) {
                return back()->withErrors(['error' => 'No se encontró el perfil de repartidor.']);
            }
            $pedido->delivery_id = $delivery->id;
            $pedido->estado = 'enviado';
            $pedido->save();

            $delivery->update(['estado' => 'ocupado']);
            return back()->with('success', 'Te has asignado el pedido exitosamente.');
        }
        return back()->withErrors(['error' => 'El pedido ya tiene un repartidor asignado.']);
    }

    // ? : API para cambiar el estado del pedido
    public function cambiarEstado(Request $request, Pedido $pedido)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,enviado,entregado,cancelado',
        ]);

        $pedido->update(['estado' => $request->estado]);

        return response()->json(['message' => 'Estado del pedido actualizado exitosamente.']);
    }
}
