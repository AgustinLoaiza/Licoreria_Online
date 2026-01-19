@extends('layouts.admin')

@section('title', 'Usuarios - Licorería')
@section('page-title', 'Gestión de Usuarios')
@section('page-description', 'Administra los usuarios del sistema')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Usuarios</h2>
            <p class="text-gray-600">Total: {{ $usuarios->total() }} usuarios</p>
        </div>
        <a href="{{ route('usuarios.create') }}"
           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 flex items-center">
            <i class="fas fa-user-plus mr-2"></i> Nuevo Usuario
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-xl shadow p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Búsqueda -->
            <div>
                <input type="text"
                       id="searchInput"
                       placeholder="Buscar por nombre o email..."
                       class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="{{ request('search') }}">
            </div>

            <!-- Filtro por rol -->
            <div>
                <select id="filterRol" class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los roles</option>
                    <option value="administrador" {{ request('rol') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="cliente" {{ request('rol') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                    <option value="repartidor" {{ request('rol') == 'repartidor' ? 'selected' : '' }}>Repartidor</option>
                </select>
            </div>

            <!-- Botón de filtro -->
            <div>
                <button onclick="aplicarFiltros()"
                        class="w-full bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-900">
                    <i class="fas fa-filter mr-2"></i> Filtrar
                </button>
            </div>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuario
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Contacto
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Rol
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Estado
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Registro
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($usuarios as $usuario)
                    <tr class="hover:bg-gray-50">
                        <!-- Columna Usuario -->
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600"></i>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $usuario->nombre }}
                                        @if($usuario->id == Auth::id())
                                        <span class="ml-2 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded">Tú</span>
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $usuario->email }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Columna Contacto -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $usuario->telefono ?? 'Sin teléfono' }}
                            </div>
                        </td>

                        <!-- Columna Rol -->
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $usuario->rol == 'administrador' ? 'bg-purple-100 text-purple-800' :
                                   ($usuario->rol == 'repartidor' ? 'bg-green-100 text-green-800' :
                                   'bg-blue-100 text-blue-800') }}">
                                <i class="fas fa-{{ $usuario->rol == 'administrador' ? 'crown' :
                                                   ($usuario->rol == 'repartidor' ? 'motorcycle' : 'user') }} mr-1"></i>
                                {{ ucfirst($usuario->rol) }}
                            </span>
                        </td>

                        <!-- Columna Estado -->
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $usuario->estado == 'activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($usuario->estado ?? 'activo') }}
                            </span>
                        </td>

                        <!-- Columna Registro -->
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">
                                {{ $usuario->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $usuario->created_at->format('H:i') }}
                            </div>
                        </td>

                        <!-- Columna Acciones -->
                        <td class="px-6 py-4">
                            <div class="flex space-x-2">
                                <!-- Ver -->
                                <a href="{{ route('usuarios.show', $usuario) }}"
                                   class="text-blue-600 hover:text-blue-900" title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('usuarios.edit', $usuario) }}"
                                   class="text-yellow-600 hover:text-yellow-900" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <!-- Eliminar (no permitir auto-eliminación) -->
                                @if($usuario->id != Auth::id())
                                <button onclick="eliminarUsuario({{ $usuario->id }}, '{{ $usuario->nombre }}')"
                                        class="text-red-600 hover:text-red-900" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            No se encontraron usuarios
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 border-t">
            {{ $usuarios->links('components.pagination') }}
        </div>
    </div>
</div>

<!-- Modal de confirmación -->
@include('components.confirm-modal')

@push('scripts')
<script>
    function eliminarUsuario(id, nombre) {
        showConfirmModal(
            '¿Eliminar usuario?',
            `¿Estás seguro de eliminar al usuario "${nombre}"? Esta acción no se puede deshacer.`,
            `/usuarios/${id}`
        );
    }

    function aplicarFiltros() {
        const search = document.getElementById('searchInput').value;
        const rol = document.getElementById('filterRol').value;

        let url = '{{ route("usuarios.index") }}?';
        const params = [];

        if (search) params.push(`search=${encodeURIComponent(search)}`);
        if (rol) params.push(`rol=${rol}`);

        window.location.href = url + params.join('&');
    }

    // Aplicar filtro con Enter
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') aplicarFiltros();
    });
</script>
@endpush
@endsection
