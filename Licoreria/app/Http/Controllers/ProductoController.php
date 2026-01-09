<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('productos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:bebida,soda,papas,hielos,cigarros,otros',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
        ]);

        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('imagenes_productos', 'public');
            $validated['imagen'] = $path;
        }

        Producto::create($validated);

        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        return view('productos.edit', compact('producto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:bebida,soda,papas,hielos,cigarros,otros',
            'precio' => 'required|numeric|min:0',
            'cantidad' => 'required|integer|min:0',
            'descripcion' => 'nullable|string',
            'activo' => 'required|boolean',
        ]);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $path = $request->file('imagen')->store('imagenes_productos', 'public');
            $validated['imagen'] = $path;
        }

        $producto->update($validated);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }

    // ? : API para obtener los productos por tipo si deseo usar AJAX
    public function apiPorTipo($tipo) {
        $productos = Producto::where('tipo', $tipo)->where('activo', true)->get();
        return response()->json($productos);
    }
}
