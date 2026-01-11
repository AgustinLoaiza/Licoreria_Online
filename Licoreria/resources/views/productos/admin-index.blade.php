@extends('layouts.admin')

@section('title', 'Gestión de Productos - Licorería')
@section('page-title', 'Gestión de Productos')
@section('page-description', 'Administra el inventario de productos')

@section('content')
<div class="space-y-6">
    <!-- Header con estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Productos</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $productos->total() }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Productos Activos</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $productos->where('activo', true)->count() }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Stock Bajo (<10)</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $productos->where('cantidad', '<', 10)->count() }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Sin Stock</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $productos->where('cantidad', 0)->count() }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-times-circle text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Header con acciones -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Gestión de Productos</h2>
            <p class="text-gray-600">Administra el inventario completo</p>
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
            <a href="{{ route('productos.index') }}?view=catalogo"
               class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 flex items-center">
                <i class="fas fa-eye mr-2"></i> Ver como Cliente
            </a>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-xl shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Búsqueda -->
            <div class="md:col-span-2">
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
                    <option value="">Todos</option>
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                    <option value="bajo_stock">Stock Bajo</option>
                    <option value="sin_stock">Sin Stock</option>
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

    <!-- Tabla de productos (vista admin) -->
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productos as $producto)
                    <tr class="hover:bg-gray-50">
                        <!-- Columna Producto -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($producto->imagen)
                                        <img class="h-12 w-12 rounded-lg object-cover"
                                             src="{{ asset('storage/' . $producto->imagen) }}"
                                             alt="{{ $producto->nombre }}">
                                    @else
                                        <div class="h-12 w-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-wine-bottle text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $producto->nombre }}
                                        @if(!$producto->activo)
                                        <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded">INACTIVO</span>
                                        @endif
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
                            <div class="text-xs text-gray-500">
                                Costo: S/ {{ number_format($producto->precio * 0.7, 2) }}
                            </div>
                        </td>

                        <!-- Columna Stock -->
                        <td class="px-6 py-4">
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <div class="w-32 bg-gray-200 rounded-full h-2.5 mr-2">
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
                                @if($producto->cantidad < 10)
                                <button onclick="reabastecerProducto({{ $producto->id }})"
                                        class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded hover:bg-yellow-200">
                                    <i class="fas fa-boxes mr-1"></i> Reabastecer
                                </button>
                                @endif
                            </div>
                        </td>

                        <!-- Columna Estado -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    {{ $producto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                                </span>

                                <div class="flex space-x-1">
                                    <button onclick="toggleEstado({{ $producto->id }}, {{ $producto->activo ? 0 : 1 }})"
                                            class="text-xs {{ $producto->activo ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }} px-2 py-1 rounded">
                                        {{ $producto->activo ? 'Desactivar' : 'Activar' }}
                                    </button>
                                </div>
                            </div>
                        </td>

                        <!-- Columna Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <!-- Ver -->
                                <a href="{{ route('productos.show', $producto) }}"
                                   class="text-blue-600 hover:text-blue-900" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('productos.edit', $producto) }}"
                                   class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Eliminar -->
                                <button onclick="eliminarProducto({{ $producto->id }}, '{{ $producto->nombre }}')"
                                        class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>

                                <!-- Duplicar -->
                                <button onclick="duplicarProducto({{ $producto->id }})"
                                        class="text-purple-600 hover:text-purple-900" title="Duplicar">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
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

    <!-- Resumen de acciones rápidas -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones Rápidas</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="exportarReporteStock()"
                    class="bg-blue-100 text-blue-700 p-4 rounded-lg hover:bg-blue-200 flex items-center">
                <i class="fas fa-chart-bar text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold">Reporte de Stock</p>
                    <p class="text-sm">Generar reporte PDF</p>
                </div>
            </button>

            <button onclick="reabastecerTodos()"
                    class="bg-green-100 text-green-700 p-4 rounded-lg hover:bg-green-200 flex items-center">
                <i class="fas fa-truck-loading text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold">Reabastecer</p>
                    <p class="text-sm">Productos con stock bajo</p>
                </div>
            </button>

            <button onclick="verCatalogoCompleto()"
                    class="bg-purple-100 text-purple-700 p-4 rounded-lg hover:bg-purple-200 flex items-center">
                <i class="fas fa-store text-2xl mr-3"></i>
                <div>
                    <p class="font-semibold">Ver Catálogo</p>
                    <p class="text-sm">Como lo ven los clientes</p>
                </div>
            </button>
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
        window.location.href = '/productos/export?type=excel';
    }

    function exportarReporteStock() {
        window.location.href = '/reportes/stock-pdf';
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

    function toggleEstado(productoId, nuevoEstado) {
        fetch(`/productos/${productoId}/toggle-estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ activo: nuevoEstado })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cambiar el estado');
        });
    }

    function reabastecerProducto(productoId) {
        const cantidad = prompt('Ingrese la cantidad a agregar:', '10');
        if (cantidad && !isNaN(cantidad) && cantidad > 0) {
            fetch(`/productos/${productoId}/reabastecer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ cantidad: parseInt(cantidad) })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function duplicarProducto(productoId) {
        if (confirm('¿Duplicar este producto?')) {
            fetch(`/productos/${productoId}/duplicar`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    }

    function reabastecerTodos() {
        if (confirm('¿Reabastecer todos los productos con stock bajo?')) {
            // Implementar lógica de reabastecimiento
            alert('Función de reabastecimiento en desarrollo');
        }
    }

    function verCatalogoCompleto() {
        window.open('/catalogo', '_blank');
    }

    // Aplicar filtros con Enter
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') aplicarFiltros();
    });
</script>
@endpush
@endsection
