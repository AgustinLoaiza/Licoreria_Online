@extends('layouts.admin')

@section('title', 'Editar Usuario')
@section('page-title', 'Editar Usuario: ' . $usuario->nombre)
@section('page-description', 'Modifica la información del usuario')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Información Básica -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Información Básica</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre *
                            </label>
                            <input type="text"
                                   id="nombre"
                                   name="nombre"
                                   value="{{ old('nombre', $usuario->nombre) }}"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('nombre')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Correo Electrónico *
                            </label>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $usuario->email) }}"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Contraseña -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Contraseña</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Nueva Contraseña
                            </label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirmar Nueva Contraseña
                            </label>
                            <input type="password"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Teléfono -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Contacto</h3>
                    <div>
                        <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">
                            Teléfono
                        </label>
                        <input type="text"
                               id="telefono"
                               name="telefono"
                               value="{{ old('telefono', $usuario->telefono) }}"
                               class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('telefono')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Rol -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Rol</h3>
                    <div>
                        <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">
                            Rol *
                        </label>
                        <select id="rol"
                                name="rol"
                                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="administrador" {{ old('rol', $usuario->rol) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                            <option value="cliente" {{ old('rol', $usuario->rol) == 'cliente' ? 'selected' : '' }}>Cliente</option>
                            <option value="repartidor" {{ old('rol', $usuario->rol) == 'repartidor' ? 'selected' : '' }}>Repartidor</option>
                        </select>
                        @error('rol')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('usuarios.show', $usuario->id) }}"
                       class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="fas fa-save mr-2"></i> Actualizar Usuario
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
