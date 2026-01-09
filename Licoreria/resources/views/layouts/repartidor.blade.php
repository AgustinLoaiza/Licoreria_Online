<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Repartidor - Licorería')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .status-pendiente { border-left: 4px solid #f59e0b; }
        .status-en-camino { border-left: 4px solid #3b82f6; }
        .status-entregado { border-left: 4px solid #10b981; }
        .status-cancelado { border-left: 4px solid #ef4444; }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navbar -->
        <nav class="bg-white shadow">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="bg-green-600 p-2 rounded-lg">
                            <i class="fas fa-motorcycle text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-800">Repartidor</h1>
                            <p class="text-xs text-gray-600">Bienvenido, {{ Auth::user()->nombre }}</p>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Estado Actual</p>
                            <div class="flex items-center space-x-2">
                                @php
                                    $delivery = Auth::user()->delivery;
                                    $estado = $delivery ? $delivery->estado : 'inactivo';
                                    $colors = [
                                        'disponible' => 'text-green-600 bg-green-100',
                                        'ocupado' => 'text-yellow-600 bg-yellow-100',
                                        'inactivo' => 'text-red-600 bg-red-100'
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-sm font-medium {{ $colors[$estado] }}">
                                    {{ ucfirst($estado) }}
                                </span>
                            </div>
                        </div>

                        <!-- Pedidos Activos -->
                        <div class="text-center">
                            <p class="text-sm text-gray-600">Pedidos Activos</p>
                            <p class="text-xl font-bold text-blue-600">{{ $pedidos_activos ?? 0 }}</p>
                        </div>

                        <!-- Usuario -->
                        <div class="relative">
                            <button id="user-menu-toggle" class="flex items-center space-x-2">
                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600"></i>
                                </div>
                                <i class="fas fa-chevron-down text-gray-500"></i>
                            </button>

                            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border">
                                <div class="py-1">
                                    <a href="{{ route('dashboard.delivery') }}"
                                       class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-history mr-2"></i>Historial
                                    </a>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar Sesión
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenido -->
        <main class="container mx-auto px-4 py-6">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
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

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            // Toggle user menu
            $('#user-menu-toggle').click(function(e) {
                e.stopPropagation();
                $('#user-menu').toggleClass('hidden');
            });

            $(document).click(function() {
                $('#user-menu').addClass('hidden');
            });

            // Auto-hide alerts
            setTimeout(function() {
                $('.alert-auto-dismiss').fadeOut('slow');
            }, 5000);

            // Actualizar estado en tiempo real
            setInterval(function() {
                // Aquí iría la lógica para actualizar pedidos en tiempo real
            }, 30000);
        });

        function cambiarEstadoPedido(pedidoId, nuevoEstado) {
            if (!confirm('¿Estás seguro de cambiar el estado del pedido?')) return;

            $.ajax({
                url: `/pedidos/${pedidoId}/cambiar-estado`,
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    estado: nuevoEstado
                },
                success: function(response) {
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error al cambiar el estado');
                }
            });
        }

        function mostrarUbicacion(direccion) {
            // Aquí iría la integración con Google Maps
            window.open(`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(direccion)}`, '_blank');
        }
    </script>

    @stack('scripts')
</body>
</html>
