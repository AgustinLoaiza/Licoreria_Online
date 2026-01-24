@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Administrativo')
@section('page-description', 'Resumen completo del negocio')

@section('content')
<div class="space-y-6">
    <!-- Estadísticas Principales -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Ventas -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Ventas</p>
                    <p class="text-3xl font-bold text-gray-800">S/ {{ number_format($stats['ingresos_mes'], 2) }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-blue-600 text-xl"></i>
                </div>
            </div>
            <p class="text-green-600 text-sm mt-2">
                <i class="fas fa-arrow-up mr-1"></i>12% vs mes anterior
            </p>
        </div>

        <!-- Pedidos Pendientes -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pedidos Pendientes</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['pedidos_pendientes'] }}</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('pedidos.index') }}" class="text-blue-600 text-sm mt-2 hover:underline">
                Ver todos →
            </a>
        </div>

        <!-- Pagos por Verificar -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Pagos por Verificar</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pagos_pendientes ?? 0 }}</p>
                </div>
                <div class="bg-red-100 p-3 rounded-full">
                    <i class="fas fa-qrcode text-red-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('ventas.index') }}?estado=pendiente" class="text-blue-600 text-sm mt-2 hover:underline">
                Verificar ahora →
            </a>
        </div>

        <!-- Stock Bajo -->
        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Productos Stock Bajo</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $stats['productos_bajo_stock'] }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-exclamation-triangle text-purple-600 text-xl"></i>
                </div>
            </div>
            <a href="{{ route('productos.admin.index') }}?stock=bajo" class="text-blue-600 text-sm mt-2 hover:underline">
                Reabastecer →
            </a>
        </div>
    </div>

    <!-- Gráficos y Tablas -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Gráfico de Ventas -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Ventas Mensuales</h3>
                <select class="border rounded-lg px-3 py-1 text-sm">
                    <option>Últimos 7 días</option>
                    <option>Últimos 30 días</option>
                    <option>Últimos 90 días</option>
                </select>
            </div>
            <canvas id="salesChart" height="250"></canvas>
        </div>

        <!-- Pedidos Recientes -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Pedidos Recientes</h3>
                <a href="{{ route('pedidos.index') }}" class="text-blue-600 text-sm hover:underline">
                    Ver todos
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Cliente</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Total</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Estado</th>
                            <th class="text-left py-3 text-sm font-medium text-gray-600">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pedidos_recientes as $pedido)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                                        <i class="fas fa-user text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $pedido->usuario->nombre }}</p>
                                        <p class="text-xs text-gray-500">{{ $pedido->fecha_pedido ? $pedido->fecha_pedido->format('d/m H:i') : '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="font-semibold">S/ {{ number_format($pedido->monto_total, 2) }}</span>
                            </td>
                            <td class="py-3">
                                @php
                                    $statusColors = [
                                        'pendiente' => 'bg-yellow-100 text-yellow-800',
                                        'confirmado' => 'bg-blue-100 text-blue-800',
                                        'en_preparacion' => 'bg-purple-100 text-purple-800',
                                        'en_camino' => 'bg-indigo-100 text-indigo-800',
                                        'entregado' => 'bg-green-100 text-green-800',
                                        'cancelado' => 'bg-red-100 text-red-800'
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$pedido->estado] }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <a href="{{ route('pedidos.show', $pedido) }}"
                                   class="text-blue-600 hover:text-blue-800 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-4 text-center text-gray-500">
                                No hay pedidos recientes
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Verificación de Pagos QR -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Pagos QR por Verificar</h3>
            <a href="{{ route('ventas.index') }}?estado=pendiente" class="text-blue-600 text-sm hover:underline">
                Ver todos
            </a>
        </div>

        @php
            $pagosPendientes = App\Models\Venta::where('estado', 'pendiente')
                ->where('metodo_pago', 'qr')
                ->with('pedido.usuario')
                ->limit(5)
                ->get();
        @endphp

        @if($pagosPendientes->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Pedido #</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Cliente</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Monto</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Comprobante</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pagosPendientes as $pago)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="py-3">
                            <span class="font-mono">#{{ str_pad($pago->id_pedido, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="py-3">
                            {{ $pago->pedido->usuario->nombre }}
                        </td>
                        <td class="py-3">
                            <span class="font-semibold">S/ {{ number_format($pago->pedido->monto_total, 2) }}</span>
                        </td>
                        <td class="py-3">
                            @if($pago->comprobante)
                                <button onclick="verComprobante('{{ $pago->comprobante }}')"
                                        class="text-blue-600 hover:text-blue-800 flex items-center">
                                    <i class="fas fa-file-invoice-dollar mr-2"></i>
                                    Ver QR
                                </button>
                            @else
                                <span class="text-gray-500">No subido</span>
                            @endif
                        </td>
                        <td class="py-3">
                            <div class="flex space-x-2">
                                <form action="{{ route('ventas.verificar', $pago) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                            class="bg-green-100 text-green-700 px-3 py-1 rounded-lg text-sm hover:bg-green-200">
                                        <i class="fas fa-check mr-1"></i>Verificar
                                    </button>
                                </form>
                                <button onclick="rechazarPago({{ $pago->id }})"
                                        class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm hover:bg-red-200">
                                    <i class="fas fa-times mr-1"></i>Rechazar
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-8">
            <i class="fas fa-check-circle text-green-400 text-4xl mb-3"></i>
            <p class="text-gray-600">No hay pagos pendientes por verificar</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal para ver comprobante -->
<div id="modalComprobante" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold">Comprobante de Pago</h3>
            <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modalContent" class="text-center">
            <!-- La imagen del comprobante se cargará aquí -->
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Gráfico de ventas
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Ventas (S/)',
                data: [12000, 19000, 15000, 25000, 22000, 30000, 28000],
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return 'S/ ' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });

    function verComprobante(url) {
        $('#modalContent').html(`
            <img src="/storage/${url}" alt="Comprobante" class="rounded-lg mx-auto max-h-96">
            <div class="mt-4">
                <a href="/storage/${url}" target="_blank"
                   class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-expand mr-2"></i>Ver en tamaño completo
                </a>
            </div>
        `);
        $('#modalComprobante').removeClass('hidden');
    }

    function cerrarModal() {
        $('#modalComprobante').addClass('hidden');
    }

    function rechazarPago(ventaId) {
        if (confirm('¿Estás seguro de rechazar este pago?')) {
            // Aquí iría la lógica para rechazar el pago
            alert('Pago rechazado');
        }
    }

    // Cerrar modal con ESC
    $(document).keyup(function(e) {
        if (e.key === "Escape") {
            cerrarModal();
        }
    });
</script>
@endpush
@endsection
