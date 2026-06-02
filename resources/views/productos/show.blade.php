@extends(Auth::user()->rol === 'administrador' ? 'layouts.admin' : (Auth::user()->rol === 'repartidor' ? 'layouts.admin' : 'layouts.cliente'))

@section('title', $producto->nombre . ' - Licorería')

@if(Auth::user()->rol === 'administrador' || Auth::user()->rol === 'repartidor')
    @section('page-title', 'Detalle del Producto')
    @section('page-description', 'Información completa del producto')
@else
    @section('page-title', $producto->nombre)
    @section('page-description', 'Detalle del producto')
@endif

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $producto->nombre }}</h2>
                <p class="text-gray-600">Código: PROD-{{ str_pad($producto->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            @if(Auth::user()->rol === 'administrador' || Auth::user()->rol === 'repartidor')
            <div class="flex space-x-2">
                <a href="{{ route('productos.edit', $producto) }}"
                   class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <button onclick="eliminarProducto({{ $producto->id }}, '{{ $producto->nombre }}')"
                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 flex items-center">
                    <i class="fas fa-trash mr-2"></i> Eliminar
                </button>
            </div>
            @else
            <div>
                <button onclick="addToCart({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->precio }})"
                        class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 flex items-center text-lg">
                    <i class="fas fa-cart-plus mr-2"></i> Agregar al Carrito
                </button>
            </div>
            @endif
        </div>

        <!-- Contenido -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Imagen -->
                <div>
                    @if($producto->imagen)
                    <div class="bg-gray-100 rounded-xl overflow-hidden h-96 flex items-center justify-center">
                        <img src="{{ asset('storage/' . $producto->imagen) }}"
                             alt="{{ $producto->nombre }}"
                             class="max-h-full max-w-full object-contain">
                    </div>
                    @else
                    <div class="bg-gray-100 rounded-xl h-96 flex items-center justify-center">
                        <i class="fas fa-wine-bottle text-gray-400 text-8xl"></i>
                    </div>
                    @endif
                </div>

                <!-- Información -->
                <div class="space-y-6">
                    <!-- Estado y Tipo -->
                    <div class="flex space-x-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $producto->tipo == 'bebida' ? 'bg-blue-100 text-blue-800' :
                               ($producto->tipo == 'comida' ? 'bg-green-100 text-green-800' :
                               ($producto->tipo == 'combo' ? 'bg-purple-100 text-purple-800' :
                               'bg-yellow-100 text-yellow-800')) }}">
                            {{ ucfirst($producto->tipo) }}
                        </span>

                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $producto->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </div>

                    <!-- Precio -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Precio</h3>
                        <p class="text-4xl font-bold text-red-600">S/ {{ number_format($producto->precio, 2) }}</p>
                    </div>

                    <!-- Stock -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Stock Disponible</h3>
                        <div class="flex items-center">
                            <div class="w-full bg-gray-200 rounded-full h-3 mr-4">
                                @php
                                    $porcentaje = min(100, ($producto->cantidad / 100) * 100);
                                    $color = $producto->cantidad > 50 ? 'bg-green-600' :
                                            ($producto->cantidad > 20 ? 'bg-yellow-500' : 'bg-red-600');
                                @endphp
                                <div class="h-3 rounded-full {{ $color }}" style="width: {{ $porcentaje }}%"></div>
                            </div>
                            <span class="text-2xl font-bold {{ $producto->cantidad < 10 ? 'text-red-600' : 'text-gray-800' }}">
                                {{ $producto->cantidad }} unidades
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">
                            @if($producto->cantidad > 50)
                                <i class="fas fa-check-circle text-green-500 mr-1"></i> Stock suficiente
                            @elseif($producto->cantidad > 20)
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-1"></i> Stock moderado
                            @elseif($producto->cantidad > 0)
                                <i class="fas fa-exclamation-triangle text-red-500 mr-1"></i> Stock bajo
                            @else
                                <i class="fas fa-times-circle text-red-500 mr-1"></i> Agotado
                            @endif
                        </p>
                    </div>

                    <!-- Descripción -->
                    @if($producto->descripcion)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Descripción</h3>
                        <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $producto->descripcion }}</p>
                    </div>
                    @endif

                    <!-- Información Adicional -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Información Adicional</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Creado</p>
                                <p class="font-medium">{{ $producto->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Última actualización</p>
                                <p class="font-medium">{{ $producto->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Para clientes: Cantidad a agregar -->
                    @if(Auth::user()->rol === 'cliente')
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Cantidad</h3>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border rounded-lg">
                                <button onclick="updateQuantity(-1)"
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number"
                                       id="quantity"
                                       value="1"
                                       min="1"
                                       max="{{ $producto->cantidad }}"
                                       class="w-16 text-center border-0 focus:outline-none">
                                <button onclick="updateQuantity(1)"
                                        class="px-4 py-2 text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <button onclick="addToCartWithQuantity()"
                                    class="flex-1 bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 text-lg">
                                <i class="fas fa-shopping-cart mr-2"></i> Agregar al Carrito
                            </button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Volver -->
    <div class="mt-6">
        <a href="{{ route('productos.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la lista
        </a>
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

    // Para clientes
    @if(Auth::user()->rol === 'cliente')
    function updateQuantity(change) {
        const input = document.getElementById('quantity');
        let value = parseInt(input.value) + change;
        const max = parseInt(input.max);

        if (value < 1) value = 1;
        if (value > max) value = max;

        input.value = value;
    }

    function addToCartWithQuantity() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const productId = {{ $producto->id }};
        const nombre = '{{ $producto->nombre }}';
        const precio = {{ $producto->precio }};

        if (quantity < 1) {
            alert('La cantidad debe ser al menos 1');
            return;
        }

        if (quantity > {{ $producto->cantidad }}) {
            alert('No hay suficiente stock disponible');
            return;
        }

        // Agregar múltiples veces al carrito
        for (let i = 0; i < quantity; i++) {
            if (typeof window.addToCart === 'function') {
                window.addToCart(productId, nombre, precio);
            }
        }
    }
    @endif

    // Validar cantidad en input
    document.getElementById('quantity')?.addEventListener('input', function() {
        const max = parseInt(this.max);
        const value = parseInt(this.value);

        if (value < 1) this.value = 1;
        if (value > max) this.value = max;
    });
</script>
@endpush
@endsection
