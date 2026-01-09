@extends('layouts.app')

@section('title', 'Registro - Licorería')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-500 to-purple-600 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-2xl p-8">
        <!-- Logo y Título -->
        <div class="text-center">
            <div class="flex justify-center">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-3 rounded-full shadow-lg">
                    <i class="fas fa-user-plus text-white text-4xl"></i>
                </div>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Crear Cuenta
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Regístrate para comenzar a comprar
            </p>
        </div>

        <!-- Formulario de Registro -->
        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-user mr-2"></i>Nombre Completo
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-id-card text-gray-400"></i>
                        </div>
                        <input
                            id="nombre"
                            name="nombre"
                            type="text"
                            required
                            value="{{ old('nombre') }}"
                            class="pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg input-focus focus:outline-none focus:z-10 sm:text-sm"
                            placeholder="Juan Pérez">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-envelope mr-2"></i>Correo Electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-at text-gray-400"></i>
                        </div>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            required
                            value="{{ old('email') }}"
                            class="pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg input-focus focus:outline-none focus:z-10 sm:text-sm"
                            placeholder="ejemplo@correo.com">
                    </div>
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-phone mr-2"></i>Teléfono
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-mobile-alt text-gray-400"></i>
                        </div>
                        <input
                            id="telefono"
                            name="telefono"
                            type="tel"
                            value="{{ old('telefono') }}"
                            class="pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg input-focus focus:outline-none focus:z-10 sm:text-sm"
                            placeholder="+51 999 999 999">
                    </div>
                </div>

                <!-- Rol -->
                <div>
                    <label for="rol" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-user-tag mr-2"></i>Tipo de Cuenta
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-briefcase text-gray-400"></i>
                        </div>
                        <select
                            id="rol"
                            name="rol"
                            required
                            class="pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg input-focus focus:outline-none focus:z-10 sm:text-sm">
                            <option value="">Selecciona un rol</option>
                            <option value="cliente" {{ old('rol') == 'cliente' ? 'selected' : '' }}>Cliente</option>
                            <option value="repartidor" {{ old('rol') == 'repartidor' ? 'selected' : '' }}>Repartidor</option>
                        </select>
                    </div>
                </div>

                <!-- Contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock mr-2"></i>Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            class="pl-10 pr-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg input-focus focus:outline-none focus:z-10 sm:text-sm"
                            placeholder="••••••••">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" class="toggle-password text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-eye" toggle="#password"></i>
                            </button>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Mínimo 8 caracteres</p>
                </div>

                <!-- Confirmar Contraseña -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-lock mr-2"></i>Confirmar Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            class="pl-10 pr-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg input-focus focus:outline-none focus:z-10 sm:text-sm"
                            placeholder="••••••••">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" class="toggle-password text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-eye" toggle="#password_confirmation"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Términos y condiciones -->
            <div class="flex items-center">
                <input
                    id="terms"
                    name="terms"
                    type="checkbox"
                    required
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-700">
                    Acepto los
                    <a href="#" class="text-blue-600 hover:text-blue-500">términos y condiciones</a>
                </label>
            </div>

            <!-- Botón de Registro -->
            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus"></i>
                    </span>
                    Crear Cuenta
                </button>
            </div>

            <!-- Enlace a Login -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 ml-1">
                        Inicia sesión aquí
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
