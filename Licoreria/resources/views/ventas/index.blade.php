@extends('layouts.admin')

@section('title', 'Ventas - Licorería')
@section('page-title', 'Gestión de Ventas')
@section('page-description', 'Administra las ventas y pagos del sistema')

@section('content')
<div class="space-y-6">
    <!-- Estadísticas de ventas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Ventas -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Ventas</p>
                    <p class="text-3xl font-bold text-gray-800">S/ {{ number_format($total_ventas, 2) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-green-600 text-sm mt-2">
                <i class="fas fa-arrow-up mr-1"></i>15% vs mes anterior
            </p>
        </div>

        <!-- Ventas Hoy -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Ventas Hoy</p>
                    <p class="text-3xl font-bold text-gray-800">S/ {{ number_format($ventas_hoy, 2) }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">{{ $ventas_count_hoy }} transacciones</p>
        </div>

        <!-- Pagos Pendientes -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pagos Pendientes</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pagos_pendientes }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
            <a href="?estado=pendiente" class="text-blue-600 text-sm mt-2 hover:underline block">
                Verificar ahora →
            </a>
        </div>

        <!-- Ventas Verificadas -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Ventas Verificadas</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $ventas_verificadas }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mt-2">Total: S/ {{ number_format($total_verificadas, 2) }}</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Filtro por estado -->
            <div>
                <select id="filterEstado" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendientes</option>
                    <option value="pagado" {{ request('estado') == 'pagado' ? 'selected' : '' }}>Pagados</option>
                </select>
            </div>

            <!-- Filtro por método de pago -->
            <div>
                <select id="filterMetodo" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los métodos</option>
                    <option value="qr" {{ request('metodo') == 'qr' ? 'selected' : '' }}>QR</option>
                    <option value="efectivo" {{ request('metodo') == 'efectivo' ? 'selected' : '' }}>Efectivo</option>
                    <option value="tarjeta" {{ request('metodo') == 'tarjeta' ? 'selected' : '' }}>Tarjeta</option>
                </select>
            </div>

            <!-- Filtro por fecha -->
            <div>
                <input type="date"
                       id="filterFecha"
                       value="{{ request('fecha') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Búsqueda -->
            <div>
                <input type="text"
                       id="searchInput"
                       placeholder="Buscar por cliente..."
                       value="{{ request('search') }}"
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Botones de acción -->
            <div class="flex space-x-2">
                <button onclick="aplicarFiltros()"
                        class="flex-1 bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
                <button onclick="exportarVentas()"
                        class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-export mr-2"></i> Exportar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de ventas -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Venta
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cliente
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pedido
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Método
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Monto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fecha
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($ventas as $venta)
                    <tr class="hover:bg-gray-50">
                        <!-- Columna Venta -->
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">
                                Venta #{{ str_pad($venta->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="text-sm text-gray-500">
                                ID: {{ $venta->id_pedido }}
                            </div>
                        </td>

                        <!-- Columna Cliente -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8">
                                    <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $venta->pedido->usuario->nombre }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        {{ $venta->pedido->usuario->telefono ?? 'Sin teléfono' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Columna Pedido -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                #{{ str_pad($venta->pedido->id, 6, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $venta->pedido->estado }}
                            </div>
                        </td>

                        <!-- Columna Método -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center mr-2
                                    {{ $venta->metodo_pago == 'qr' ? 'bg-green-100 text-green-600' :
                                       ($venta->metodo_pago == 'efectivo' ? 'bg-yellow-100 text-yellow-600' :
                                       'bg-blue-100 text-blue-600') }}">
                                    <i class="fas fa-{{ $venta->metodo_pago == 'qr' ? 'qrcode' :
                                                       ($venta->metodo_pago == 'efectivo' ? 'money-bill-wave' :
                                                       'credit-card') }} text-sm"></i>
                                </div>
                                <span class="text-sm font-medium">
                                    {{ ucfirst($venta->metodo_pago) }}
                                </span>
                            </div>
                        </td>

                        <!-- Columna Monto -->
                        <td class="px-6 py-4">
                            <div class="text-lg font-bold text-gray-900">
                                S/ {{ number_format($venta->pedido->monto_total, 2) }}
                            </div>
                        </td>

                        <!-- Columna Estado -->
                        <td class="px-6 py-4">
                            @php
                                $estadoColors = [
                                    'pendiente' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
                                    'pagado' => 'bg-green-100 text-green-800 border-green-300',
                                    'verificado' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'rechazado' => 'bg-red-100 text-red-800 border-red-300'
                                ];
                                $estadoIcons = [
                                    'pendiente' => 'fa-clock',
                                    'pagado' => 'fa-check-circle',
                                    'verificado' => 'fa-shield-check',
                                    'rechazado' => 'fa-times-circle'
                                ];
                            @endphp
                            <div class="inline-flex items-center px-3 py-1 rounded-full border text-sm {{ $estadoColors[$venta->estado] }}">
                                <i class="fas {{ $estadoIcons[$venta->estado] }} mr-2"></i>
                                {{ ucfirst($venta->estado) }}
                            </div>
                        </td>

                        <!-- Columna Fecha -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $venta->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $venta->created_at->format('H:i') }}
                            </div>
                        </td>

                        <!-- Columna Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <!-- Ver detalle -->
                                <a href="{{ route('ventas.show', $venta) }}"
                                   class="text-blue-600 hover:text-blue-900" title="Ver detalle">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Ver comprobante -->
                                @if($venta->metodo_pago == 'qr' && $venta->comprobante)
                                <button onclick="verComprobante('{{ $venta->comprobante }}')"
                                        class="text-green-600 hover:text-green-900" title="Ver comprobante">
                                    <i class="fas fa-qrcode"></i>
                                </button>
                                @endif

                                <!-- Verificar pago -->
                                @if($venta->estado == 'pendiente')
                                <button onclick="verificarPago({{ $venta->id }})"
                                        class="text-green-600 hover:text-green-900" title="Verificar pago">
                                    <i class="fas fa-check"></i>
                                </button>
                                @endif

                                <!-- Rechazar pago -->
                                @if($venta->estado == 'pendiente')
                                <button onclick="rechazarPago({{ $venta->id }})"
                                        class="text-red-600 hover:text-red-900" title="Rechazar pago">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif

                                <!-- Eliminar -->
                                <button onclick="eliminarVenta({{ $venta->id }})"
                                        class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron ventas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t">
            {{ $ventas->links('components.pagination') }}
        </div>
    </div>

    <!-- Resumen por método de pago -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de ventas -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Ventas por Método de Pago</h3>
                <select id="graficoPeriodo" class="border rounded-lg px-3 py-1 text-sm">
                    <option value="7">Últimos 7 días</option>
                    <option value="30" selected>Últimos 30 días</option>
                    <option value="90">Últimos 90 días</option>
                </select>
            </div>
            <canvas id="ventasChart" height="250"></canvas>
        </div>

        <!-- Métodos de pago más usados -->
        <div class="bg-white rounded-xl shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6">Resumen de Métodos de Pago</h3>
            <div class="space-y-4">
                @php
                    $metodos = [
                        'qr' => ['color' => 'bg-green-500', 'icon' => 'fa-qrcode', 'total' => 0],
                        'efectivo' => ['color' => 'bg-yellow-500', 'icon' => 'fa-money-bill-wave', 'total' => 0],
                        'tarjeta' => ['color' => 'bg-blue-500', 'icon' => 'fa-credit-card', 'total' => 0]
                    ];

                    foreach($ventas as $venta) {
                        if(isset($metodos[$venta->metodo_pago])) {
                            $metodos[$venta->metodo_pago]['total'] += $venta->pedido->monto_total;
                        }
                    }

                    $total_metodos = array_sum(array_column($metodos, 'total'));
                @endphp

                @foreach($metodos as $metodo => $data)
                @if($data['total'] > 0)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full {{ str_replace('bg-', 'bg-', $data['color']) }} flex items-center justify-center mr-3">
                            <i class="fas {{ $data['icon'] }} text-white"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ ucfirst($metodo) }}</p>
                            <p class="text-sm text-gray-600">
                                {{ $total_metodos > 0 ? number_format(($data['total'] / $total_metodos) * 100, 1) : 0 }}%
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">S/ {{ number_format($data['total'], 2) }}</p>
                        <p class="text-sm text-gray-600">
                            {{ $ventas->where('metodo_pago', $metodo)->count() }} ventas
                        </p>
                    </div>
                </div>

                <!-- Barra de progreso -->
                <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                    <div class="h-2 rounded-full {{ $data['color'] }}"
                         style="width: {{ $total_metodos > 0 ? ($data['total'] / $total_metodos * 100) : 0 }}%"></div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal para ver comprobante -->
