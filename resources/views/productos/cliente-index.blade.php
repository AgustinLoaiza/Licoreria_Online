@extends('layouts.cliente')

@section('title', 'Catálogo de Productos - Licorería')
@section('page-title', 'Nuestro Catálogo')

@section('content')
<div class="space-y-6">
    <!-- Banner promocional -->
    <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-2xl shadow-xl p-8 text-white">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">¡Encuentra tus bebidas favoritas!</h1>
                <p class="text-red-100 mb-4">Gran selección de Bebidas y acompañamientos. Delivery en 30 minutos.</p>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-xl mr-2"></i>
                        <div>
                            <p class="text-sm">Entrega rápida</p>
                            <p class="font-bold">30-45 min</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-truck text-xl mr-2"></i>
                        <div>
                            <p class="text-sm">Delivery seguro</p>
                            <p class="font-bold">En toda la ciudad</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 md:mt-0">
                <i class="fas fa-wine-bottle text-6xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Filtros para clientes -->
    <div class="bg-white rounded-xl shadow p-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between space-y-4 md:space-y-0">
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text"
                           id="searchInput"
                           placeholder="Buscar bebidas..."
                           class="pl-10 w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
            </div>

            <div class="flex space-x-3">
                <!-- Filtro por categoría -->
                <select id="filterCategoria"
                        class="border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">Todas las categorías</option>
                    <option value="bebida">Bebidas alcohólicas</option>
                    <option value="soda">Refrescos</option>
                    <option value="papas">Snacks</option>
                    <option value="hielos">Hielos</option>
                    <option value="cigarros">Cigarros</option>
                    <option value="otros">Otros</option>
                </select>

                <button onclick="aplicarFiltros()"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
            </div>
        </div>

        <!-- Categorías rápidas -->
        <div class="mt-4 flex space-x-2 overflow-x-auto pb-2">
            <button onclick="filtrarPorTipo('bebida')"
                    class="flex-shrink-0 bg-blue-100 text-blue-800 px-4 py-2 rounded-lg hover:bg-blue-200 flex items-center">
                <i class="fas fa-wine-glass-alt mr-2"></i> Bebidas
            </button>
            <button onclick="filtrarPorTipo('soda')"
                    class="flex-shrink-0 bg-green-100 text-green-800 px-4 py-2 rounded-lg hover:bg-green-200 flex items-center">
                <i class="fas fa-utensils mr-2"></i> Acompañamientos
            </button>
            <button onclick="filtrarPorTipo('papas')"
                    class="flex-shrink-0 bg-purple-100 text-purple-800 px-4 py-2 rounded-lg hover:bg-purple-200 flex items-center">
                <i class="fas fa-gift mr-2"></i> Snacks
            </button>
            <button onclick="filtrarPorTipo('hielos')"
                    class="flex-shrink-0 bg-indigo-100 text-indigo-800 px-4 py-2 rounded-lg hover:bg-indigo-200 flex items-center">
                <i class="fas fa-snowflake mr-2"></i> Hielos
            </button>
            <button onclick="filtrarPorTipo('cigarros')"
                    class="flex-shrink-0 bg-gray-100 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-200 flex items-center">
                <i class="fas fa-smoking mr-2"></i> Cigarros
            </button>
            <button onclick="filtrarPorTipo('otros')"
                    class="flex-shrink-0 bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg hover:bg-yellow-200 flex items-center">
                <i class="fas fa-glass-whiskey mr-2"></i> Otros
            </button>
        </div>
    </div>

    <!-- Grid de productos -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($productos as $producto)
        <div class="product-card bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
            <!-- Badge de stock -->
            @if($producto->cantidad == 0)
            <div class="absolute top-3 right-3 z-10">
                <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    AGOTADO
                </span>
            </div>
            @elseif($producto->cantidad < 10)
            <div class="absolute top-3 right-3 z-10">
                <span class="bg-yellow-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                    ÚLTIMAS {{ $producto->cantidad }}
                </span>
            </div>
            @endif

            <!-- Imagen del producto -->
            <div class="h-48 bg-gray-100 overflow-hidden relative">
                @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}"
                     alt="{{ $producto->nombre }}"
                     class="w-full h-full object-cover transition-transform duration-500 hover:scale-110">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-wine-bottle text-gray-300 text-5xl"></i>
                </div>
                @endif

                <!-- Botón rápido de agregar -->
                <button onclick="addToCart({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->precio }})"
                        class="absolute bottom-3 right-3 bg-red-600 text-white p-2 rounded-full shadow-lg hover:bg-red-700 transition transform hover:scale-110"
                        {{ $producto->cantidad == 0 ? 'disabled' : '' }}>
                    <i class="fas fa-cart-plus"></i>
                </button>
            </div>

            <!-- Información del producto -->
            <div class="p-4">
                <!-- Categoría -->
                <div class="mb-2">
                    <span class="text-xs font-semibold px-2 py-1 rounded
                        {{ $producto->tipo == 'bebida' ? 'bg-blue-100 text-blue-800' :
                           ($producto->tipo == 'comida' ? 'bg-green-100 text-green-800' :
                           ($producto->tipo == 'combo' ? 'bg-purple-100 text-purple-800' :
                           'bg-yellow-100 text-yellow-800')) }}">
                        {{ ucfirst($producto->tipo) }}
                    </span>
                </div>

                <!-- Nombre -->
                <h3 class="font-bold text-gray-800 text-lg mb-1 truncate">{{ $producto->nombre }}</h3>

                <!-- Descripción -->
                @if($producto->descripcion)
                <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $producto->descripcion }}</p>
                @endif

                <!-- Precio y acciones -->
                <div class="flex items-center justify-between mt-4">
                    <div>
                        <p class="text-2xl font-bold text-red-600">S/ {{ number_format($producto->precio, 2) }}</p>
                        @if($producto->cantidad > 0)
                        <p class="text-xs text-gray-500">Stock: {{ $producto->cantidad }} unidades</p>
                        @else
                        <p class="text-xs text-red-500">Sin stock disponible</p>
                        @endif
                    </div>

                    <div class="flex space-x-2">
                        <!-- Ver detalle -->
                        <a href="{{ route('productos.show', $producto) }}"
                           class="text-blue-600 hover:text-blue-800 p-2">
                            <i class="fas fa-eye"></i>
                        </a>

                        <!-- Agregar al carrito (botón grande) -->
                        <button onclick="addToCart({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->precio }})"
                                class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center"
                                {{ $producto->cantidad == 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus mr-2"></i>
                            Agregar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Efecto hover adicional -->
            <div class="px-4 pb-4 opacity-0 hover:opacity-100 transition-opacity duration-300">
                <div class="border-t pt-3">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-shipping-fast mr-2"></i>
                            <span>30-45 min</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-star text-yellow-400 mr-1"></i>
                            <span>4.5</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full">
            <div class="bg-white rounded-xl shadow p-12 text-center">
                <i class="fas fa-search text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">No se encontraron productos</h3>
                <p class="text-gray-600 mb-6">Intenta con otros filtros de búsqueda</p>
                <button onclick="limpiarFiltros()"
                        class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">
                    <i class="fas fa-times mr-2"></i> Limpiar filtros
                </button>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($productos->hasPages())
    <div class="bg-white rounded-xl shadow p-4">
        {{ $productos->links('components.pagination') }}
    </div>
    @endif

    <!-- Sección de información -->
    <div class="bg-white rounded-xl shadow p-8 mt-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-red-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Compra Segura</h4>
                <p class="text-gray-600 text-sm">Todos nuestros productos son 100% originales y certificados.</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-green-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Entrega Rápida</h4>
                <p class="text-gray-600 text-sm">Recibe tu pedido en 30-45 minutos dentro de nuestra zona de cobertura.</p>
            </div>

            <div class="text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-headset text-blue-600 text-2xl"></i>
                </div>
                <h4 class="font-bold text-gray-800 mb-2">Soporte 24/7</h4>
                <p class="text-gray-600 text-sm">¿Necesitas ayuda? Llámanos al +51 999 999 999</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .product-card {
        position: relative;
        transition: all 0.3s ease;
    }

    .product-card:hover {
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .hover-scale {
        transition: transform 0.3s ease;
    }

    .hover-scale:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@push('scripts')
<script>
    function aplicarFiltros() {
        const search = document.getElementById('searchInput').value;
        const categoria = document.getElementById('filterCategoria').value;
        //const orden = document.getElementById('filterOrden').value;

        let url = '{{ route("productos.index") }}?';
        const params = [];

        if (search) params.push(`search=${encodeURIComponent(search)}`);
        if (categoria) params.push(`tipo=${categoria}`);
        //if (orden) params.push(`orden=${orden}`);

        window.location.href = url + params.join('&');
    }

    function filtrarPorTipo(tipo) {
        document.getElementById('filterCategoria').value = tipo;
        aplicarFiltros();
    }

    function mostrarOfertas() {
        // Implementar lógica de ofertas
        alert('Próximamente: Sección de ofertas especiales');
    }

    function limpiarFiltros() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterCategoria').value = '';
        //document.getElementById('filterOrden').value = 'nuevos';
        aplicarFiltros();
    }

    // Función addToCart debería estar en el layout del cliente
    // Si no está, la definimos aquí
    if (typeof window.addToCart !== 'function') {
        window.addToCart = function(productId, nombre, precio) {
            // Implementación básica si no existe
            alert(`Agregado al carrito: ${nombre}`);
        };
    }

    // Aplicar filtros con Enter
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') aplicarFiltros();
    });
</script>
@endpush
@endsection
