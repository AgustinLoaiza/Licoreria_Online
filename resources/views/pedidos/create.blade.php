@extends('layouts.cliente')

@section('title', 'Nuevo pedido - R&E Bebidas')
@section('page-title', 'Realizar nuevo pedido')

@section('content')
<div class="mx-auto max-w-6xl space-y-6">
    <section class="overflow-hidden rounded-xl border border-amber-100 bg-white shadow-lg" id="cart-section">
        <div class="border-b bg-[#11151d] px-5 py-4 text-white sm:px-6">
            <h3 class="text-lg font-black">Tu carrito</h3>
        </div>
        <div class="p-4 sm:p-6">
            <div id="cart-items-list" class="space-y-4"></div>

            <div class="mt-6 border-t pt-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600">Total estimado</p>
                        <p id="cart-total-display" class="text-3xl font-black text-red-700">S/ 0.00</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="button"
                                onclick="vaciarCarrito()"
                                class="rounded-lg bg-gray-200 px-4 py-2 font-semibold text-gray-800 hover:bg-gray-300">
                            <i class="fas fa-trash mr-2"></i>Vaciar carrito
                        </button>
                        <a href="{{ route('productos.index') }}"
                           class="rounded-lg bg-[#11151d] px-4 py-2 text-center font-semibold text-white hover:bg-black">
                            <i class="fas fa-plus mr-2"></i>Agregar mas productos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <form action="{{ route('pedidos.store') }}" method="POST" id="pedido-form">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_0.86fr]">
            <div class="space-y-6">
                <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg">
                    <div class="border-b px-5 py-4 sm:px-6">
                        <h3 class="text-lg font-black text-gray-950">Informacion de entrega</h3>
                    </div>
                    <div class="space-y-4 p-4 sm:p-6">
                        <div>
                            <label for="nombre_pedido" class="mb-2 block text-sm font-semibold text-gray-700">
                                Nombre para el pedido
                            </label>
                            <input type="text"
                                   id="nombre_pedido"
                                   name="nombre_pedido"
                                   placeholder="Ej: Pedido para reunion"
                                   class="w-full rounded-lg border px-4 py-3 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100">
                        </div>

                        <div>
                            <label for="direccion" class="mb-2 block text-sm font-semibold text-gray-700">
                                Direccion de entrega *
                            </label>
                            <textarea id="direccion"
                                      name="direccion"
                                      rows="3"
                                      class="w-full rounded-lg border px-4 py-3 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100"
                                      required>{{ old('direccion', Auth::user()->direccion ?? '') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Indica la direccion completa para la entrega.</p>
                        </div>

                        <div>
                            <label for="notas" class="mb-2 block text-sm font-semibold text-gray-700">
                                Instrucciones especiales
                            </label>
                            <textarea id="notas"
                                      name="notas"
                                      rows="2"
                                      placeholder="Ej: timbre no funciona, llamar al llegar"
                                      class="w-full rounded-lg border px-4 py-3 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100"></textarea>
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg">
                    <div class="border-b px-5 py-4 sm:px-6">
                        <h3 class="text-lg font-black text-gray-950">Metodo de pago</h3>
                    </div>
                    <div class="space-y-3 p-4 sm:p-6">
                        <div class="flex items-start rounded-lg border border-red-100 bg-red-50/70 p-3">
                            <input type="radio"
                                   id="metodo_qr"
                                   name="metodo_pago"
                                   value="qr"
                                   checked
                                   class="mt-3 h-5 w-5 text-red-700">
                            <label for="metodo_qr" class="ml-3 flex-1 cursor-pointer">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex items-center">
                                        <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-red-700 text-white">
                                            <i class="fas fa-qrcode"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-950">Pago por QR Altoke</p>
                                            <p class="text-sm text-gray-600">Escanea el codigo QR antes de confirmar.</p>
                                        </div>
                                    </div>
                                    <button type="button"
                                            class="open-payment-qr rounded-lg bg-[#11151d] px-4 py-2 text-sm font-bold text-white hover:bg-black">
                                        Ver QR
                                    </button>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center rounded-lg border p-3 transition hover:bg-gray-50">
                            <input type="radio" id="metodo_efectivo" name="metodo_pago" value="efectivo" class="h-5 w-5 text-red-700">
                            <label for="metodo_efectivo" class="ml-3 flex-1 cursor-pointer">
                                <div class="flex items-center">
                                    <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100">
                                        <i class="fas fa-money-bill-wave text-amber-700"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-950">Pago en efectivo</p>
                                        <p class="text-sm text-gray-600">Paga al recibir tu pedido.</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div class="flex items-center rounded-lg border p-3 transition hover:bg-gray-50">
                            <input type="radio" id="metodo_tarjeta" name="metodo_pago" value="tarjeta" class="h-5 w-5 text-red-700">
                            <label for="metodo_tarjeta" class="ml-3 flex-1 cursor-pointer">
                                <div class="flex items-center">
                                    <div class="mr-3 flex h-10 w-10 items-center justify-center rounded-lg bg-gray-100">
                                        <i class="fas fa-credit-card text-gray-700"></i>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-950">Tarjeta de credito/debito</p>
                                        <p class="text-sm text-gray-600">Pago con tarjeta al entregar.</p>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <div id="qr-info" class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                            <div class="mb-3 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-amber-700"></i>
                                <p class="font-bold text-amber-900">Instrucciones para pago QR</p>
                            </div>
                            <ul class="space-y-1 text-sm text-amber-900">
                                <li>1. Realiza el pago escaneando el codigo QR.</li>
                                <li>2. Guarda el comprobante de pago.</li>
                                <li>3. Despues de realizar el pedido, podras subir el comprobante.</li>
                                <li>4. El administrador verificara tu pago.</li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg">
                    <div class="border-b px-5 py-4 sm:px-6">
                        <h3 class="text-lg font-black text-gray-950">Resumen del pedido</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div id="resumen-productos" class="mb-4 space-y-3"></div>

                        <div class="space-y-2 border-t pt-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span id="resumen-subtotal" class="font-bold">S/ 0.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Costo de delivery</span>
                                <span class="font-bold">S/ 0.00</span>
                            </div>
                            <div class="flex justify-between border-t pt-3 text-lg font-black">
                                <span>Total a pagar</span>
                                <span id="resumen-total" class="text-red-700">S/ 0.00</span>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg">
                    <div class="border-b px-5 py-4 sm:px-6">
                        <h3 class="text-lg font-black text-gray-950">Necesitas ayuda?</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <div class="mb-4 flex items-center">
                            <div class="mr-4 flex h-12 w-12 items-center justify-center rounded-lg bg-red-100">
                                <i class="fas fa-phone text-red-700"></i>
                            </div>
                            <div>
                                <p class="text-lg font-black text-gray-950">Llamanos al instante</p>
                                <a href="tel:+51999999999" class="font-black text-red-700 hover:text-red-800">+51 999 999 999</a>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600">Si tienes dudas sobre tu pedido o prefieres hacerlo por telefono, estamos disponibles para ayudarte.</p>
                    </div>
                </section>

                <section class="sticky bottom-6 rounded-xl border-2 border-amber-200 bg-white p-4 shadow-xl sm:p-6 lg:top-24 lg:bottom-auto">
                    <div class="mb-4 flex items-center justify-between gap-4">
                        <div>
                            <p class="font-semibold text-gray-700">Confirmar pedido</p>
                            <p id="total-confirmacion" class="text-2xl font-black text-red-700">S/ 0.00</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Tiempo estimado</p>
                            <p class="font-black text-gray-950">30-45 min</p>
                        </div>
                    </div>

                    <input type="hidden" name="usuario_id" value="{{ Auth::id() }}">
                    <input type="hidden" name="productos" id="productos-data">

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('productos.index') }}" class="flex-1 rounded-lg bg-gray-200 py-3 text-center font-bold text-gray-800 hover:bg-gray-300">
                            Seguir comprando
                        </a>
                        <button type="submit"
                                id="confirmar-btn"
                                class="flex-1 rounded-lg bg-red-700 py-3 text-lg font-black text-white hover:bg-red-800"
                                disabled>
                            <i class="fas fa-check-circle mr-2"></i>Confirmar
                        </button>
                    </div>
                    <p class="mt-3 text-center text-xs text-gray-500">Al confirmar, aceptas los terminos del servicio.</p>
                </section>
            </aside>
        </div>
    </form>
