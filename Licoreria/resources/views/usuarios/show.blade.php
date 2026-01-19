@extends(Auth::user()->rol === 'administrador' ? 'layouts.admin' : (Auth::user()->rol === 'repartidor' ? 'layouts.admin' : 'layouts.cliente'))

@section('title', $usuario->nombre . ' - Licorería')

@if(Auth::user()->rol === 'administrador' || Auth::user()->rol === 'repartidor')
    @section('page-title', 'Detalle del Usuario')
    @section('page-description', 'Información completa del usuario')
@else
    @section('page-title', $usuario->nombre)
    @section('page-description', 'Detalle del usuario')
@endif

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 border-b flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $usuario->nombre }}</h2>
                <p class="text-gray-600">ID: USR-{{ str_pad($usuario->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>

            @if(Auth::user()->rol === 'administrador' || Auth::user()->rol === 'repartidor')
            <div class="flex space-x-2">
                <a href="{{ route('usuarios.edit', $usuario->id) }}"
                   class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 flex items-center">
                    <i class="fas fa-edit mr-2"></i> Editar
                </a>
                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?');">
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
                        <i class="fas fa-user text-gray-400 text-8xl"></i>
                    </div>
                </div>

                <!-- Información -->
                <div class="space-y-6">
                    <!-- Rol y Estado -->
                    <div class="flex space-x-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $usuario->rol == 'administrador' ? 'bg-blue-100 text-blue-800' :
                               ($usuario->rol == 'repartidor' ? 'bg-green-100 text-green-800' :
                               'bg-yellow-100 text-yellow-800') }}">
                            {{ ucfirst($usuario->rol) }}
                        </span>
                        @if(isset($usuario->activo))
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                            {{ $usuario->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $usuario->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                        @endif
                    </div>

                    <!-- Email -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Correo Electrónico</h3>
                        <p class="text-gray-800">{{ $usuario->email }}</p>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Teléfono</h3>
                        <p class="text-gray-800">{{ $usuario->telefono ?? 'No registrado' }}</p>
                    </div>

                    <!-- Información Adicional -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-2">Información Adicional</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Creado</p>
                                <p class="font-medium">{{ $usuario->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-500">Última actualización</p>
                                <p class="font-medium">{{ $usuario->updated_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Para clientes: botón para editar perfil -->
                    @if(Auth::user()->rol === 'cliente' && Auth::user()->id === $usuario->id)
                    <div>
                        <a href="{{ route('usuarios.edit', $usuario->id) }}"
                           class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                            <i class="fas fa-user-edit mr-2"></i> Editar mi perfil
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Botón Volver -->
    <div class="mt-6">
        <a href="{{ route('usuarios.index') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Volver a la lista
        </a>
    </div>
</div>
@endsection
