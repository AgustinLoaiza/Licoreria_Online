@extends(Auth::user()->rol === 'administrador' ? 'layouts.admin' : (Auth::user()->rol === 'repartidor' ? 'layouts.repartidor' : 'layouts.cliente'))

@section('title', 'Pedidos - Licorería')

@if(Auth::user()->rol === 'administrador')
    @section('page-title', 'Gestión de Pedidos')
    @section('page-description', 'Administra todos los pedidos del sistema')
@elseif(Auth::user()->rol === 'repartidor')
    @section('page-title', 'Mis Pedidos Asignados')
    @section('page-description', 'Pedidos asignados para entrega')
@else
    @section('page-title', 'Mis Pedidos')
    @section('page-description', 'Historial de tus pedidos')
@endif

@section('content')
<div class="space-y-6">
    <!-- Header con acciones -->
    @if(Auth::user()->rol !== 'repartidor')
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pedidos</h2>
            <p class="text-gray-600">Total: {{ $pedidos->total() }} pedidos</p>
        </div>
        @if(Auth::user()->rol === 'cliente')
        <div>
            <a href="{{ route('pedidos.create') }}"
               class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 flex items-center text-lg">
                <i class="fas fa-plus mr-2"></i> Nuevo Pedido
            </a>
        </div>
        @endif
    </div>
    @endif

    <!-- Filtros -->
    @if(Auth::user()->rol === 'administrador' || Auth::user()->rol === 'repartidor')
    <div class="bg-white rounded-xl shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Filtro por estado -->
            <div>
                <select id="filterEstado" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="confirmado">Confirmado</option>
                    <option value="en_preparacion">En preparación</option>
                    <option value="en_camino">En camino</option>
                    <option value="entregado">Entregado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>

            <!-- Filtro por fecha -->
            <div>
                <input type="date"
                       id="filterFecha"
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Búsqueda (solo admin) -->
            @if(Auth::user()->rol === 'administrador')
            <div>
                <input type="text"
                       id="searchInput"
                       placeholder="Buscar por cliente..."
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            @endif

            <!-- Botón de filtro -->
            <div>
                <button onclick="aplicarFiltros()"
                        class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Tarjetas de pedidos -->
    <div class="grid grid-cols-1 gap-6">
        @forelse($pedidos as $pedido)
        <div class="bg-white rounded-xl shadow overflow-hidden hover:shadow-lg transition">
            <div class="p-6">
                <!-- Encabezado del pedido -->
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">
                            Pedido #{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}
                            @if($pedido->nombre_pedido)
                            <span class="text-gray-600 text-lg font-normal ml-2">- {{ $pedido->nombre_pedido }}</span>
                            @endif
                        </h3>
                        <p class="text-gray-600">
                            <i class="far fa-clock mr-1"></i>
                            {{ $pedido->fecha_pedido->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="text-right">
                        <!-- Estado -->
                        @php
                            $statusColors = [
                                'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                'confirmado' => 'bg-blue-100 text-blue-800 border-blue-300',
                                'en_preparacion' => 'bg-purple-100 text-purple-800 border-purple-300',
                                'en_camino' => 'bg-indigo-100 text-indigo-800 border-indigo-300',
                                'entregado' => 'bg-green-100 text-green-800 border-green-300',
                                'cancelado' => 'bg-red-100 text-red-800 border-red-300'
                            ];
                            $statusIcons = [
                                'pendiente' => 'fa-clock',
                                'confirmado' => 'fa-check',
                                'en_preparacion' => 'fa-utensils',
                                'en_camino' => 'fa-motorcycle',
                                'entregado' => 'fa-box',
                                'cancelado' => 'fa-times'
                            ];
                        @endphp
                        <div class="inline-flex items-center px-3 py-1 rounded-full border {{ $statusColors[$pedido->estado] }}">
                            <i class="fas {{ $statusIcons[$pedido->estado] }} mr-2"></i>
                            <span class="font-semibold">{{ ucfirst($pedido->estado) }}</span>
                        </div>

                        <!-- Total -->
                        <p class="text-2xl font-bold text-gray-800 mt-2">S/ {{ number_format($pedido->monto_total, 2) }}</p>
                    </div>
                </div>

                <!-- Información del pedido -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Cliente -->
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Cliente</p>
                        <p class="font-medium flex items-center">
                            <i class="fas fa-user mr-2 text-gray-400"></i>
                            {{ $pedido->usuario->nombre }}
                        </p>
                        @if($pedido->usuario->telefono)
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-phone mr-1"></i>
                            {{ $pedido->usuario->telefono }}
                        </p>
                        @endif
                    </div>

                    <!-- Dirección -->
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Dirección de entrega</p>
                        <p class="font-medium flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-gray-400"></i>
                            {{ Str::limit($pedido->direccion, 50) }}
                        </p>
                    </div>

                    <!-- Repartidor (solo admin/repartidor) -->
                    @if(Auth::user()->rol !== 'cliente')
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-500 mb-1">Repartidor</p>
                        @if($pedido->delivery)
                        <p class="font-medium flex items-center">
                            <i class="fas fa-motorcycle mr-2 text-gray-400"></i>
                            {{ $pedido->delivery->usuario->nombre }}
                            <span class="ml-2 px-2 py-1 text-xs rounded-full
                                {{ $pedido->delivery->estado == 'disponible' ? 'bg-green-100 text-green-800' :
                                   ($pedido->delivery->estado == 'ocupado' ? 'bg-yellow-100 text-yellow-800' :
                                   'bg-red-100 text-red-800') }}">
                                {{ ucfirst($pedido->delivery->estado) }}
                            </span>
                        </p>
                        @else
                        <p class="text-gray-500 italic">Sin asignar</p>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Productos del pedido -->
                <div class="border-t pt-4">
                    <h4 class="font-semibold text-gray-700 mb-3">Productos</h4>
                    <div class="space-y-2">
                        @foreach($pedido->detalles->take(3) as $detalle)
                        <div class="flex justify-between items-center">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-wine-bottle text-gray-400"></i>
                                </div>
                                <div>
                                    <p class="font-medium">{{ $detalle->producto->nombre }}</p>
                                    <p class="text-sm text-gray-600">{{ $detalle->cantidad }} x S/ {{ number_format($detalle->precio_unitario, 2) }}</p>
                                </div>
                            </div>
                            <p class="font-semibold">S/ {{ number_format($detalle->subtotal, 2) }}</p>
                        </div>
                        @endforeach

                        @if($pedido->detalles->count() > 3)
                        <p class="text-center text-blue-600 text-sm mt-2">
                            +{{ $pedido->detalles->count() - 3 }} productos más
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Acciones -->
                <div class="border-t pt-4 mt-4 flex justify-between items-center">
                    <div>
                        <!-- Información de pago -->
                        @if($pedido->venta)
                        <div class="flex items-center text-sm">
                            @php
                                $pagoColors = [
                                    'pendiente' => 'text-yellow-600 bg-yellow-100',
                                    'pagado' => 'text-green-600 bg-green-100',
                                    'verificado' => 'text-blue-600 bg-blue-100',
                                    'rechazado' => 'text-red-600 bg-red-100'
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full {{ $pagoColors[$pedido->venta->estado] }} mr-2">
                                Pago: {{ ucfirst($pedido->venta->estado) }}
                            </span>
                            <span class="text-gray-600">
                                <i class="fas fa-{{ $pedido->venta->metodo_pago == 'qr' ? 'qrcode' : 'money-bill-wave' }} mr-1"></i>
                                {{ ucfirst($pedido->venta->metodo_pago) }}
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="flex space-x-2">
                        <!-- Ver detalle -->
                        <a href="{{ route('pedidos.show', $pedido) }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                            <i class="fas fa-eye mr-2"></i> Ver Detalle
                        </a>

                        <!-- Acciones específicas por rol -->
                        @if(Auth::user()->rol === 'admin')
                            @if($pedido->estado == 'pendiente')
                            <button onclick="cambiarEstado({{ $pedido->id }}, 'confirmado')"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                                <i class="fas fa-check mr-2"></i> Confirmar
                            </button>
                            @endif

                            @if(in_array($pedido->estado, ['pendiente', 'confirmado', 'en_preparacion']))
                            <button onclick="cambiarEstado({{ $pedido->id }}, 'cancelado')"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center">
                                <i class="fas fa-times mr-2"></i> Cancelar
                            </button>
                            @endif

                        @elseif(Auth::user()->rol === 'repartidor')
                            @if($pedido->estado == 'confirmado')
                            <button onclick="cambiarEstado({{ $pedido->id }}, 'en_camino')"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 flex items-center">
                                <i class="fas fa-play mr-2"></i> Iniciar Entrega
                            </button>
                            @endif

                            @if($pedido->estado == 'en_camino')
                            <button onclick="cambiarEstado({{ $pedido->id }}, 'entregado')"
                                    class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 flex items-center">
                                <i class="fas fa-check mr-2"></i> Marcar Entregado
                            </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow p-12 text-center">
            <i class="fas fa-shopping-cart text-gray-400 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-800 mb-2">No hay pedidos</h3>
            <p class="text-gray-600 mb-6">
                @if(Auth::user()->rol === 'cliente')
                ¡Aún no has realizado ningún pedido!
                @else
                No se encontraron pedidos con los filtros aplicados
                @endif
            </p>
            @if(Auth::user()->rol === 'cliente')
            <a href="{{ route('productos.index') }}"
               class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">
                <i class="fas fa-store mr-2"></i> Ir al Catálogo
            </a>
            @endif
        </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($pedidos->hasPages())
    <div class="bg-white rounded-xl shadow p-4">
        {{ $pedidos->links('components.pagination') }}
    </div>
    @endif
</div>

@push('scripts')
<script>
    function aplicarFiltros() {
        const estado = document.getElementById('filterEstado')?.value || '';
        const fecha = document.getElementById('filterFecha')?.value || '';
        const search = document.getElementById('searchInput')?.value || '';

        let url = '{{ request()->url() }}?';
        const params = [];

        if (estado) params.push(`estado=${estado}`);
        if (fecha) params.push(`fecha=${fecha}`);
        if (search) params.push(`search=${encodeURIComponent(search)}`);

        window.location.href = url + params.join('&');
    }

    function cambiarEstado(pedidoId, nuevoEstado) {
        if (!confirm('¿Estás seguro de cambiar el estado del pedido?')) return;

        fetch(`/pedidos/${pedidoId}/cambiar-estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ estado: nuevoEstado })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error al cambiar el estado');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cambiar el estado');
        });
    }

    // Aplicar filtro de fecha por defecto (hoy)
    @if(request('fecha'))
        document.getElementById('filterFecha').value = '{{ request("fecha") }}';
    @endif

    // Aplicar filtro de estado por defecto
    @if(request('estado'))
        document.getElementById('filterEstado').value = '{{ request("estado") }}';
    @endif

    // Aplicar búsqueda por defecto
    @if(request('search'))
        document.getElementById('searchInput').value = '{{ request("search") }}';
    @endif
</script>
@endpush
@endsection
