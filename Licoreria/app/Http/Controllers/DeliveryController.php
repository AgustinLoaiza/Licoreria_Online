<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:repartidor',
        ]);

        $usuario = Usuario::create([
            'nombre' => $validated['nombre'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'telefono' => $validated['telefono'] ?? null,
            'rol' => 'repartidor',
        ]);

        Delivery::create([
            'usuario_id' => $usuario->id,
            'estado' => 'disponible',
        ]);

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
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:usuarios,email,' . $delivery->usuario_id,
            'password' => 'nullable|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'estado' => 'required|in:disponible,ocupado,inactivo',
        ]);

        $usuario = $delivery->usuario;
        $usuario->nombre = $validated['nombre'];
        $usuario->email = $validated['email'];
        if (!empty($validated['password'])) {
            $usuario->password = bcrypt($validated['password']);
        }
        $usuario->telefono = $validated['telefono'] ?? $usuario->telefono;
        $usuario->save();

        $delivery->estado = $validated['estado'];
        $delivery->save();

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

    // TODO Cambio de estado por el propio repartidor
    public function cambiarEstado(Request $request, Delivery $delivery)
    {
        $user = Auth::user();
        if ($user->rol !== 'repartidor' || $delivery->usuario_id !== $user->id) {
            abort(403, 'No tienes permiso para cambiar el estado de este repartidor');
        }

        $request->validate([
            'estado' => 'required|in:disponible,ocupado,inactivo',
        ]);

        $delivery->estado = $request->input('estado');
        $delivery->save();

        return back()->with('success', 'Estado del repartidor actualizado exitosamente.');
    }

    // ? : API para obtener repartidores disponibles
    public function disponibles()
    {
        $deliveries = Delivery::with('usuario')->where('estado', 'disponible')->get();
        return response()->json($deliveries);
    }
}
