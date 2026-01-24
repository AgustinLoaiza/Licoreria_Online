@extends(Auth::user()->rol === 'administrador' ? 'layouts.admin' : (Auth::user()->rol === 'repartidor' ? 'layouts.admin' : 'layouts.cliente'))

@section('title', 'Repartidor: ' . $delivery->usuario->nombre . ' - Licorería')

@if(Auth::user()->rol === 'administrador' || Auth::user()->rol === 'repartidor')
    @section('page-title', 'Detalle del Repartidor')
    @section('page-description', 'Información completa del repartidor')
@else
    @section('page-title', $delivery->usuario->nombre)
    @section('page-description', 'Detalle del repartidor')
@endif

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $delivery->usuario->nombre }}</h2>
                <p class="text-gray-600">ID: REP-{{ str_pad($delivery->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            @if(Auth::user()->rol === 'administrador')
            <div class="flex space-x-2">
                <a href="{{ route('deliveries.edit', $delivery->id) }}"
                   class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('deliveries.destroy', $delivery->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este repartidor?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 flex items-center">
                        <i class="fas fa-trash mr-2"></i> Eliminar
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Contenido -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Avatar o icono -->
                <div class="flex items-center justify-center">
                    <div class="bg-gray-100 rounded-full h-48 w-48 flex items-center justify-center">
                        <i class="fas fa-motorcycle text-gray-400 text-8xl"></i>
                    </div>
                </div>

                <!-- Información -->
                <div class="space-y-6">
                    <!-- Estado -->
                    <div class="flex space-x-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $delivery->estado == 'disponible' ? 'bg-green-100 text-green-800' :
                               ($delivery->estado == 'ocupado' ? 'bg-yellow-100 text-yellow-800' :
                               'bg-red-100 text-red-800') }}">
                            {{ ucfirst($delivery->estado) }}
                        </span>
                    </div>

                    <!-- Email -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Correo Electrónico</h3>
                        <p class="text-gray-800">{{ $delivery->usuario->email }}</p>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Teléfono</h3>
                        <p class="text-gray-800">{{ $delivery->usuario->telefono ?? 'No registrado' }}</p>
                    </div>

                    <!-- Información Adicional -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Información Adicional</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Creado</p>
                                <p class="font-medium">{{ $delivery->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Última actualización</p>
                                <p class="font-medium">{{ $delivery->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Volver -->
    <div class="mt-6">
        <a href="{{ route('deliveries.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la lista
        </a>
    </div>
</div>
@endsection
