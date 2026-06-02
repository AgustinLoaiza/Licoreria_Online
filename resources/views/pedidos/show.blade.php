@extends(Auth::user()->rol === 'administrador' ? 'layouts.admin' : (Auth::user()->rol === 'repartidor' ? 'layouts.repartidor' : 'layouts.cliente'))

@section('title', 'Pedido #' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT))

@if(Auth::user()->rol === 'administrador')
    @section('page-title', 'Detalle del Pedido')
@elseif(Auth::user()->rol === 'repartidor')
    @section('page-title', 'Detalle de Entrega')
@else
    @section('page-title', 'Mi Pedido')
@endif

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Encabezado del pedido -->
    <div class="bg-white rounded-xl shadow mb-6 overflow-hidden">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Pedido #{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}
                    @if($pedido->nombre_pedido)
                    <span class="text-gray-600 text-lg font-normal ml-2">- {{ $pedido->nombre_pedido }}</span>
                    @endif
                </h2>
                <p class="text-gray-600">
                    <i class="far fa-clock mr-1"></i>
                    {{ $pedido->fecha_pedido ? $pedido->fecha_pedido->format('d/m/Y H:i') : '-' }}
                </p>
            </div>

            <div class="text-right">
                <!-- Estado -->
                @php
                    $statusColors = [
                        'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'enviado' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'entregado' => 'bg-green-100 text-green-800 border-green-300',
                        'cancelado' => 'bg-red-100 text-red-800 border-red-300'
                    ];
                    $statusIcons = [
                        'pendiente' => 'fa-clock',
                        'enviado' => 'fa-check',
                        'entregado' => 'fa-box',
                        'cancelado' => 'fa-times'
                    ];
                @endphp
                <div class="inline-flex items-center px-4 py-2 rounded-lg border text-lg {{ $statusColors[$pedido->estado] }}">
                    <i class="fas {{ $statusIcons[$pedido->estado] }} mr-2"></i>
                    <span class="font-bold">{{ ucfirst($pedido->estado) }}</span>
                </div>

                <!-- Total -->
                <p class="text-3xl font-bold text-gray-800 mt-2">S/ {{ number_format($pedido->monto_total, 2) }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Columna izquierda: Información general -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Productos del pedido -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Productos</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($pedido->detalles as $detalle)
                        <div class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                    @if($detalle->producto->imagen)
                                    <img src="{{ asset('storage/' . $detalle->producto->imagen) }}"
                                         alt="{{ $detalle->producto->nombre }}"
                                         class="h-full w-full object-cover rounded-lg">
                                    @else
                                    <i class="fas fa-wine-bottle text-gray-400 text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $detalle->producto->nombre }}</p>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <span class="bg-gray-100 px-2 py-1 rounded mr-2">{{ $detalle->producto->tipo }}</span>
                                        <span>Stock: {{ $detalle->producto->cantidad }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $detalle->cantidad }} x S/ {{ number_format($detalle->precio_unitario, 2) }}</p>
                                <p class="text-lg font-bold text-gray-800">S/ {{ number_format($detalle->subtotal, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Resumen de precios -->
                    <div class="mt-6 pt-6 border-t">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-semibold">S/ {{ number_format($pedido->monto_total, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Delivery:</span>
                                <span class="font-semibold">S/ 0.00</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t pt-2">
                                <span>Total:</span>
                                <span>S/ {{ number_format($pedido->monto_total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del pago -->
            @if($pedido->venta)
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Información de Pago</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Método de pago</p>
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center mr-3
                                    {{ $pedido->venta->metodo_pago == 'qr' ? 'bg-green-100 text-green-600' :
                                       ($pedido->venta->metodo_pago == 'efectivo' ? 'bg-yellow-100 text-yellow-600' :
                                       'bg-blue-100 text-blue-600') }}">
                                    <i class="fas fa-{{ $pedido->venta->metodo_pago == 'qr' ? 'qrcode' :
                                                       ($pedido->venta->metodo_pago == 'efectivo' ? 'money-bill-wave' :
                                                       'credit-card') }}"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ ucfirst($pedido->venta->metodo_pago) }}</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $pedido->venta->fecha_pago ? $pedido->venta->fecha_pago->format('d/m/Y H:i') : 'Pendiente' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-gray-600 mb-1">Estado del pago</p>
                            @php
                                $pagoColors = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'pagado' => 'bg-green-100 text-green-800 border-green-300',
                                    'verificado' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'rechazado' => 'bg-red-100 text-red-800 border-red-300'
                                ];
                                $pagoIcons = [
                                    'pendiente' => 'fa-clock',
                                    'pagado' => 'fa-check-circle',
                                    'verificado' => 'fa-shield-check',
                                    'rechazado' => 'fa-times-circle'
                                ];
                            @endphp
                            <div class="inline-flex items-center px-4 py-2 rounded-lg border {{ $pagoColors[$pedido->venta->estado] }}">
                                <i class="fas {{ $pagoIcons[$pedido->venta->estado] }} mr-2"></i>
                                <span class="font-semibold">{{ ucfirst($pedido->venta->estado) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Comprobante QR -->
                    @if($pedido->venta->metodo_pago == 'qr' && $pedido->venta->comprobante)
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-sm text-gray-600 mb-3">Comprobante de pago</p>
                        <div class="flex items-center space-x-4">
                            <div class="bg-gray-100 p-4 rounded-lg">
                                <i class="fas fa-qrcode text-gray-400 text-4xl"></i>
                            </div>
                            <div>
                                <p class="font-medium">Comprobante subido</p>
                                <p class="text-sm text-gray-600">
                                    {{ $pedido->venta->updated_at->format('d/m/Y H:i') }}
                                </p>
                                <div class="mt-2 flex space-x-2">
                                    <button onclick="verComprobante('{{ $pedido->venta->comprobante }}')"
                                            class="text-blue-600 hover:text-blue-800 flex items-center">
                                        <i class="fas fa-eye mr-1"></i> Ver
                                    </button>
                                    <a href="{{ asset('storage/' . $pedido->venta->comprobante) }}"
                                       target="_blank"
                                       class="text-green-600 hover:text-green-800 flex items-center">
                                        <i class="fas fa-download mr-1"></i> Descargar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Para cliente: subir comprobante -->
                    @if(Auth::user()->rol === 'cliente' && $pedido->venta &&
                        $pedido->venta->metodo_pago == 'qr' && $pedido->venta->estado == 'pendiente')
                    <div class="mt-6 pt-6 border-t">
                        <p class="text-sm text-gray-600 mb-3">Subir comprobante de pago</p>
                        <form action="{{ route('ventas.subir-comprobante', $pedido->venta) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="space-y-4">
                            @csrf
                            <div>
                                <input type="file"
                                       name="comprobante"
                                       accept="image/*"
                                       class="block w-full text-sm text-gray-500
                                              file:mr-4 file:py-2 file:px-4
                                              file:rounded-lg file:border-0
                                              file:text-sm file:font-medium
                                              file:bg-blue-50 file:text-blue-700
                                              hover:file:bg-blue-100"
                                       required>
                                <p class="text-xs text-gray-500 mt-1">
                                    Toma una foto del QR pagado. Formatos: JPG, PNG, GIF. Max: 2MB
                                </p>
                            </div>
                            <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                                <i class="fas fa-upload mr-2"></i> Subir Comprobante
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Columna derecha: Información de entrega y cliente -->
        <div class="space-y-6">
            <!-- Información del cliente -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">
                        @if(Auth::user()->rol === 'cliente')
                        Mi Información
                        @else
                        Información del Cliente
                        @endif
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $pedido->usuario->nombre }}</p>
                            <p class="text-sm text-gray-600">{{ $pedido->usuario->email }}</p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center text-gray-700">
                            <i class="fas fa-phone text-gray-400 w-5 mr-3"></i>
                            <span>{{ $pedido->usuario->telefono ?? 'No registrado' }}</span>
                        </div>

                        <div class="flex items-start text-gray-700">
                            <i class="fas fa-map-marker-alt text-gray-400 w-5 mr-3 mt-1"></i>
                            <div>
                                <p class="font-medium">Dirección de entrega</p>
                                <p class="text-sm">{{ $pedido->direccion }}</p>
                            </div>
                        </div>

                        @if(Auth::user()->rol !== 'cliente')
                        <div class="mt-4 pt-4 border-t">
                            <a href="tel:{{ $pedido->usuario->telefono }}"
                               class="block w-full bg-blue-600 text-white text-center py-2 rounded-lg hover:bg-blue-700">
                                <i class="fas fa-phone mr-2"></i> Llamar al cliente
                            </a>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            <!-- Información de entrega -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Información de Entrega</h3>
                </div>
                <div class="p-6">
                    {{-- Solo si no hay repartidor asignado --}}
                        @if(Auth::user()->rol === 'administrador' && !$pedido->delivery_id)
                            <form action="{{ route('pedidos.asignar-repartidor', $pedido) }}" method="POST" class="mt-4 space-y-2">
                                @csrf
                                <label for="delivery_id" class="block text-sm font-medium text-gray-700 mb-2">Asignar repartidor disponible:</label>
                                <select name="delivery_id" id="delivery_id" class="w-full border rounded-lg px-4 py-2 mb-2" required>
                                    <option value="">Selecciona un repartidor</option>
                                    @foreach(\App\Models\Delivery::where('estado', 'disponible')->with('usuario')->get() as $delivery)
                                        <option value="{{ $delivery->id }}">
                                            {{ $delivery->usuario->nombre }} ({{ $delivery->usuario->telefono }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                                    Asignar repartidor
                                </button>
                            </form>
                        @endif

                    @if($pedido->delivery)

                    <div class="mb-4">
                        <div class="flex items-center mb-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-motorcycle text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $pedido->delivery->usuario->nombre }}</p>
                                <p class="text-sm text-gray-600">Repartidor asignado</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center">
                                <i class="fas fa-phone text-gray-400 w-4 mr-2"></i>
                                <span class="text-sm">{{ $pedido->delivery->usuario->telefono ?? 'Sin teléfono' }}</span>
                            </div>
                            <div class="flex items-center">
                                @php
                                    $estadoColors = [
                                        'disponible' => 'text-green-600 bg-green-100',
                                        'ocupado' => 'text-yellow-600 bg-yellow-100',
                                        'inactivo' => 'text-red-600 bg-red-100'
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-medium {{ $estadoColors[$pedido->delivery->estado] }}">
                                    {{ ucfirst($pedido->delivery->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-motorcycle text-gray-300 text-3xl mb-2"></i>
                        <p class="text-gray-600">Sin repartidor asignado</p>
                    </div>
                    @endif

                    <!-- Acciones para repartidor -->
                    @if(Auth::user()->rol === 'repartidor')
                    <div class="mt-4 space-y-2">
                        @if($pedido->estado == 'confirmado')
                        <button onclick="cambiarEstado('en_camino')"
                                class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700 flex items-center justify-center">
                            <i class="fas fa-play mr-2"></i> Iniciar Entrega
                        </button>
                        @endif

                        @if($pedido->estado == 'en_camino')
                        <button onclick="cambiarEstado('entregado')"
                                class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 flex items-center justify-center">
                            <i class="fas fa-check mr-2"></i> Marcar como Entregado
                        </button>

                        <button onclick="abrirMapa()"
                                class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2"></i> Ver en Mapa
                        </button>
                        @endif
                    </div>
                    @endif

                    <!-- Acciones para admin -->
                    @if(Auth::user()->rol === 'admin')
                    <div class="mt-4 space-y-2">
                        @if(in_array($pedido->estado, ['pendiente', 'confirmado', 'en_preparacion']))
                        <a href="{{ route('pedidos.edit', $pedido) }}"
                           class="block w-full bg-yellow-500 text-white text-center py-2 rounded-lg hover:bg-yellow-600">
                            <i class="fas fa-edit mr-2"></i> Editar Pedido
                        </a>
                        @endif

                        @if(!in_array($pedido->estado, ['entregado', 'cancelado']))
                        <button onclick="cancelarPedido()"
                                class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i> Cancelar Pedido
                        </button>
                        @endif
                    </div>
                    @endif

                </div>
            </div>

            <!-- Historial de estados -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Historial del Pedido</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-shopping-cart text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Pedido creado</p>
                                <p class="text-sm text-gray-600">{{ $pedido->fecha_pedido ? $pedido->fecha_pedido->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>

                        @if($pedido->venta && $pedido->venta->fecha_pago)
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-credit-card text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Pago {{ $pedido->venta->estado }}</p>
                                <p class="text-sm text-gray-600">{{ $pedido->venta->fecha_pago ? $pedido->venta->fecha_pago->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>
                        @endif

                        @if($pedido->estado == 'entregado')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <i class="fas fa-box text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">Pedido entregado</p>
                                <p class="text-sm text-gray-600">{{ $pedido->updated_at ? $pedido->updated_at->format('d/m/Y H:i') : '-' }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de navegación -->
    <div class="mt-6 flex justify-between">
        <a href="{{ route('pedidos.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la lista
        </a>

        <!-- Para cliente: botón de WhatsApp -->
        @if(Auth::user()->rol === 'cliente')
        <a href="https://wa.me/51999999999?text=Hola,%20tengo%20una%20consulta%20sobre%20mi%20pedido%20%23{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}"
           target="_blank"
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
            <i class="fab fa-whatsapp mr-2"></i> Consultar por WhatsApp
        </a>
        @endif
    </div>
</div>

<!-- Modal para ver comprobante -->
<div id="modalComprobante" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Comprobante de Pago</h3>
            <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modalContent" class="text-center">
            <!-- Contenido dinámico -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    function verComprobante(url) {
        $('#modalContent').html(`
            <img src="/storage/${url}" alt="Comprobante" class="rounded-lg mx-auto max-h-96">
            <div class="mt-4 flex justify-center space-x-3">
                <a href="/storage/${url}" target="_blank"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-expand mr-2"></i> Ver completo
                </a>
                <a href="/storage/${url}" download
                   class="inline-block bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i> Descargar
                </a>
            </div>
        `);
        $('#modalComprobante').removeClass('hidden');
    }

    function cerrarModal() {
        $('#modalComprobante').addClass('hidden');
    }

    function cambiarEstado(nuevoEstado) {
        if (!confirm('¿Estás seguro de cambiar el estado del pedido?')) return;

        fetch(`{{ route('pedidos.cambiar-estado', $pedido) }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ estado: nuevoEstado })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cambiar el estado');
        });
    }

    function cancelarPedido() {
        if (confirm('¿Estás seguro de cancelar este pedido?')) {
            cambiarEstado('cancelado');
        }
    }

    function abrirMapa() {
        const direccion = encodeURIComponent('{{ $pedido->direccion }}');
        window.open(`https://www.google.com/maps/search/?api=1&query=${direccion}`, '_blank');
    }

    // Cerrar modal con ESC
    $(document).keyup(function(e) {
        if (e.key === "Escape") cerrarModal();
    });
</script>
@endpush
@endsection
