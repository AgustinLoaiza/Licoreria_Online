@extends(Auth::user()->rol === 'administrador' ? 'layouts.admin' : (Auth::user()->rol === 'repartidor' ? 'layouts.admin' : 'layouts.cliente'))

@section('title', 'Productos - Licorería')

@if(Auth::user()->rol === 'administrador' || Auth::user()->rol === 'repartidor')
    @section('page-title', 'Gestión de Productos')
    @section('page-description', 'Administra el inventario de productos')
@else
    @section('page-title', 'Catálogo de Productos')
    @section('page-description', 'Explora nuestra selección de bebidas')
@endif

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    @if(Auth::user()->rol === 'administrador' || Auth::user()->rol === 'repartidor')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Productos</h2>
            <p class="text-gray-600">Total: {{ $productos->total() }} productos</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('productos.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                <i class="fas fa-plus mr-2"></i> Nuevo Producto
            </a>
            <button onclick="exportarProductos()"
                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                <i class="fas fa-file-export mr-2"></i> Exportar
            </button>
        </div>
    </div>
    @endif

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-xl shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Búsqueda -->
            <div>
                <input type="text"
                       id="searchInput"
                       placeholder="Buscar producto..."
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Filtro por tipo -->
            <div>
                <select id="filterTipo" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los tipos</option>
                    <option value="bebida">Bebidas</option>
                    <option value="comida">Comidas</option>
                    <option value="combo">Combos</option>
                    <option value="accesorio">Accesorios</option>
                </select>
            </div>

            <!-- Filtro por estado -->
            <div>
                <select id="filterEstado" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                    <option value="bajo_stock">Stock Bajo</option>
                </select>
            </div>

            <!-- Botón de filtro -->
            <div>
                <button onclick="aplicarFiltros()"
                        class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de productos -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Producto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tipo
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Precio
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Stock
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        @if(Auth::user()->rol === 'admin' || Auth::user()->rol === 'repartidor')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productos as $producto)
                    <tr class="hover:bg-gray-50">
                        <!-- Columna Producto -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    @if($producto->imagen)
                                        <img class="h-10 w-10 rounded-full object-cover"
                                             src="{{ asset('storage/' . $producto->imagen) }}"
                                             alt="{{ $producto->nombre }}">
                                    @else
                                        <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                                            <i class="fas fa-wine-bottle text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $producto->nombre }}
                                    </div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">
                                        {{ Str::limit($producto->descripcion, 50) }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Columna Tipo -->
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $producto->tipo == 'bebida' ? 'bg-blue-100 text-blue-800' :
                                   ($producto->tipo == 'comida' ? 'bg-green-100 text-green-800' :
                                   ($producto->tipo == 'combo' ? 'bg-purple-100 text-purple-800' :
                                   'bg-yellow-100 text-yellow-800')) }}">
                                {{ ucfirst($producto->tipo) }}
                            </span>
                        </td>

                        <!-- Columna Precio -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">
                                S/ {{ number_format($producto->precio, 2) }}
                            </div>
                        </td>

                        <!-- Columna Stock -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 mr-2">
                                    @php
                                        $porcentaje = min(100, ($producto->cantidad / 50) * 100);
                                        $color = $producto->cantidad > 20 ? 'bg-green-600' :
                                                ($producto->cantidad > 10 ? 'bg-yellow-500' : 'bg-red-600');
                                    @endphp
                                    <div class="h-2.5 rounded-full {{ $color }}" style="width: {{ $porcentaje }}%"></div>
                                </div>
                                <span class="text-sm font-medium {{ $producto->cantidad < 10 ? 'text-red-600' : 'text-gray-700' }}">
                                    {{ $producto->cantidad }}
                                </span>
                            </div>
                        </td>

                        <!-- Columna Estado -->
                        <td class="px-6 py-4">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $producto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                        <!-- Columna Acciones -->
                        @if(Auth::user()->rol === 'administrador' || Auth::user()->rol === 'repartidor')
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <!-- Ver -->
                                <a href="{{ route('productos.show', $producto) }}"
                                   class="text-blue-600 hover:text-blue-900"
                                   title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('productos.edit', $producto) }}"
                                   class="text-yellow-600 hover:text-yellow-900"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Eliminar -->
                                <button onclick="eliminarProducto({{ $producto->id }}, '{{ $producto->nombre }}')"
                                        class="text-red-600 hover:text-red-900"
                                        title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Para clientes: Agregar al carrito -->
                                @else
                                <button onclick="addToCart({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->precio }})"
                                        class="bg-red-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-red-700 flex items-center">
                                    <i class="fas fa-cart-plus mr-1"></i> Agregar
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->rol === 'cliente' ? 5 : 6 }}" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron productos
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t">
            {{ $productos->links('components.pagination') }}
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
@include('components.confirm-modal')

@push('scripts')
<script>
    function eliminarProducto(id, nombre) {
        showConfirmModal(
            '¿Eliminar producto?',
            `¿Estás seguro de eliminar el producto "${nombre}"? Esta acción no se puede deshacer.`,
            `/productos/${id}`
        );
    }

    function exportarProductos() {
        window.location.href = '/productos/export?type=csv';
    }

    function aplicarFiltros() {
        const search = document.getElementById('searchInput').value;
        const tipo = document.getElementById('filterTipo').value;
        const estado = document.getElementById('filterEstado').value;

        let url = '{{ route("productos.index") }}?';
        const params = [];

        if (search) params.push(`search=${encodeURIComponent(search)}`);
        if (tipo) params.push(`tipo=${tipo}`);
        if (estado) params.push(`estado=${estado}`);

        window.location.href = url + params.join('&');
    }

    // Aplicar filtros con Enter
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') aplicarFiltros();
    });

    // Para clientes: función addToCart
    @if(Auth::user()->rol === 'cliente')
    function addToCart(productId, nombre, precio) {
        // Esta función ya debería existir en el layout del cliente
        if (typeof window.addToCart === 'function') {
            window.addToCart(productId, nombre, precio);
        }
    }
    @endif
</script>
@endpush
@endsection
