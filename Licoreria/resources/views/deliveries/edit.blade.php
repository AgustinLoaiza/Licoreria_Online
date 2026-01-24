@extends('layouts.admin')

@section('title', 'Editar Repartidor')
@section('page-title', 'Editar Repartidor: ' . $delivery->usuario->nombre)
@section('page-description', 'Modifica la información del repartidor')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6">
        <form action="{{ route('deliveries.update', $delivery->id) }}" method="POST">
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
                                   value="{{ old('nombre', $delivery->usuario->nombre) }}"
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
                                   value="{{ old('email', $delivery->usuario->email) }}"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                               value="{{ old('telefono', $delivery->usuario->telefono) }}"
                               class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('telefono')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Estado del repartidor -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Estado</h3>
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                            Estado *
                        </label>
                        <select id="estado"
                                name="estado"
                                class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required>
                            <option value="disponible" {{ old('estado', $delivery->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="ocupado" {{ old('estado', $delivery->estado) == 'ocupado' ? 'selected' : '' }}>Ocupado</option>
                            <option value="inactivo" {{ old('estado', $delivery->estado) == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        @error('estado')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Contraseña (opcional) -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Contraseña</h3>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Nueva Contraseña <span class="text-gray-400 text-xs">(dejar vacío para no cambiar)</span>
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="mt-2">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Confirmar Nueva Contraseña
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('deliveries.show', $delivery->id) }}"
                       class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="fas fa-save mr-2"></i> Actualizar Repartidor
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
