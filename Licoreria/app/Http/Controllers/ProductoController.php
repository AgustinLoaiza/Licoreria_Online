<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Producto::query();

        // Filtro por busqeuda
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'ILIKE', "%$search%");
        }

        // Filtro por tipo
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }


        if (in_array($user->rol, ['administrador', 'repartidor'])) {
            $productos = $query->orderBy('created_at', 'desc')->paginate(10);
            return view('productos.admin-index', compact('productos'));
        }

        $productos = $query->where('activo', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('productos.cliente-index', compact('productos'));
    }

    // Catalogo para los clientes
    public function catalogo(Request $request)
    {
        $query = Producto::where('activo', true);

        // Filtro por busqeuda
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nombre', 'ILIKE', "%$search%");
        }

        // Filtro por tipo/categoria
        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        $productos = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('productos.cliente-index', compact('productos'));
    }

    // TODO Mostrar catalogo
    public function catalogoShow(Producto $producto)
    {
        if (!$producto->activo) {
            abort(404);
        }
        return view('productos.catalogo-show', compact('producto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        if (!in_array($user->rol, ['administrador', 'repartidor'])) {
            abort(403, 'No tienes permiso para crear productos');
        }

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
        $user = Auth::user();

        // Si es admin o repartidor, usar vista de admin
        if (in_array($user->rol, ['administrador', 'repartidor'])) {
            return view('productos.show', compact('producto'));
        }

        // Si es cliente, usar vista de cliente
        return view('productos.cliente-show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $user = Auth::user();
        if (!in_array($user->rol, ['administrador', 'repartidor'])) {
            abort(403, 'No tienes permiso para editar productos');
        }

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

    // Funcion para reabastecer el producto
    public function reabastecer(Request $request, Producto $producto)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto->cantidad += $request->cantidad;
        $producto->save();

        return back()->with('success', 'Producto reabastecido correctamente.');
    }

    //Funcion para cambiar de estado
    public function cambiarEstado(Request $request, Producto $producto)
    {
        $request->validate([
            'activo' => 'required|boolean',
        ]);

        $producto->activo = $request->activo;
        $producto->save();

        return back()->with('success', 'Estado del producto actualizado correctamente.');
    }

    // Funcion para exportar en PDF
    public function exportPDF()
    {
        $productos = Producto::all();
        $pdf = Pdf::loadView('productos.pdf', compact('productos'));
        return $pdf->download('productos.pdf');
    }

    // ? : API para obtener los productos por tipo si deseo usar AJAX
    public function apiPorTipo($tipo) {
        $productos = Producto::where('tipo', $tipo)->where('activo', true)->get();
        return response()->json($productos);
    }
}