</div>

<div id="payment-qr-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/70 p-4">
    <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl">
        <div class="flex items-center justify-between border-b px-5 py-4">
            <div>
                <h3 class="text-lg font-black text-gray-950">Pago por QR</h3>
                <p class="text-sm text-gray-500">Escanea y guarda tu comprobante.</p>
            </div>
            <button type="button" class="close-payment-qr rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900" aria-label="Cerrar QR">
                <i class="fas fa-xmark text-xl"></i>
            </button>
        </div>
        <div class="p-5 text-center">
            <img src="{{ asset('images/qr-pagos.jpeg') }}"
                 alt="Codigo QR de pago"
                 class="mx-auto w-full max-w-xs rounded-xl border object-cover shadow-lg">
            <div class="mt-4 rounded-lg bg-amber-50 p-4 text-left text-sm text-amber-900">
                <p class="font-black">Importante</p>
                <p>Verifica el monto total antes de pagar y conserva tu comprobante para subirlo despues del pedido.</p>
            </div>
            <button type="button" class="close-payment-qr mt-5 w-full rounded-lg bg-red-700 px-4 py-3 font-black text-white hover:bg-red-800">
                Listo
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function actualizarVista() {
    const cartList = document.getElementById('cart-items-list');
    const resumenProductos = document.getElementById('resumen-productos');
    let htmlCart = '';
    let htmlResumen = '';
    let total = 0;

    if (cart.length === 0) {
        htmlCart = `
            <div class="rounded-lg border border-dashed p-8 text-center">
                <i class="fas fa-shopping-cart mb-3 text-4xl text-gray-300"></i>
                <p class="text-gray-600">Tu carrito esta vacio</p>
                <a href="{{ route('productos.index') }}" class="mt-4 inline-block rounded-lg bg-red-700 px-4 py-2 font-bold text-white hover:bg-red-800">
                    Ir al catalogo
                </a>
            </div>
        `;
        htmlResumen = '<p class="py-4 text-center text-sm text-gray-500">No hay productos</p>';
    } else {
        cart.forEach((item, index) => {
            const subtotal = item.precio * item.cantidad;
            total += subtotal;

            htmlCart += `
                <div class="flex flex-col gap-3 rounded-lg border p-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex min-w-0 items-center">
                        <div class="mr-4 flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-[#11151d] text-amber-300">
                            <i class="fas fa-wine-bottle"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="truncate font-bold text-gray-950">${item.nombre}</p>
                            <p class="text-sm text-gray-600">S/ ${item.precio.toFixed(2)} c/u</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 sm:justify-end">
                        <div class="flex items-center rounded-lg border">
                            <button type="button" onclick="actualizarCantidad(${index}, -1)" class="px-3 py-1 text-gray-600 hover:text-red-700">
                                <i class="fas fa-minus"></i>
                            </button>
                            <span class="px-3 font-bold">${item.cantidad}</span>
                            <button type="button" onclick="actualizarCantidad(${index}, 1)" class="px-3 py-1 text-gray-600 hover:text-red-700">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>

                        <p class="w-24 text-right font-black text-gray-950">S/ ${subtotal.toFixed(2)}</p>

                        <button type="button" onclick="eliminarDelCarrito(${index})" class="text-red-500 hover:text-red-700" aria-label="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            `;

            htmlResumen += `
                <div class="flex justify-between gap-4">
                    <div class="min-w-0">
                        <p class="truncate font-bold text-gray-950">${item.nombre}</p>
                        <p class="text-sm text-gray-600">${item.cantidad} x S/ ${item.precio.toFixed(2)}</p>
                    </div>
                    <p class="shrink-0 font-bold">S/ ${subtotal.toFixed(2)}</p>
                </div>
            `;
        });
    }

    cartList.innerHTML = htmlCart;
    resumenProductos.innerHTML = htmlResumen;

    document.getElementById('cart-total-display').textContent = `S/ ${total.toFixed(2)}`;
    document.getElementById('resumen-subtotal').textContent = `S/ ${total.toFixed(2)}`;
    document.getElementById('resumen-total').textContent = `S/ ${total.toFixed(2)}`;
    document.getElementById('total-confirmacion').textContent = `S/ ${total.toFixed(2)}`;

    const confirmarBtn = document.getElementById('confirmar-btn');
    confirmarBtn.disabled = cart.length === 0;
    confirmarBtn.classList.toggle('opacity-50', cart.length === 0);
    confirmarBtn.classList.toggle('cursor-not-allowed', cart.length === 0);

    localStorage.setItem('cart', JSON.stringify(cart));
    if (typeof updateCart === 'function') updateCart();
}

