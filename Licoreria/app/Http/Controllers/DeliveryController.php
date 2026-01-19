<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Usuario;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveries = Delivery::with('usuario')->paginate(10);
        return view('deliveries.index', compact('deliveries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $usuarios = Usuario::where('rol', 'repartidor')->wheredoesntHave('delivery')->paginate(10);
        return view('deliveries.create', compact('usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'usuario_id' => 'required|exists:usuarios,id|unique:deliveries,usuario_id',
            'estado' => 'required|in:disponible,ocupado,inactivo',
        ]);

        Delivery::create($validated);

        return redirect()->route('deliveries.index')->with('success', 'Repartidor creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        $delivery->load('usuario', 'pedidos.usuario');
        return view('deliveries.show', compact('delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery)
    {
        return view('deliveries.edit', compact('delivery'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {
        $validated = $request->validate([
            'estado' => 'required|in:disponible,ocupado,inactivo',
        ]);

        $delivery->update($validated);

        return redirect()->route('deliveries.index')->with('success', 'Repartidor actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        $delivery->delete();
        return redirect()->route('deliveries.index')->with('success', 'Repartidor eliminado exitosamente.');
    }

    // ? : API para obtener repartidores disponibles
    public function disponibles()
    {
        $deliveries = Delivery::with('usuario')->where('estado', 'disponible')->get();
        return response()->json($deliveries);
    }
}
