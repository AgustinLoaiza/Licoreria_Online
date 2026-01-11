@extends('layouts.cliente')

@section('title', 'Nuevo Pedido')
@section('page-title', 'Realizar Nuevo Pedido')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Carrito actual -->
    <div class="bg-white rounded-xl shadow mb-6" id="cart-section">
        <div class="px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-800">Tu Carrito</h3>
        </div>
        <div class="p-6">
            <div id="cart-items-list" class="space-y-4">
                <!-- Los items del carrito se cargarán aquí con JavaScript -->
            </div>

            <div class="mt-6 pt-6 border-t">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-gray-600">Total estimado</p>
                        <p id="cart-total-display" class="text-3xl font-bold text-gray-800">S/ 0.00</p>
                    </div>
                    <div>
                        <button onclick="vaciarCarrito()"
                                class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 mr-3">
                            <i class="fas fa-trash mr-2"></i> Vaciar Carrito
                        </button>
                        <a href="{{ route('productos.index') }}"
                           class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i> Agregar más productos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulario del pedido -->
    <form action="{{ route('pedidos.store') }}" method="POST" id="pedido-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Información de entrega -->
            <div class="space-y-6">
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Información de Entrega</h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <!-- Nombre del pedido -->
                        <div>
                            <label for="nombre_pedido" class="block text-sm font-medium text-gray-700 mb-2">
                                Nombre para el pedido (opcional)
                            </label>
                            <input type="text"
                                   id="nombre_pedido"
                                   name="nombre_pedido"
                                   placeholder="Ej: Pedido para fiesta de Juan"
                                   class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Dirección -->
                        <div>
                            <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección de entrega *
                            </label>
                            <textarea id="direccion"
                                      name="direccion"
                                      rows="3"
                                      class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      required>{{ old('direccion', Auth::user()->direccion ?? '') }}</textarea>
                            <p class="text-sm text-gray-500 mt-1">
                                Indica la dirección completa para la entrega
                            </p>
                        </div>

                        <!-- Notas -->
                        <div>
                            <label for="notas" class="block text-sm font-medium text-gray-700 mb-2">
                                Instrucciones especiales (opcional)
                            </label>
                            <textarea id="notas"
                                      name="notas"
                                      rows="2"
                                      placeholder="Ej: Dejar en el porche, timbre no funciona, etc."
                                      class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Método de pago -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Método de Pago</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio"
                                       id="metodo_qr"
                                       name="metodo_pago"
                                       value="qr"
                                       checked
                                       class="h-5 w-5 text-blue-600">
                                <label for="metodo_qr" class="ml-3 flex-1 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-qrcode text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Pago por QR (Yape/Plin)</p>
                                            <p class="text-sm text-gray-600">Escanea el código QR para pagar</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio"
                                       id="metodo_efectivo"
                                       name="metodo_pago"
                                       value="efectivo"
                                       class="h-5 w-5 text-blue-600">
                                <label for="metodo_efectivo" class="ml-3 flex-1 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-money-bill-wave text-yellow-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Pago en efectivo</p>
                                            <p class="text-sm text-gray-600">Paga al recibir tu pedido</p>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio"
                                       id="metodo_tarjeta"
                                       name="metodo_pago"
                                       value="tarjeta"
                                       class="h-5 w-5 text-blue-600">
                                <label for="metodo_tarjeta" class="ml-3 flex-1 cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-credit-card text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-800">Tarjeta de crédito/débito</p>
                                            <p class="text-sm text-gray-600">Pago con tarjeta al entregar</p>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Información de pago QR -->
                        <div id="qr-info" class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-info-circle text-green-600 mr-2"></i>
                                <p class="font-medium text-green-800">Instrucciones para pago QR</p>
                            </div>
                            <ul class="text-sm text-green-700 space-y-1">
                                <li>1. Realiza el pago escaneando el código QR</li>
                                <li>2. Guarda el comprobante de pago</li>
                                <li>3. Después de realizar el pedido, podrás subir el comprobante</li>
                                <li>4. El administrador verificará tu pago</li>
                                <li>5. Una vez verificado, prepararemos tu pedido</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen y confirmación -->
            <div class="space-y-6">
                <!-- Resumen del pedido -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">Resumen del Pedido</h3>
                    </div>
                    <div class="p-6">
                        <div id="resumen-productos" class="space-y-3 mb-4">
                            <!-- Productos se cargarán aquí -->
                        </div>

                        <div class="border-t pt-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="resumen-subtotal" class="font-semibold">S/ 0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Costo de delivery:</span>
                                <span class="font-semibold">S/ 0.00</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold border-t pt-2">
                                <span>Total a pagar:</span>
                                <span id="resumen-total" class="text-red-600">S/ 0.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contacto de emergencia -->
                <div class="bg-white rounded-xl shadow">
                    <div class="px-6 py-4 border-b">
                        <h3 class="text-lg font-semibold text-gray-800">¿Necesitas ayuda?</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-phone text-red-600"></i>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800 text-lg">Llámanos al instante</p>
                                <a href="tel:+51999999999" class="text-red-600 font-bold text-xl hover:text-red-700">
                                    +51 999 999 999
                                </a>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">
                            Si tienes dudas sobre tu pedido o prefieres hacerlo por teléfono,
                            estamos disponibles las 24 horas.
                        </p>
                    </div>
                </div>

                <!-- Botón de confirmación -->
                <div class="sticky bottom-6">
                    <div class="bg-white rounded-xl shadow-lg p-6 border-2 border-blue-200">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="font-medium text-gray-800">Confirmar pedido</p>
                                <p id="total-confirmacion" class="text-2xl font-bold text-red-600">S/ 0.00</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">Tiempo estimado</p>
                                <p class="font-bold text-gray-800">30-45 min</p>
                            </div>
                        </div>

                        <input type="hidden" name="productos" id="productos-data">

                        <div class="flex space-x-3">
                            <a href="{{ route('productos.index') }}"
                               class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg hover:bg-gray-300 text-center">
                                Seguir comprando
                            </a>
                            <button type="submit"
                                    id="confirmar-btn"
                                    class="flex-1 bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 text-lg font-bold"
                                    disabled>
                                <i class="fas fa-check-circle mr-2"></i> Confirmar Pedido
                            </button>
                        </div>

                        <p class="text-xs text-gray-500 text-center mt-3">
                            Al confirmar, aceptas nuestros términos y condiciones
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function actualizarVista() {
        // Actualizar lista del carrito
        const cartList = document.getElementById('cart-items-list');
        const resumenProductos = document.getElementById('resumen-productos');
        let htmlCart = '';
        let htmlResumen = '';
        let total = 0;

        if (cart.length === 0) {
            htmlCart = `
                <div class="text-center py-8">
                    <i class="fas fa-shopping-cart text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-600">Tu carrito está vacío</p>
                    <a href="{{ route('productos.index') }}"
                       class="inline-block mt-3 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                        Ir al catálogo
                    </a>
                </div>
            `;
            htmlResumen = '<p class="text-gray-500 text-center py-4">No hay productos</p>';
        } else {
            cart.forEach((item, index) => {
                const subtotal = item.precio * item.cantidad;
                total += subtotal;

                htmlCart += `
                    <div class="flex items-center justify-between p-3 border rounded-lg">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-wine-bottle text-gray-400"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">${item.nombre}</p>
                                <p class="text-sm text-gray-600">S/ ${item.precio.toFixed(2)} c/u</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex items-center border rounded-lg">
                                <button type="button"
                                        onclick="actualizarCantidad(${index}, -1)"
                                        class="px-3 py-1 text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <span class="px-3">${item.cantidad}</span>
                                <button type="button"
                                        onclick="actualizarCantidad(${index}, 1)"
                                        class="px-3 py-1 text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <p class="font-bold text-gray-800 w-20 text-right">
                                S/ ${subtotal.toFixed(2)}
                            </p>

                            <button type="button"
                                    onclick="eliminarDelCarrito(${index})"
                                    class="text-red-500 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `;

                htmlResumen += `
                    <div class="flex justify-between">
                        <div>
                            <p class="font-medium">${item.nombre}</p>
                            <p class="text-sm text-gray-600">${item.cantidad} x S/ ${item.precio.toFixed(2)}</p>
                        </div>
                        <p class="font-semibold">S/ ${subtotal.toFixed(2)}</p>
                    </div>
                `;
            });
        }

        cartList.innerHTML = htmlCart;
        resumenProductos.innerHTML = htmlResumen;

        // Actualizar totales
        document.getElementById('cart-total-display').textContent = `S/ ${total.toFixed(2)}`;
        document.getElementById('resumen-subtotal').textContent = `S/ ${total.toFixed(2)}`;
        document.getElementById('resumen-total').textContent = `S/ ${total.toFixed(2)}`;
        document.getElementById('total-confirmacion').textContent = `S/ ${total.toFixed(2)}`;

        // Habilitar/deshabilitar botón de confirmar
        const confirmarBtn = document.getElementById('confirmar-btn');
        if (cart.length > 0) {
            confirmarBtn.disabled = false;
            confirmarBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            confirmarBtn.disabled = true;
            confirmarBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }

        // Actualizar localStorage
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    function actualizarCantidad(index, cambio) {
        cart[index].cantidad += cambio;

        if (cart[index].cantidad <= 0) {
            cart.splice(index, 1);
        }

        actualizarVista();
    }

    function eliminarDelCarrito(index) {
        if (confirm('¿Eliminar este producto del carrito?')) {
            cart.splice(index, 1);
            actualizarVista();
        }
    }

    function vaciarCarrito() {
        if (cart.length === 0) return;

        if (confirm('¿Vaciar todo el carrito?')) {
            cart = [];
            actualizarVista();
        }
    }

    // Inicializar vista
    document.addEventListener('DOMContentLoaded', function() {
        actualizarVista();

        // Configurar envío del formulario
        const form = document.getElementById('pedido-form');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (cart.length === 0) {
                alert('El carrito está vacío');
                return;
            }

            // Preparar datos de productos para el servidor
            const productosData = cart.map(item => ({
                id: item.id,
                cantidad: item.cantidad
            }));

            document.getElementById('productos-data').value = JSON.stringify(productosData);

            // Mostrar confirmación
            if (confirm('¿Confirmar pedido?')) {
                form.submit();
            }
        });
    });
</script>
@endpush
@endsection