<div id="modalComprobante" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-2xl w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Comprobante de Pago QR</h3>
            <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modalContent" class="text-center">
            <!-- Contenido dinámico -->
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
@include('components.confirm-modal')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Gráfico de ventas
    const ctx = document.getElementById('ventasChart').getContext('2d');
    const ventasChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['QR', 'Efectivo', 'Tarjeta'],
            datasets: [{
                data: [
                    {{ $ventasPorMetodo['qr'] ?? 0 }},
                    {{ $ventasPorMetodo['efectivo'] ?? 0 }},
                    {{ $ventasPorMetodo['tarjeta'] ?? 0 }}
                ],
                backgroundColor: [
                    '#10b981', // Verde para QR
                    '#f59e0b', // Amarillo para efectivo
                    '#3b82f6'  // Azul para tarjeta
                ],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

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

    function verificarPago(ventaId) {
        if (confirm('¿Verificar este pago como correcto?')) {
            fetch(`/ventas/${ventaId}/verificar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al verificar el pago');
            });
        }
    }

    function rechazarPago(ventaId) {
        const motivo = prompt('Ingrese el motivo del rechazo:', 'Comprobante no válido');
        if (motivo) {
            fetch(`/ventas/${ventaId}/rechazar`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ motivo: motivo })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al rechazar el pago');
            });
        }
    }

    function eliminarVenta(ventaId) {
        showConfirmModal(
            '¿Eliminar venta?',
            '¿Estás seguro de eliminar este registro de venta? Esta acción no se puede deshacer.',
            `/ventas/${ventaId}`
        );
    }

    function aplicarFiltros() {
        const estado = document.getElementById('filterEstado').value;
        const metodo = document.getElementById('filterMetodo').value;
        const fecha = document.getElementById('filterFecha').value;
        const search = document.getElementById('searchInput').value;

        let url = '{{ route("ventas.index") }}?';
        const params = [];

        if (estado) params.push(`estado=${estado}`);
        if (metodo) params.push(`metodo=${metodo}`);
        if (fecha) params.push(`fecha=${fecha}`);
        if (search) params.push(`search=${encodeURIComponent(search)}`);

        window.location.href = url + params.join('&');
    }

    function exportarVentas() {
        const estado = document.getElementById('filterEstado').value;
        const metodo = document.getElementById('filterMetodo').value;
        const fecha = document.getElementById('filterFecha').value;

        let url = '/ventas/export?';
        const params = [];

        if (estado) params.push(`estado=${estado}`);
        if (metodo) params.push(`metodo=${metodo}`);
        if (fecha) params.push(`fecha=${fecha}`);

        window.location.href = url + params.join('&');
    }

    // Cerrar modal con ESC
    $(document).keyup(function(e) {
        if (e.key === "Escape") cerrarModal();
    });
</script>
@endpush
@endsection
