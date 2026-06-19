@extends('layouts.cliente')

@section('title', 'Dashboard Cliente')
@section('page-title', 'Bienvenido a Licorería Online')

@section('content')
<div class="space-y-8">
    <!-- Banner Principal -->
    <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-2xl shadow-xl p-8 text-white">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">¡Hola, {{ Auth::user()->nombre }}!</h1>
                <p class="text-red-100 mb-4">¿Listo para tu próxima pedido? Tenemos las mejores bebidas para ti.</p>
                <a href="{{ route('productos.index') }}"
                   class="inline-block bg-white text-red-600 font-semibold px-6 py-3 rounded-lg hover:bg-gray-100 transition">
                    <i class="fas fa-store mr-2"></i>Ver Catálogo Completo
                </a>
            </div>
            <div class="mt-6 md:mt-0">
                <i class="fas fa-wine-glass-alt text-6xl opacity-50"></i>
            </div>
        </div>
    </div>

    <!-- Estadísticas del Cliente -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <i class="fas fa-shopping-cart text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pedidos Totales</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pedidos->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pedidos Entregados</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pedidos_entregados ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pedidos Pendientes</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $pedidos_pendientes }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Catálogo Destacado -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Productos Destacados</h2>
            <a href="{{ route('productos.index') }}" class="text-red-600 hover:text-red-800 font-medium">
                Ver todos <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @php
            $productosDestacados = App\Models\Producto::where('activo', true)
                ->inRandomOrder()
                ->limit(4)
                ->get();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($productosDestacados as $producto)
            <div class="product-card bg-white border rounded-lg overflow-hidden hover:shadow-lg transition">
                @if($producto->imagen)
                <div class="h-40 bg-gray-200 flex items-center justify-center">
                    <img src="/storage/{{ $producto->imagen }}" alt="{{ $producto->nombre }}" class="h-full w-full object-cover">
                </div>
                @else
                <div class="h-40 bg-gray-100 flex items-center justify-center">
                    <i class="fas fa-wine-bottle text-gray-400 text-4xl"></i>
                </div>
                @endif

                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-semibold text-gray-800">{{ $producto->nombre }}</h3>
                        <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">
                            {{ $producto->tipo }}
                        </span>
                    </div>

                    <p class="text-sm text-gray-600 mb-3 truncate">{{ $producto->descripcion }}</p>

                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-xl font-bold text-red-600">Bs {{ number_format($producto->precio, 2) }}</p>
                            <p class="text-xs text-gray-500">Stock: {{ $producto->cantidad }}</p>
                        </div>

                        <button onclick="addToCart({{ $producto->id }}, '{{ $producto->nombre }}', {{ $producto->precio }})"
                                class="bg-red-600 text-white p-2 rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Pedidos Recientes -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Tus Pedidos Recientes</h2>
            <a href="{{ route('pedidos.index') }}" class="text-red-600 hover:text-red-800 font-medium">
                Ver historial completo
            </a>
        </div>

        @if($pedidos->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Pedido #</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Fecha</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Total</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Estado</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pedidos as $pedido)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3">
                            <span class="font-mono">#{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="py-3">
                            {{ $pedido->fecha_pedido ? $pedido->fecha_pedido->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="py-3">
                            <span class="font-semibold">Bs {{ number_format($pedido->monto_total, 2) }}</span>
                        </td>
                        <td class="py-3">
                            @php
                                $statusColors = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'enviado' => 'bg-blue-100 text-blue-800',
                                    'entregado' => 'bg-green-100 text-green-800',
                                    'cancelado' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$pedido->estado] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($pedido->estado) }}
                            </span>
                        </td>
                        <td class="py-3">
                            <div class="flex space-x-2">
                                <a href="{{ route('pedidos.show', $pedido) }}"
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>

                                @if($pedido->venta && $pedido->venta->metodo_pago == 'qr' && $pedido->venta->estado == 'pendiente')
                                <button onclick="subirComprobante({{ $pedido->venta->id }})"
                                        class="text-green-600 hover:text-green-800">
                                    <i class="fas fa-upload"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $pedidos->links() }}
            </div>
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-shopping-cart text-gray-400 text-4xl mb-3"></i>
            <p class="text-gray-600 mb-4">Aún no has realizado ningún pedido</p>
            <a href="{{ route('productos.index') }}"
               class="inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700">
                Realizar mi primer pedido
            </a>
        </div>
        @endif
    </div>
</div>

<!-- Modal para subir comprobante -->
<div id="modalComprobante" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Subir Comprobante QR</h3>
            <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="formComprobante" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="venta_id" name="venta_id">

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Selecciona la imagen del comprobante</label>
                <input type="file"
                       id="comprobante"
                       name="comprobante"
                       accept="image/*"
                       class="w-full border rounded-lg p-2"
                       required>
                <p class="text-xs text-gray-500 mt-1">Formatos aceptados: JPG, PNG, GIF (Max. 2MB)</p>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Vista previa:</label>
                <div id="preview" class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                    <i class="fas fa-qrcode text-gray-400 text-4xl mb-2"></i>
                    <p class="text-gray-500">La vista previa aparecerá aquí</p>
                </div>
            </div>

            <div class="flex space-x-3">
                <button type="button" onclick="cerrarModal()"
                        class="flex-1 bg-gray-200 text-gray-800 py-2 rounded-lg hover:bg-gray-300">
                    Cancelar
                </button>
                <button type="submit"
                        class="flex-1 bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-upload mr-2"></i>Subir Comprobante
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Vista previa de imagen
    $('#comprobante').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview').html(`
                    <img src="${e.target.result}" alt="Vista previa" class="max-h-48 mx-auto rounded">
                    <p class="text-sm text-gray-600 mt-2">${file.name}</p>
                `);
            }
            reader.readAsDataURL(file);
        }
    });

    function subirComprobante(ventaId) {
        $('#venta_id').val(ventaId);
        $('#formComprobante').attr('action', `/ventas/${ventaId}/subir-comprobante`);
        $('#modalComprobante').removeClass('hidden');
    }

    function cerrarModal() {
        $('#modalComprobante').addClass('hidden');
        $('#comprobante').val('');
        $('#preview').html(`
            <i class="fas fa-qrcode text-gray-400 text-4xl mb-2"></i>
            <p class="text-gray-500">La vista previa aparecerá aquí</p>
        `);
    }

    // Enviar formulario de comprobante
    $('#formComprobante').submit(function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                showNotification('Comprobante subido exitosamente', 'success');
                cerrarModal();
                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                showNotification('Error al subir el comprobante', 'error');
            }
        });
    });

    $(document).keyup(function(e) {
        if (e.key === "Escape") {
            cerrarModal();
        }
    });
</script>
@endpush
@endsection
