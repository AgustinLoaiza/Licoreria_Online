@extends('layouts.admin')

@section('title', 'Venta #' . str_pad($venta->id, 6, '0', STR_PAD_LEFT))
@section('page-title', 'Detalle de Venta')
@section('page-description', 'Información completa de la transacción')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Encabezado -->
    <div class="bg-white rounded-xl shadow mb-6">
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    Venta #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}
                </h2>
                <p class="text-gray-600">
                    <i class="far fa-clock mr-1"></i>
                    {{ $venta->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
            <div class="text-right">
                @php
                    $estadoColors = [
                        'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                        'pagado' => 'bg-green-100 text-green-800 border-green-300',
                        'verificado' => 'bg-blue-100 text-blue-800 border-blue-300',
                        'rechazado' => 'bg-red-100 text-red-800 border-red-300'
                    ];
                @endphp
                <div class="inline-flex items-center px-4 py-2 rounded-lg border text-lg {{ $estadoColors[$venta->estado] ?? 'bg-gray-100 text-gray-800 border-gray-300' }}">
                    <i class="fas fa-{{ $venta->estado == 'pendiente' ? 'clock' :
                                       ($venta->estado == 'pagado' ? 'check-circle' :
                                       ($venta->estado == 'verificado' ? 'shield-check' : 'times-circle')) }} mr-2"></i>
                    <span class="font-bold">{{ ucfirst($venta->estado) }}</span>
                </div>
                <p class="text-3xl font-bold text-gray-800 mt-2">
                    Bs {{ number_format($venta->pedido->monto_total ?? 0, 2) }}
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Información del pedido -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Información del Pedido</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Pedido #</p>
                            <p class="font-bold text-lg text-gray-800">
                                #{{ str_pad($venta->pedido->id, 6, '0', STR_PAD_LEFT) }}
                            </p>
                            <p class="text-sm text-gray-600">
                                {{ $venta->pedido->nombre_pedido ?? 'Sin nombre específico' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Estado del pedido</p>
                            @php
                                $pedidoColors = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'confirmado' => 'bg-blue-100 text-blue-800',
                                    'en_preparacion' => 'bg-purple-100 text-purple-800',
                                    'en_camino' => 'bg-indigo-100 text-indigo-800',
                                    'entregado' => 'bg-green-100 text-green-800',
                                    'cancelado' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $pedidoColors[$venta->pedido->estado] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst(str_replace('_', ' ', $venta->pedido->estado)) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Fecha del pedido</p>
                            <p class="font-medium">
                                {{ $venta->pedido->fecha_pedido ? $venta->pedido->fecha_pedido->format('d/m/Y H:i') : $venta->pedido->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Dirección de entrega</p>
                            <p class="font-medium">{{ $venta->pedido->direccion }}</p>
                        </div>
                    </div>
                    <!-- Productos del pedido -->
                    <div class="mt-6 pt-6 border-t">
                        <h4 class="font-semibold text-gray-800 mb-3">Productos vendidos</h4>
                        <div class="space-y-3">
                            @foreach($venta->pedido->detalles as $detalle)
                            <div class="flex items-center justify-between p-3 border rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                        @if($detalle->producto && $detalle->producto->imagen)
                                        <img src="{{ asset('storage/' . $detalle->producto->imagen) }}"
                                             alt="{{ $detalle->producto->nombre }}"
                                             class="h-full w-full object-cover rounded-lg">
                                        @else
                                        <i class="fas fa-wine-bottle text-gray-400"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $detalle->producto->nombre ?? 'Producto no disponible' }}</p>
                                        <p class="text-sm text-gray-600">{{ $detalle->producto->tipo ?? 'Sin categoria' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">{{ $detalle->cantidad }} x Bs {{ number_format($detalle->precio_unitario, 2) }}</p>
                                    <p class="font-bold">Bs {{ number_format($detalle->subtotal, 2) }}</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Información del pago -->
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Información de Pago</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Método de pago</p>
                            <div class="flex items-center mt-2">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center mr-3
                                    {{ $venta->metodo_pago == 'qr' ? 'bg-green-100 text-green-600' :
                                       ($venta->metodo_pago == 'efectivo' ? 'bg-yellow-100 text-yellow-600' :
                                       'bg-blue-100 text-blue-600') }}">
                                    <i class="fas fa-{{ $venta->metodo_pago == 'qr' ? 'qrcode' :
                                                       ($venta->metodo_pago == 'efectivo' ? 'money-bill-wave' :
                                                       'credit-card') }} text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-bold text-xl text-gray-800">{{ ucfirst($venta->metodo_pago) }}</p>
                                    <p class="text-sm text-gray-600">
                                        @if($venta->fecha_pago)
                                        Pagado: {{ $venta->fecha_pago->format('d/m/Y H:i') }}
                                        @else
                                        Pendiente de pago
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Verificación</p>
                            <div class="space-y-2">
                                @if($venta->verificado_por)
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <i class="fas fa-user-check text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">Verificado por administrador</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $venta->fecha_verificacion->format('d/m/Y H:i') }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                                @if($venta->notas)
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Notas</p>
                                    <p class="text-sm bg-gray-50 p-3 rounded-lg">{{ $venta->notas }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- Comprobante QR -->
                    @if($venta->metodo_pago == 'qr' && $venta->comprobante)
                    <div class="mt-6 pt-6 border-t">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="font-semibold text-gray-800">Comprobante de pago QR</h4>
                            <div class="flex space-x-2">
                                <button onclick="verComprobante('{{ asset('storage/' . $venta->comprobante) }}')"
                                        class="text-blue-600 hover:text-blue-800 flex items-center" type="button">
                                    <i class="fas fa-eye mr-1"></i> Ver
                                </button>
                                <a href="{{ asset('storage/' . $venta->comprobante) }}"
                                   target="_blank"
                                   class="text-green-600 hover:text-green-800 flex items-center">
                                    <i class="fas fa-download mr-1"></i> Descargar
                                </a>
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg flex items-center justify-center">
                            <img src="{{ asset('storage/' . $venta->comprobante) }}" alt="Comprobante QR" class="max-h-80 rounded-lg shadow">
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- Información del cliente -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Cliente</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-100 rounded-full h-16 w-16 flex items-center justify-center">
                            <i class="fas fa-user text-gray-400 text-3xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">{{ $venta->pedido->usuario->nombre }}</p>
                            <p class="text-gray-600">{{ $venta->pedido->usuario->email }}</p>
                            <p class="text-gray-600">{{ $venta->pedido->usuario->telefono ?? 'Sin teléfono' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Información del repartidor -->
            @if($venta->pedido->delivery)
            <div class="bg-white rounded-xl shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Repartidor</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-100 rounded-full h-16 w-16 flex items-center justify-center">
                            <i class="fas fa-motorcycle text-gray-400 text-3xl"></i>
                        </div>
                        <div>
                            <p class="font-bold text-lg">{{ $venta->pedido->delivery->usuario->nombre }}</p>
                            <p class="text-gray-600">{{ $venta->pedido->delivery->usuario->email }}</p>
                            <p class="text-gray-600">{{ $venta->pedido->delivery->usuario->telefono ?? 'Sin teléfono' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- Botón volver -->
    <div class="mt-8">
        <a href="{{ route('ventas.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la lista
        </a>
    </div>
</div>

<!-- Modal para ver comprobante -->
<div id="modalComprobante" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h4 class="font-semibold text-gray-800">Comprobante de pago QR</h4>
            <button onclick="cerrarComprobante()" class="text-gray-500 hover:text-gray-800 text-2xl">&times;</button>
        </div>
        <div class="flex items-center justify-center">
            <img id="imgComprobante" src="" alt="Comprobante QR" class="max-h-96 rounded-lg shadow">
        </div>
    </div>
</div>

@push('scripts')
<script>
    function verComprobante(url) {
        document.getElementById('imgComprobante').src = url;
        document.getElementById('modalComprobante').classList.remove('hidden');
    }
    function cerrarComprobante() {
        document.getElementById('modalComprobante').classList.add('hidden');
        document.getElementById('imgComprobante').src = '';
    }
</script>
@endpush
@endsection
