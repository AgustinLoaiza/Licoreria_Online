<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Licorería')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .sidebar {
            transition: all 0.3s;
        }
        .sidebar.collapsed {
            width: 70px;
        }
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        .main-content {
            transition: all 0.3s;
        }
        .active-nav {
            background-color: #3b82f6;
            color: white;
        }
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Sidebar -->
    <div class="flex h-screen">
        <div class="sidebar bg-gray-900 text-white w-64 flex flex-col">
            <!-- Logo -->
            <div class="p-4 border-b border-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="bg-blue-600 p-2 rounded-lg">
                        <i class="fas fa-crown text-white text-xl"></i>
                    </div>
                    <div class="sidebar-text">
                        <h1 class="text-xl font-bold">Licorería Admin</h1>
                        <p class="text-xs text-gray-400">Panel de Control</p>
                    </div>
                </div>
            </div>

            <!-- Menú Principal -->
            <nav class="flex-1 p-4 space-y-2">
                <a href="{{ route('dashboard.administrador') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('dashboard.admin') ? 'active-nav' : '' }}">
                    <i class="fas fa-tachometer-alt w-6"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <div class="text-gray-400 text-xs uppercase tracking-wider sidebar-text mt-6 mb-2">Gestión</div>

                <a href="{{ route('usuarios.index') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('usuarios.*') ? 'active-nav' : '' }}">
                    <i class="fas fa-users w-6"></i>
                    <span class="sidebar-text">Usuarios</span>
                </a>

                <a href="{{ route('productos.admin.index') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('productos.*') ? 'active-nav' : '' }}">
                    <i class="fas fa-wine-bottle w-6"></i>
                    <span class="sidebar-text">Productos</span>
                    @if($productos_bajo_stock ?? 0)
                    <span class="notification-badge bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $productos_bajo_stock }}
                    </span>
                    @endif
                </a>

                <a href="{{ route('pedidos.index') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('pedidos.*') ? 'active-nav' : '' }}">
                    <i class="fas fa-shopping-cart w-6"></i>
                    <span class="sidebar-text">Pedidos</span>
                    @if($pedidos_pendientes ?? 0)
                    <span class="notification-badge bg-yellow-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $pedidos_pendientes }}
                    </span>
                    @endif
                </a>

                <a href="{{ route('deliveries.index') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('deliveries.*') ? 'active-nav' : '' }}">
                    <i class="fas fa-motorcycle w-6"></i>
                    <span class="sidebar-text">Repartidores</span>
                </a>

                <a href="{{ route('ventas.index') }}"
                   class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('ventas.*') ? 'active-nav' : '' }}">
                    <i class="fas fa-chart-bar w-6"></i>
                    <span class="sidebar-text">Ventas</span>
                    @if($pagos_pendientes ?? 0)
                    <span class="notification-badge bg-blue-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ $pagos_pendientes }}
                    </span>
                    @endif
                </a>


            </nav>

            <!-- Usuario -->
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="sidebar-text">
                        <p class="font-medium">{{ Auth::user()->nombre }}</p>
                        <p class="text-xs text-gray-400">Administrador</p>
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 text-red-400">
                        <i class="fas fa-sign-out-alt w-6"></i>
                        <span class="sidebar-text">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="main-content flex-1 overflow-auto">
            <!-- Header -->
            <header class="bg-white shadow-sm border-b">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-600">@yield('page-description', 'Panel de control del administrador')</p>
                    </div>

                    <div class="flex items-center space-x-4">
                        <!-- Fecha y Hora -->
                        <div class="text-sm text-gray-600">
                            <i class="far fa-clock mr-2"></i>
                            <span id="current-time"></span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Contenido de la página -->
            <main class="p-6">
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

                @if(session('error'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Hora actual
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('es-ES', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateString = now.toLocaleDateString('es-ES', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
            document.getElementById('current-time').textContent =
                `${dateString} ${timeString}`;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Toggle sidebar
        $('#toggle-sidebar').click(function() {
            $('.sidebar').toggleClass('collapsed');
            $('.main-content').toggleClass('ml-64');
        });

        // Auto-hide alerts
        setTimeout(function() {
            $('.alert-auto-dismiss').fadeOut('slow');
        }, 5000);
    </script>

    @stack('scripts')
</body>
</html>
