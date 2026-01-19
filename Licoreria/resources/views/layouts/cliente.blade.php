<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cliente - Licorería')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .cart-item {
            transition: all 0.3s;
        }
        .cart-item:hover {
            transform: translateY(-2px);
        }
        .product-card {
            transition: all 0.3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="bg-red-600 p-2 rounded-lg">
                        <i class="fas fa-wine-bottle text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-800">Licorería Online</h1>
                        <p class="text-xs text-gray-600">Bienvenido, {{ Auth::user()->nombre }}</p>
                    </div>
                </div>

                <!-- Menú -->
                <div class="flex items-center space-x-6">
                    <a href="{{ route('dashboard.cliente') }}"
                       class="text-gray-700 hover:text-red-600 {{ request()->routeIs('dashboard.cliente') ? 'text-red-600 font-semibold' : '' }}">
                        <i class="fas fa-home mr-2"></i>Inicio
                    </a>

                    <a href="{{ route('productos.index') }}"
                       class="text-gray-700 hover:text-red-600 {{ request()->routeIs('productos.index') ? 'text-red-600 font-semibold' : '' }}">
                        <i class="fas fa-store mr-2"></i>Catálogo
                    </a>

                    <a href="{{ route('pedidos.index') }}"
                       class="text-gray-700 hover:text-red-600 {{ request()->routeIs('pedidos.*') ? 'text-red-600 font-semibold' : '' }}">
                        <i class="fas fa-shopping-cart mr-2"></i>Mis Pedidos
                        @if($pedidos_pendientes ?? 0)
                        <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-1">
                            {{ $pedidos_pendientes }}
                        </span>
                        @endif
                    </a>

                    <!-- Carrito -->
                    <div class="relative">
                        <button id="cart-toggle" class="text-gray-700 hover:text-red-600">
                            <i class="fas fa-shopping-cart text-xl"></i>
                            <span id="cart-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">0</span>
                        </button>

                        <!-- Carrito Dropdown -->
                        <div id="cart-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl z-50 border">
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-800 mb-3">Tu Carrito</h3>
                                <div id="cart-items" class="space-y-3 max-h-64 overflow-y-auto">
                                    <!-- Items del carrito aparecerán aquí -->
                                    <p class="text-gray-500 text-center py-4">No hay productos en el carrito</p>
                                </div>
                                <div class="border-t pt-3 mt-3">
                                    <div class="flex justify-between mb-3">
                                        <span class="font-semibold">Total:</span>
                                        <span id="cart-total" class="font-bold">S/ 0.00</span>
                                    </div>
                                    <button onclick="checkout()"
                                            class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
                                        Proceder al Pago
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Usuario -->
                    <div class="relative">
                        <button id="user-menu-toggle" class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                            <span class="text-gray-700">{{ Auth::user()->nombre }}</span>
                            <i class="fas fa-chevron-down text-gray-500"></i>
                        </button>

                        <!-- Dropdown Usuario -->
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 border">
                            <div class="py-1">
                                <a href="{{ route('usuarios.show', Auth::user()->id) }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user-circle mr-2"></i>Mi Perfil
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
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Contacto -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Contacto Rápido</h3>
                    <div class="space-y-3">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone bg-red-600 p-2 rounded-full"></i>
                            <div>
                                <p class="font-semibold">Llámanos ahora</p>
                                <a href="tel:+51999999999" class="text-xl font-bold hover:text-red-400">+51 999 999 999</a>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock bg-red-600 p-2 rounded-full"></i>
                            <div>
                                <p class="font-semibold">Horario de Atención</p>
                                <p>Lun-Dom: 9:00 AM - 11:00 PM</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Métodos de Pago -->
                <div>
                    <h3 class="text-xl font-bold mb-4">Métodos de Pago</h3>
                    <div class="flex space-x-4 mb-4">
                        <div class="bg-white p-2 rounded">
                            <i class="fas fa-qrcode text-3xl text-green-600"></i>
                        </div>
                        <div class="bg-white p-2 rounded">
                            <i class="fas fa-money-bill-wave text-3xl text-green-600"></i>
                        </div>
                        <div class="bg-white p-2 rounded">
                            <i class="fas fa-credit-card text-3xl text-blue-600"></i>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400">Aceptamos Yape, Plin, efectivo y tarjeta</p>
                </div>

                <!-- QR de Pago -->
                <div class="text-center">
                    <h3 class="text-xl font-bold mb-4">Pago por QR</h3>
                    <div class="bg-white p-4 rounded-lg inline-block">
                        <i class="fas fa-qrcode text-6xl text-gray-800"></i>
                        <p class="text-gray-800 mt-2 font-semibold">Escanea para pagar</p>
                        <p class="text-sm text-gray-600">Yape: 999 999 999</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>© 2024 Licorería Online. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Carrito
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        function updateCart() {
            $('#cart-count').text(cart.length);

            let total = 0;
            let itemsHtml = '';

            if (cart.length === 0) {
                itemsHtml = '<p class="text-gray-500 text-center py-4">No hay productos en el carrito</p>';
            } else {
                cart.forEach((item, index) => {
                    total += item.precio * item.cantidad;
                    itemsHtml += `
                        <div class="flex items-center justify-between cart-item p-2 hover:bg-gray-50 rounded">
                            <div>
                                <p class="font-medium">${item.nombre}</p>
                                <p class="text-sm text-gray-600">S/ ${item.precio} x ${item.cantidad}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="updateQuantity(${index}, -1)" class="text-gray-500 hover:text-red-600">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="w-8 text-center">${item.cantidad}</span>
                                <button onclick="updateQuantity(${index}, 1)" class="text-gray-500 hover:text-red-600">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700 ml-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
            }

            $('#cart-items').html(itemsHtml);
            $('#cart-total').text('S/ ' + total.toFixed(2));
            localStorage.setItem('cart', JSON.stringify(cart));
        }

        function addToCart(productId, nombre, precio) {
            const existingItem = cart.find(item => item.id === productId);

            if (existingItem) {
                existingItem.cantidad++;
            } else {
                cart.push({
                    id: productId,
                    nombre: nombre,
                    precio: parseFloat(precio),
                    cantidad: 1
                });
            }

            updateCart();
            showNotification(`${nombre} agregado al carrito`, 'success');
        }

        function updateQuantity(index, change) {
            cart[index].cantidad += change;

            if (cart[index].cantidad <= 0) {
                cart.splice(index, 1);
            }

            updateCart();
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCart();
        }

        function checkout() {
            if (cart.length === 0) {
                showNotification('El carrito está vacío', 'error');
                return;
            }

            // Aquí iría la lógica para procesar el pedido
            window.location.href = "{{ route('pedidos.create') }}?cart=" + encodeURIComponent(JSON.stringify(cart));
        }

        function showNotification(message, type = 'info') {
            const notification = $(`
                <div class="fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 ${
                    type === 'success' ? 'bg-green-100 text-green-800 border-green-300' :
                    type === 'error' ? 'bg-red-100 text-red-800 border-red-300' :
                    'bg-blue-100 text-blue-800 border-blue-300'
                } border-l-4">
                    <div class="flex items-center">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'} mr-2"></i>
                        <span>${message}</span>
                    </div>
                </div>
            `);

            $('body').append(notification);

            setTimeout(() => {
                notification.fadeOut('slow', () => notification.remove());
            }, 3000);
        }

        // Toggle dropdowns
        $(document).ready(function() {
            updateCart();

            $('#cart-toggle').click(function(e) {
                e.stopPropagation();
                $('#cart-dropdown').toggleClass('hidden');
                $('#user-menu').addClass('hidden');
            });

            $('#user-menu-toggle').click(function(e) {
                e.stopPropagation();
                $('#user-menu').toggleClass('hidden');
                $('#cart-dropdown').addClass('hidden');
            });

            $(document).click(function() {
                $('#cart-dropdown').addClass('hidden');
                $('#user-menu').addClass('hidden');
            });

            $('.dropdown').click(function(e) {
                e.stopPropagation();
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
