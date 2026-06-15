<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'R&E Distribuidora de Bebidas')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --brand-ink: #090b10;
            --brand-panel: #131720;
            --brand-red: #f10f3f;
            --brand-red-dark: #99051f;
            --brand-gold: #f59e0b;
            --brand-paper: #fff8ee;
        }

        body {
            background:
                linear-gradient(180deg, #fff8ee 0%, #f7f2ea 44%, #ffffff 100%);
        }

        .brand-shell {
            background:
                radial-gradient(circle at top left, rgba(245, 158, 11, 0.2), transparent 22rem),
                linear-gradient(135deg, #090b10 0%, #151922 72%, #2b0712 100%);
        }

        .brand-button {
            background: linear-gradient(135deg, var(--brand-red) 0%, var(--brand-red-dark) 100%);
        }

        .brand-button:hover {
            background: linear-gradient(135deg, #ff2755 0%, #b7082c 100%);
        }

        .cart-item,
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 18px 40px rgba(9, 11, 16, 0.12);
        }
    </style>

    @stack('styles')
</head>
<body class="min-h-screen text-gray-950">
    @php
        $whatsappNumber = '59172879400';
        $facebookUrl = 'https://www.facebook.com/share/1DABFWuLas/';
        $instagramUrl = 'https://www.instagram.com/rye_licoreria?igsh=OHBpZTRmd3U0dzRr';
        $whatsappGeneralMessage = 'Hola, quiero mas informacion sobre R&E Bebidas.';
        $whatsappGeneralUrl = 'https://wa.me/' . $whatsappNumber . '?text=' . rawurlencode($whatsappGeneralMessage);
        $clienteHomeUrl = Auth::check() ? route('dashboard.cliente') : route('productos.catalogo');
        $pedidosUrl = Auth::check() ? route('pedidos.index') : route('login');
        $displayName = Auth::check() ? Auth::user()->nombre : 'Visitante';
    @endphp

    <header class="sticky top-0 z-40 border-b border-white/10 brand-shell text-white shadow-xl">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex min-h-20 items-center justify-between gap-4 py-3">
                <a href="{{ $clienteHomeUrl }}" class="flex min-w-0 items-center gap-3">
                    <img src="{{ asset('images/logo-licoreria.jpeg') }}"
                         alt="R&E Distribuidora de Bebidas"
                         class="h-12 w-12 shrink-0 rounded-lg border border-white/15 object-cover shadow-md">
                    <div class="min-w-0">
                        <p class="truncate text-lg font-black leading-tight">R&E Bebidas</p>
                        <p class="truncate text-xs text-amber-100">Hola, {{ $displayName }}</p>
                    </div>
                </a>

                <nav class="hidden items-center gap-2 lg:flex">
                    <a href="{{ $clienteHomeUrl }}"
                       class="rounded-lg px-3 py-2 text-sm font-semibold transition hover:bg-white/10 {{ request()->routeIs('dashboard.cliente') ? 'bg-white/15 text-amber-200' : 'text-gray-100' }}">
                        <i class="fas fa-home mr-2"></i>Inicio
                    </a>
                    <a href="{{ route('productos.catalogo') }}"
                       class="rounded-lg px-3 py-2 text-sm font-semibold transition hover:bg-white/10 {{ request()->routeIs('productos.catalogo') ? 'bg-white/15 text-amber-200' : 'text-gray-100' }}">
                        <i class="fas fa-store mr-2"></i>Catalogo
                    </a>
                    <a href="{{ $pedidosUrl }}"
                       class="rounded-lg px-3 py-2 text-sm font-semibold transition hover:bg-white/10 {{ request()->routeIs('pedidos.*') ? 'bg-white/15 text-amber-200' : 'text-gray-100' }}">
                        <i class="fas fa-receipt mr-2"></i>Mis pedidos
                        @if($pedidos_pendientes ?? 0)
                            <span class="ml-1 rounded-full bg-amber-400 px-2 py-0.5 text-xs font-black text-gray-950">{{ $pedidos_pendientes }}</span>
                        @endif
                    </a>
                </nav>

                <div class="flex items-center gap-2">
                    <div class="relative">
                        <button id="cart-toggle"
                                class="relative rounded-lg border border-white/10 bg-white/10 px-3 py-2 text-white transition hover:bg-white/15"
                                aria-label="Abrir carrito">
                            <i class="fas fa-shopping-cart text-lg"></i>
                            <span id="cart-count" class="absolute -right-2 -top-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-amber-400 px-1 text-xs font-black text-gray-950">0</span>
                        </button>

                        <div id="cart-dropdown" class="hidden absolute right-0 mt-3 w-[min(22rem,calc(100vw-2rem))] rounded-xl border border-gray-200 bg-white text-gray-950 shadow-2xl">
                            <div class="p-4">
                                <div class="mb-3 flex items-center justify-between">
                                    <h3 class="font-black">Tu carrito</h3>
                                    <button type="button" class="text-gray-400 hover:text-gray-700" onclick="$('#cart-dropdown').addClass('hidden')" aria-label="Cerrar carrito">
                                        <i class="fas fa-xmark"></i>
                                    </button>
                                </div>
                                <div id="cart-items" class="max-h-72 space-y-3 overflow-y-auto pr-1">
                                    <p class="py-4 text-center text-sm text-gray-500">No hay productos en el carrito</p>
                                </div>
                                <div class="mt-4 border-t pt-4">
                                    <div class="mb-3 flex justify-between text-sm">
                                        <span class="font-semibold text-gray-600">Total</span>
                                        <span id="cart-total" class="text-lg font-black text-red-700">S/ 0.00</span>
                                    </div>
                                    <button onclick="checkout()" class="brand-button w-full rounded-lg py-3 text-sm font-black text-white transition">
                                        Proceder al pago
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    @auth
                    <div class="relative hidden sm:block">
                        <button id="user-menu-toggle" class="flex items-center gap-2 rounded-lg border border-white/10 bg-white/10 px-3 py-2 transition hover:bg-white/15">
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-amber-400 text-gray-950">
                                <i class="fas fa-user"></i>
                            </span>
                            <span class="max-w-32 truncate text-sm font-semibold">{{ Auth::user()->nombre }}</span>
                            <i class="fas fa-chevron-down text-xs text-gray-300"></i>
                        </button>

                        <div id="user-menu" class="hidden absolute right-0 mt-3 w-52 rounded-xl border border-gray-200 bg-white py-2 text-gray-950 shadow-2xl">
                            <a href="{{ route('usuarios.show', Auth::user()->id) }}" class="block px-4 py-2 text-sm hover:bg-gray-50">
                                <i class="fas fa-user-circle mr-2 text-red-700"></i>Mi perfil
                            </a>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-700 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesion
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="hidden items-center gap-2 sm:flex">
                        <a href="{{ route('login') }}" class="rounded-lg border border-white/10 bg-white/10 px-3 py-2 text-sm font-bold hover:bg-white/15">
                            Iniciar sesion
                        </a>
                        <a href="{{ route('register') }}" class="rounded-lg bg-amber-400 px-3 py-2 text-sm font-black text-gray-950 hover:bg-amber-300">
                            Registrarse
                        </a>
                    </div>
                    @endauth

                    <button id="mobile-menu-toggle"
                            class="rounded-lg border border-white/10 bg-white/10 px-3 py-2 text-white lg:hidden"
                            aria-label="Abrir menu">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>

            <div id="mobile-menu" class="hidden border-t border-white/10 py-3 lg:hidden">
                <div class="grid gap-2 text-sm font-semibold">
                    <a href="{{ $clienteHomeUrl }}" class="rounded-lg px-3 py-3 hover:bg-white/10">
                        <i class="fas fa-home mr-2 text-amber-300"></i>Inicio
                    </a>
                    <a href="{{ route('productos.catalogo') }}" class="rounded-lg px-3 py-3 hover:bg-white/10">
                        <i class="fas fa-store mr-2 text-amber-300"></i>Catalogo
                    </a>
                    <a href="{{ $pedidosUrl }}" class="rounded-lg px-3 py-3 hover:bg-white/10">
                        <i class="fas fa-receipt mr-2 text-amber-300"></i>Mis pedidos
                    </a>
                    @auth
                    <a href="{{ route('usuarios.show', Auth::user()->id) }}" class="rounded-lg px-3 py-3 hover:bg-white/10">
                        <i class="fas fa-user-circle mr-2 text-amber-300"></i>Mi perfil
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full rounded-lg px-3 py-3 text-left text-red-200 hover:bg-white/10">
                            <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesion
                        </button>
                    </form>
                    @else
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-3 hover:bg-white/10">
                        <i class="fas fa-right-to-bracket mr-2 text-amber-300"></i>Iniciar sesion
                    </a>
                    <a href="{{ route('register') }}" class="rounded-lg px-3 py-3 hover:bg-white/10">
                        <i class="fas fa-user-plus mr-2 text-amber-300"></i>Registrarse
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <footer class="mt-12 brand-shell text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-[1.1fr_0.9fr_0.8fr] lg:px-8">
            <div>
                <div class="mb-4 flex items-center gap-3">
                    <img src="{{ asset('images/logo-licoreria.jpeg') }}" alt="R&E Bebidas" class="h-14 w-14 rounded-lg object-cover">
                    <div>
                        <h3 class="text-xl font-black">R&E Distribuidora</h3>
                        <p class="text-sm text-amber-100">Bebidas, hielo y complementos</p>
                    </div>
                </div>
                <p class="max-w-md text-sm leading-6 text-gray-300">
                    Servicio de pedidos online con opciones de pago flexibles y entregas coordinadas.
                </p>
            </div>

            <div>
                <h3 class="mb-4 text-lg font-black">Contacto rapido</h3>
                <div class="space-y-3 text-sm text-gray-200">
                    <a href="{{ $whatsappGeneralUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center gap-3 hover:text-amber-200">
                        <i class="fab fa-whatsapp flex h-9 w-9 items-center justify-center rounded-lg bg-green-600"></i>
                        WhatsApp
                    </a>
                    <a href="tel:+59172879400" class="flex items-center gap-3 hover:text-amber-200">
                        <i class="fas fa-phone flex h-9 w-9 items-center justify-center rounded-lg bg-red-600"></i>
                        +591 72879400
                    </a>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-clock flex h-9 w-9 items-center justify-center rounded-lg bg-red-600"></i>
                        Lun-Dom: 9:00 AM - 11:00 PM
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="fas fa-credit-card flex h-9 w-9 items-center justify-center rounded-lg bg-red-600"></i>
                        QR, efectivo y tarjeta
                    </div>
                </div>

                <div class="mt-5 flex gap-3">
                    <a href="{{ $facebookUrl }}" target="_blank" rel="noopener noreferrer" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white transition hover:bg-blue-600" aria-label="Facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="{{ $instagramUrl }}" target="_blank" rel="noopener noreferrer" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white transition hover:bg-pink-600" aria-label="Instagram">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="{{ $whatsappGeneralUrl }}" target="_blank" rel="noopener noreferrer" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white transition hover:bg-green-600" aria-label="WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>

            <div>
                <h3 class="mb-4 text-lg font-black">Pago por QR</h3>
                <button type="button"
                        class="rounded-xl bg-white p-3 text-left text-gray-950 shadow-xl transition hover:-translate-y-0.5"
                        onclick="window.dispatchEvent(new Event('open-payment-qr'))">
                    <img src="{{ asset('images/qr-pagos.jpeg') }}" alt="QR de pagos" class="h-36 w-36 rounded-lg object-cover">
                    <p class="mt-2 text-sm font-black">Escanear QR</p>
                    <p class="text-xs text-gray-500">Altoke / pago movil</p>
                </button>
            </div>
        </div>
    </footer>

    <a href="{{ $whatsappGeneralUrl }}"
       target="_blank"
       rel="noopener noreferrer"
       class="fixed bottom-5 right-5 z-50 flex h-14 w-14 items-center justify-center rounded-full bg-green-600 text-2xl text-white shadow-2xl shadow-green-900/30 transition hover:-translate-y-1 hover:bg-green-700"
       aria-label="Contactar por WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        let cart = JSON.parse(localStorage.getItem('cart')) || [];

        function updateCart() {
            $('#cart-count').text(cart.length);

            let total = 0;
            let itemsHtml = '';

            if (cart.length === 0) {
                itemsHtml = '<p class="py-4 text-center text-sm text-gray-500">No hay productos en el carrito</p>';
            } else {
                cart.forEach((item, index) => {
                    total += item.precio * item.cantidad;
                    itemsHtml += `
                        <div class="cart-item rounded-lg border border-gray-100 p-3 hover:bg-gray-50">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <p class="truncate font-bold">${item.nombre}</p>
                                    <p class="text-sm text-gray-600">S/ ${item.precio.toFixed(2)} x ${item.cantidad}</p>
                                </div>
                                <button onclick="removeFromCart(${index})" class="text-red-500 hover:text-red-700" aria-label="Quitar producto">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="mt-3 flex items-center justify-between">
                                <div class="flex items-center rounded-lg border">
                                    <button onclick="updateQuantity(${index}, -1)" class="px-3 py-1 text-gray-600 hover:text-red-600" aria-label="Restar">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <span class="w-8 text-center text-sm font-bold">${item.cantidad}</span>
                                    <button onclick="updateQuantity(${index}, 1)" class="px-3 py-1 text-gray-600 hover:text-red-600" aria-label="Sumar">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <span class="font-black text-red-700">S/ ${(item.precio * item.cantidad).toFixed(2)}</span>
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
                showNotification('El carrito esta vacio', 'error');
                return;
            }

            window.location.href = "{{ route('pedidos.create') }}?cart=" + encodeURIComponent(JSON.stringify(cart));
        }

        function showNotification(message, type = 'info') {
            const notification = $(`
                <div class="fixed right-4 top-24 z-50 max-w-[calc(100vw-2rem)] rounded-lg border-l-4 px-4 py-3 shadow-xl ${
                    type === 'success' ? 'border-emerald-500 bg-emerald-50 text-emerald-800' :
                    type === 'error' ? 'border-red-500 bg-red-50 text-red-800' :
                    'border-blue-500 bg-blue-50 text-blue-800'
                }">
                    <div class="flex items-center text-sm font-semibold">
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

        $(document).ready(function() {
            updateCart();

            $('#cart-toggle').click(function(e) {
                e.stopPropagation();
                $('#cart-dropdown').toggleClass('hidden');
                $('#user-menu').addClass('hidden');
                $('#mobile-menu').addClass('hidden');
            });

            $('#user-menu-toggle').click(function(e) {
                e.stopPropagation();
                $('#user-menu').toggleClass('hidden');
                $('#cart-dropdown').addClass('hidden');
            });

            $('#mobile-menu-toggle').click(function(e) {
                e.stopPropagation();
                $('#mobile-menu').toggleClass('hidden');
                $('#cart-dropdown').addClass('hidden');
                $('#user-menu').addClass('hidden');
            });

            $('#cart-dropdown, #user-menu, #mobile-menu').click(function(e) {
                e.stopPropagation();
            });

            $(document).click(function() {
                $('#cart-dropdown').addClass('hidden');
                $('#user-menu').addClass('hidden');
                $('#mobile-menu').addClass('hidden');
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