function actualizarCantidad(index, cambio) {
    cart[index].cantidad += cambio;
    if (cart[index].cantidad <= 0) {
        cart.splice(index, 1);
    }
    actualizarVista();
}

function eliminarDelCarrito(index) {
    if (confirm('Eliminar este producto del carrito?')) {
        cart.splice(index, 1);
        actualizarVista();
    }
}

function vaciarCarrito() {
    if (cart.length === 0) return;
    if (confirm('Vaciar todo el carrito?')) {
        cart = [];
        actualizarVista();
    }
}

function abrirModalQr() {
    const modal = document.getElementById('payment-qr-modal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function cerrarModalQr() {
    const modal = document.getElementById('payment-qr-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document.addEventListener('DOMContentLoaded', function() {
    actualizarVista();

    document.querySelectorAll('.open-payment-qr').forEach((button) => {
        button.addEventListener('click', abrirModalQr);
    });

    document.querySelectorAll('.close-payment-qr').forEach((button) => {
        button.addEventListener('click', cerrarModalQr);
    });

    document.getElementById('payment-qr-modal').addEventListener('click', function(event) {
        if (event.target === this) cerrarModalQr();
    });

    window.addEventListener('open-payment-qr', abrirModalQr);

    const form = document.getElementById('pedido-form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        if (cart.length === 0) {
            alert('El carrito esta vacio');
            return;
        }

        const productosData = cart.map(item => ({
            id: item.id,
            cantidad: item.cantidad
        }));

        document.getElementById('productos-data').value = JSON.stringify(productosData);

        if (confirm('Confirmar pedido?')) {
            form.submit();
        }
    });
});
</script>
@endpush
@endsection
