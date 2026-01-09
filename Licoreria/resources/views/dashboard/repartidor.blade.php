@extends('layouts.repartidor')

@section('title', 'Dashboard Repartidor')

@section('content')
<div class="space-y-6">
    <!-- Estado y Acciones -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <div class="mb-4 md:mb-0">
                <h2 class="text-xl font-bold text-gray-800 mb-2">Estado Actual</h2>
                @php
                    $estado = $delivery->estado;
                    $estadoConfig = [
                        'disponible' => [
                            'color' => 'text-green-600 bg-green-100',
                            'icon' => 'fa-check-circle',
                            'message' => 'Estás disponible para recibir pedidos'
                        ],
                        'ocupado' => [
                            'color' => 'text-yellow-600 bg-yellow-100',
                            'icon' => 'fa-motorcycle',
                            'message' => 'Tienes pedidos asignados'
                        ],
                        'inactivo' => [
                            'color' => 'text-red-600 bg-red-100',
                            'icon' => 'fa-times-circle',
                            'message' => 'No estás disponible'
                        ]
                    ];
                @endphp

                <div class="flex items-center space-x-3">
                    <span class="px-4 py-2 rounded-full text-lg font-semibold {{ $estadoConfig[$estado]['color'] }}">
                        <i class="fas {{ $estadoConfig[$estado]['icon'] }} mr-2"></i>
                        {{ ucfirst($estado) }}
                    </span>
                    <p class="text-gray-600">{{ $estadoConfig[$estado]['message'] }}</p>
                </div>
            </div>

            <div class="flex space-x-3">
                <form action="{{ route('deliverys.update', $delivery) }}" method="POST">
                    @csrf @method('PUT')
                    @if($delivery->estado == 'disponible')
                    <input type="hidden" name="estado" value="inactivo">
                    <button type="submit"
                            class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300">
                        <i class="fas fa-power-off mr-2"></i>Desactivar
                    </button>
                    @else
                    <input type="hidden" name="estado" value="disponible">
                    <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        <i class="fas fa-play mr-2"></i>Activar
                    </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Pedidos Asignados -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pedidos Activos -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Pedidos Asignados</h3>
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $pedidos_asignados->count() }} activos
                </span>
            </div>

            @if($pedidos_asignados->count() > 0)
            <div class="space-y-4">
                @foreach($pedidos_asignados as $pedido)
                <div class="border rounded-lg p-4 hover:shadow-md transition status-{{ $pedido->estado }}">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="font-semibold text-gray-800">Pedido #{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</h4>
                            <p class="text-sm text-gray-600">{{ $pedido->nombre_pedido }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-medium
                            {{ $pedido->estado == 'en_camino' ? 'bg-indigo-100 text-indigo-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($pedido->estado) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <p class="text-sm text-gray-700 mb-1">
                            <i class="fas fa-user mr-2"></i>
                            {{ $pedido->usuario->nombre }}
                        </p>
                        <p class="text-sm text-gray-700 mb-1">
                            <i class="fas fa-phone mr-2"></i>
                            {{ $pedido->usuario->telefono ?? 'Sin teléfono' }}
                        </p>
                        <p class="text-sm text-gray-700">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $pedido->direccion }}
                        </p>
                    </div>

                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Total:</p>
                            <p class="font-bold text-lg">S/ {{ number_format($pedido->monto_total, 2) }}</p>
                        </div>

                        <div class="flex space-x-2">
                            <button onclick="mostrarUbicacion('{{ $pedido->direccion }}')"
                                    class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-map"></i>
                            </button>

                            @if($pedido->estado == 'confirmado')
                            <button onclick="cambiarEstadoPedido({{ $pedido->id }}, 'en_camino')"
                                    class="bg-blue-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-blue-700">
                                <i class="fas fa-play mr-1"></i>Iniciar Entrega
                            </button>
                            @endif

                            @if($pedido->estado == 'en_camino')
                            <button onclick="cambiarEstadoPedido({{ $pedido->id }}, 'entregado')"
                                    class="bg-green-600 text-white px-3 py-1 rounded-lg text-sm hover:bg-green-700">
                                <i class="fas fa-check mr-1"></i>Marcar Entregado
                            </button>
                            @endif

                            <a href="{{ route('pedidos.show', $pedido) }}"
                               class="text-gray-600 hover:text-gray-800">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-box-open text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-600">No tienes pedidos asignados</p>
                <p class="text-sm text-gray-500 mt-1">Cuando estés disponible, te asignaremos pedidos</p>
            </div>
            @endif
        </div>

        <!-- Historial Reciente -->
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800">Historial Reciente</h3>
                <a href="#" class="text-blue-600 text-sm hover:underline">
                    Ver todo
                </a>
            </div>

            @if($historial->count() > 0)
            <div class="space-y-4">
                @foreach($historial as $pedido)
                <div class="flex items-center justify-between p-3 border rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">#{{ str_pad($pedido->id, 6, '0', STR_PAD_LEFT) }}</p>
                        <p class="text-sm text-gray-600">{{ $pedido->fecha_pedido->format('d/m H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold">S/ {{ number_format($pedido->monto_total, 2) }}</p>
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Entregado
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <i class="fas fa-history text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-600">No hay historial de entregas</p>
            </div>
            @endif

            <!-- Estadísticas rápidas -->
            <div class="mt-8 pt-6 border-t">
                <h4 class="font-semibold text-gray-800 mb-3">Resumen del Día</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600">Pedidos Entregados</p>
                        <p class="text-2xl font-bold text-gray-800">3</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-sm text-gray-600">Ganancias Estimadas</p>
                        <p class="text-2xl font-bold text-gray-800">S/ 45.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa Simulado -->
    <div class="bg-white rounded-xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Zona de Entrega</h3>
        <div class="bg-gray-100 rounded-lg h-64 flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-map-marked-alt text-gray-400 text-4xl mb-3"></i>
                <p class="text-gray-600">Mapa de entregas</p>
                <p class="text-sm text-gray-500">(Integración con Google Maps)</p>
                <button onclick="mostrarZonaEntrega()"
                        class="mt-3 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-map-marker-alt mr-2"></i>Ver en Mapa
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function mostrarZonaEntrega() {
        // Aquí se integraría con Google Maps
        alert('Mostrando zona de entrega en el mapa');
    }
</script>
@endpush
@endsection
