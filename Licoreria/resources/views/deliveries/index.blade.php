@extends('layouts.admin')

@section('title', 'Repartidores - Licorería')
@section('page-title', 'Gestión de Repartidores')
@section('page-description', 'Administra los repartidores del sistema')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Repartidores</h2>
            <p class="text-gray-600">Total: {{ $deliveries->total() }} repartidores</p>
        </div>
        <a href="{{ route('deliveries.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Nuevo Repartidor
        </a>
    </div>

    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Disponibles</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $disponibles ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center">
                <div class="bg-yellow-100 p-3 rounded-full mr-4">
                    <i class="fas fa-motorcycle text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Ocupados</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $ocupados ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6">
            <div class="flex items-center">
                <div class="bg-red-100 p-3 rounded-full mr-4">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Inactivos</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $inactivos ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de repartidores -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Repartidor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pedidos Activos
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Historial
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Calificación
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($deliveries as $delivery)
                    <tr class="hover:bg-gray-50">
                        <!-- Columna Repartidor -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                        <i class="fas fa-motorcycle text-green-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $delivery->usuario->nombre }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $delivery->usuario->telefono ?? 'Sin teléfono' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Columna Estado -->
                        <td class="px-6 py-4">
                            @php
                                $estadoColors = [
                                    'disponible' => 'bg-green-100 text-green-800',
                                    'ocupado' => 'bg-yellow-100 text-yellow-800',
                                    'inactivo' => 'bg-red-100 text-red-800'
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $estadoColors[$delivery->estado] }}">
                                <i class="fas fa-circle text-xs mr-1"></i>
                                {{ ucfirst($delivery->estado) }}
                            </span>
                        </td>

                        <!-- Columna Pedidos Activos -->
                        <td class="px-6 py-4">
                            @php
                                $pedidosActivos = $delivery->pedidos()
                                    ->whereIn('estado', ['confirmado', 'en_preparacion', 'en_camino'])
                                    ->count();
                            @endphp
                            <div class="text-center">
                                <span class="text-lg font-bold {{ $pedidosActivos > 0 ? 'text-blue-600' : 'text-gray-600' }}">
                                    {{ $pedidosActivos }}
                                </span>
                            </div>
                        </td>

                        <!-- Columna Historial -->
                        <td class="px-6 py-4">
                            @php
                                $pedidosEntregados = $delivery->pedidos()
                                    ->where('estado', 'entregado')
                                    ->count();
                            @endphp
                            <div class="text-sm">
                                <div class="flex items-center">
                                    <i class="fas fa-check-circle text-green-500 mr-2"></i>
                                    <span>{{ $pedidosEntregados }} entregados</span>
                                </div>
                            </div>
                        </td>

                        <!-- Columna Calificación -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= 4 ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">4.0</span>
                            </div>
                        </td>

                        <!-- Columna Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <!-- Ver -->
                                <a href="{{ route('deliveries.show', $delivery) }}"
                                   class="text-blue-600 hover:text-blue-900" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('deliveries.edit', $delivery) }}"
                                   class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Eliminar -->
                                <button onclick="eliminarDelivery({{ $delivery->id }}, '{{ $delivery->usuario->nombre }}')"
                                        class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron repartidores
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t">
            {{ $deliveries->links('components.pagination') }}
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
@include('components.confirm-modal')

@push('scripts')
<script>
    function eliminarDelivery(id, nombre) {
        showConfirmModal(
            '¿Eliminar repartidor?',
            `¿Estás seguro de eliminar al repartidor "${nombre}"? Esta acción no se puede deshacer.`,
            `/deliveries/${id}`
        );
    }
</script>
@endpush
@endsection
