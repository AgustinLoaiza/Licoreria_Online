@extends('layouts.app')

@section('title', 'Iniciar Sesión - Licorería')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-primary py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white rounded-2xl shadow-2xl p-8">
        <!-- Logo y Título -->
        <div class="text-center">
            <div class="flex justify-center">
                <div class="bg-gradient-primary p-3 rounded-full shadow-lg">
                    <i class="fas fa-wine-bottle text-white text-4xl"></i>
                </div>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                Licorería Online
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Inicia sesión en tu cuenta
            </p>
        </div>

        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded alert-auto-dismiss">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Mostrar mensajes de éxito -->
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded alert-auto-dismiss">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulario de Login -->
        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <!-- Campo Email -->
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
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            class="pl-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg input-focus focus:outline-none focus:z-10 sm:text-sm"
                            placeholder="ejemplo@correo.com">
                    </div>
                </div>

                <!-- Campo Contraseña -->
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
                            autocomplete="current-password"
                            required
                            class="pl-10 pr-10 appearance-none relative block w-full px-3 py-3 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-lg input-focus focus:outline-none focus:z-10 sm:text-sm"
                            placeholder="••••••••">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button type="button" class="toggle-password text-gray-400 hover:text-gray-600 focus:outline-none">
                                <i class="fas fa-eye" toggle="#password"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recordarme y Olvidé contraseña -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            id="remember"
                            name="remember"
                            type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Recordar sesión
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-blue-600 hover:text-blue-500">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                </div>
            </div>

            <!-- Botón de Login -->
            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white btn-primary hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-sign-in-alt"></i>
                    </span>
                    Iniciar Sesión
                </button>
            </div>

            <!-- Enlaces adicionales -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    ¿No tienes una cuenta?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-500 ml-1">
                        Regístrate aquí
                    </a>
                </p>
            </div>

            <!-- Separador -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white text-gray-500">O continúa como</span>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script para credenciales de demo -->
<script>
    function setDemoCredentials(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;

        // Mostrar notificación
        showNotification('Credenciales de prueba cargadas', 'info');
    }

    function showNotification(message, type = 'info') {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 ${
            type === 'info' ? 'bg-blue-100 text-blue-800 border-blue-300' :
            type === 'success' ? 'bg-green-100 text-green-800 border-green-300' :
            'bg-red-100 text-red-800 border-red-300'
        } border-l-4`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-${type === 'info' ? 'info-circle' : type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                <span>${message}</span>
            </div>
        `;

        // Agregar al documento
        document.body.appendChild(notification);

        // Remover después de 3 segundos
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Mostrar mensaje de bienvenida si viene del registro
    @if(session('registered'))
        showNotification('¡Cuenta creada exitosamente! Ya puedes iniciar sesión.', 'success');
    @endif
</script>

<!-- Estilos adicionales -->
<style>
    .shake {
        animation: shake 0.5s;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }

    .btn-primary:active {
        transform: scale(0.98);
    }

    .input-error {
        border-color: #f56565;
        animation: shake 0.5s;
    }
</style>

<!-- Script para validación en tiempo real -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');

        // Validar email en tiempo real
        emailInput.addEventListener('blur', function() {
            const email = this.value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (email && !emailRegex.test(email)) {
                this.classList.add('input-error');
                showTooltip(this, 'Por favor ingresa un email válido');
            } else {
                this.classList.remove('input-error');
                hideTooltip(this);
            }
        });

        // Validar contraseña en tiempo real
        passwordInput.addEventListener('blur', function() {
            if (this.value && this.value.length < 6) {
                this.classList.add('input-error');
                showTooltip(this, 'La contraseña debe tener al menos 6 caracteres');
            } else {
                this.classList.remove('input-error');
                hideTooltip(this);
            }
        });

        // Validar formulario antes de enviar
        form.addEventListener('submit', function(e) {
            let isValid = true;

            // Validar email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                emailInput.classList.add('input-error');
                showTooltip(emailInput, 'Por favor ingresa un email válido');
                isValid = false;
            }

            // Validar contraseña
            if (passwordInput.value.length < 6) {
                passwordInput.classList.add('input-error');
                showTooltip(passwordInput, 'La contraseña debe tener al menos 6 caracteres');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
                showNotification('Por favor corrige los errores en el formulario', 'error');
            }
        });

        function showTooltip(element, message) {
            // Remover tooltip anterior si existe
            hideTooltip(element);

            // Crear tooltip
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-10 px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm -top-10';
            tooltip.textContent = message;
            tooltip.id = 'tooltip-' + element.id;

            // Posicionar tooltip
            element.parentElement.style.position = 'relative';
            element.parentElement.appendChild(tooltip);
        }

        function hideTooltip(element) {
            const tooltip = document.getElementById('tooltip-' + element.id);
            if (tooltip) {
                tooltip.remove();
            }
        }
    });
</script>
@endsection
