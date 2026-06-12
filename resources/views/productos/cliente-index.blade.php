@extends('layouts.cliente')

@section('title', 'Catalogo - R&E Bebidas')
@section('page-title', 'Catalogo')

@section('content')
<div class="space-y-6">
    <section class="brand-shell overflow-hidden rounded-2xl p-5 text-white shadow-xl sm:p-8">
        <div class="grid gap-6 md:grid-cols-[1fr_auto] md:items-center">
            <div>
                <div class="mb-4 inline-flex items-center rounded-full border border-amber-300/40 bg-white/10 px-4 py-2 text-sm font-bold text-amber-100">
                    <i class="fas fa-bolt mr-2 text-amber-300"></i>
                    Delivery 30-45 min
                </div>
                <h1 class="max-w-2xl text-3xl font-black leading-tight sm:text-4xl">
                    Encuentra bebidas, hielo y complementos para tu pedido.
                </h1>
                <p class="mt-3 max-w-xl text-sm leading-6 text-gray-200 sm:text-base">
                    Compra desde tu celular, tablet o computadora con carrito rapido y pago por QR.
                </p>
                <div class="mt-6 flex flex-wrap gap-3 text-sm font-semibold">
                    <span class="rounded-lg bg-white/10 px-4 py-2"><i class="fas fa-shield-halved mr-2 text-amber-300"></i>Compra segura</span>
                    <span class="rounded-lg bg-white/10 px-4 py-2"><i class="fas fa-truck-fast mr-2 text-amber-300"></i>Delivery coordinado</span>
                    <span class="rounded-lg bg-white/10 px-4 py-2"><i class="fas fa-qrcode mr-2 text-amber-300"></i>Pago QR</span>
                </div>
            </div>
            <img src="{{ asset('images/logo-licoreria.jpeg') }}"
                 alt="R&E Distribuidora de Bebidas"
                 class="mx-auto h-32 w-32 rounded-2xl border border-white/10 object-cover shadow-2xl sm:h-40 sm:w-40">
        </div>
    </section>

    <section class="rounded-xl border border-gray-100 bg-white p-4 shadow-lg">
        <div class="grid gap-3 md:grid-cols-[1fr_auto] md:items-center">
            <div class="relative">
                <i class="fas fa-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text"
                       id="searchInput"
                       value="{{ request('search') }}"
                       placeholder="Buscar bebidas..."
                       class="w-full rounded-lg border px-4 py-3 pl-10 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100">
            </div>

            <div class="grid grid-cols-1 gap-3 sm:grid-cols-[1fr_auto]">
                <select id="filterCategoria"
                        class="rounded-lg border px-4 py-3 outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100">
                    <option value="">Todas las categorias</option>
                    <option value="bebida" {{ request('tipo') === 'bebida' ? 'selected' : '' }}>Bebidas alcoholicas</option>
                    <option value="soda" {{ request('tipo') === 'soda' ? 'selected' : '' }}>Refrescos</option>
                    <option value="papas" {{ request('tipo') === 'papas' ? 'selected' : '' }}>Snacks</option>
                    <option value="hielos" {{ request('tipo') === 'hielos' ? 'selected' : '' }}>Hielos</option>
                    <option value="cigarros" {{ request('tipo') === 'cigarros' ? 'selected' : '' }}>Cigarros</option>
                    <option value="otros" {{ request('tipo') === 'otros' ? 'selected' : '' }}>Otros</option>
                </select>

                <button onclick="aplicarFiltros()" class="rounded-lg bg-red-700 px-5 py-3 font-black text-white hover:bg-red-800">
                    <i class="fas fa-filter mr-2"></i>Filtrar
                </button>
            </div>
        </div>

        <div class="mt-4 flex gap-2 overflow-x-auto pb-2">
            @foreach([
                'bebida' => ['Bebidas', 'wine-glass-alt'],
                'soda' => ['Refrescos', 'bottle-water'],
                'papas' => ['Snacks', 'cookie-bite'],
                'hielos' => ['Hielos', 'snowflake'],
                'cigarros' => ['Cigarros', 'smoking'],
                'otros' => ['Otros', 'glass-whiskey'],
            ] as $tipo => [$label, $icon])
                <button onclick="filtrarPorTipo('{{ $tipo }}')"
                        class="flex shrink-0 items-center rounded-lg border border-gray-200 px-4 py-2 text-sm font-bold text-gray-800 hover:border-red-200 hover:bg-red-50 hover:text-red-800">
                    <i class="fas fa-{{ $icon }} mr-2 text-red-700"></i>{{ $label }}
                </button>
            @endforeach
        </div>
    </section>

    <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($productos as $producto)
            <article class="product-card overflow-hidden rounded-xl border border-gray-100 bg-white shadow-lg">
                <div class="relative h-48 bg-[#11151d]">
                    @if($producto->cantidad == 0)
                        <span class="absolute right-3 top-3 z-10 rounded-full bg-red-600 px-3 py-1 text-xs font-black text-white">AGOTADO</span>
                    @elseif($producto->cantidad < 10)
                        <span class="absolute right-3 top-3 z-10 rounded-full bg-amber-400 px-3 py-1 text-xs font-black text-gray-950">ULTIMAS {{ $producto->cantidad }}</span>
                    @endif

                    @if($producto->imagen)
                        <img src="{{ asset('storage/' . $producto->imagen) }}"
                             alt="{{ $producto->nombre }}"
                             class="h-full w-full object-cover transition duration-500 hover:scale-105">
                    @else
                        <div class="flex h-full w-full items-center justify-center">
                            <i class="fas fa-wine-bottle text-6xl text-amber-300/70"></i>
                        </div>
                    @endif

                    <button onclick="addToCart({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->precio }})"
                            class="absolute bottom-3 right-3 flex h-11 w-11 items-center justify-center rounded-full bg-red-700 text-white shadow-lg transition hover:bg-red-800 disabled:cursor-not-allowed disabled:opacity-50"
                            {{ $producto->cantidad == 0 ? 'disabled' : '' }}
                            aria-label="Agregar al carrito">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </div>

                <div class="flex min-h-64 flex-col p-4">
                    <span class="mb-3 w-fit rounded-full bg-amber-100 px-3 py-1 text-xs font-black text-amber-900">
                        {{ ucfirst($producto->tipo) }}
                    </span>

                    <h3 class="mb-2 truncate text-lg font-black text-gray-950">{{ $producto->nombre }}</h3>

                    @if($producto->descripcion)
                        <p class="line-clamp-2 text-sm leading-6 text-gray-600">{{ $producto->descripcion }}</p>
                    @endif

                    <div class="mt-auto pt-4">
                        <p class="text-2xl font-black text-red-700">S/ {{ number_format($producto->precio, 2) }}</p>
                        @if($producto->cantidad > 0)
                            <p class="mt-1 text-xs font-semibold text-gray-500">Stock: {{ $producto->cantidad }} unidades</p>
                        @else
                            <p class="mt-1 text-xs font-semibold text-red-600">Sin stock disponible</p>
                        @endif

                        <div class="mt-4 grid grid-cols-[auto_1fr] gap-2">
                            <a href="{{ route('productos.show', $producto) }}"
                               class="flex items-center justify-center rounded-lg border px-3 py-2 text-gray-700 hover:bg-gray-50"
                               aria-label="Ver producto">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="addToCart({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->precio }})"
                                    class="rounded-lg bg-red-700 px-4 py-2 font-black text-white hover:bg-red-800 disabled:cursor-not-allowed disabled:opacity-50"
                                    {{ $producto->cantidad == 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cart-plus mr-2"></i>Agregar
                            </button>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full rounded-xl border border-gray-100 bg-white p-10 text-center shadow-lg">
                <i class="fas fa-search mb-4 text-5xl text-gray-300"></i>
                <h3 class="mb-2 text-xl font-black text-gray-950">No se encontraron productos</h3>
                <p class="mb-6 text-gray-600">Intenta con otros filtros de busqueda.</p>
                <button onclick="limpiarFiltros()" class="rounded-lg bg-red-700 px-6 py-3 font-black text-white hover:bg-red-800">
                    Limpiar filtros
                </button>
            </div>
        @endforelse
    </section>

    @if($productos->hasPages())
        <div class="rounded-xl border border-gray-100 bg-white p-4 shadow-lg">
            {{ $productos->links('components.pagination') }}
        </div>
    @endif

    <section class="grid gap-4 rounded-xl border border-gray-100 bg-white p-5 shadow-lg md:grid-cols-3">
        <div class="rounded-lg bg-red-50 p-5 text-center">
            <i class="fas fa-shield-alt mb-3 text-3xl text-red-700"></i>
            <h4 class="font-black text-gray-950">Compra segura</h4>
            <p class="mt-2 text-sm text-gray-600">Productos registrados y pagos verificados.</p>
        </div>
        <div class="rounded-lg bg-amber-50 p-5 text-center">
            <i class="fas fa-clock mb-3 text-3xl text-amber-700"></i>
            <h4 class="font-black text-gray-950">Entrega rapida</h4>
            <p class="mt-2 text-sm text-gray-600">Pedidos coordinados en 30-45 minutos.</p>
        </div>
        <div class="rounded-lg bg-gray-50 p-5 text-center">
            <i class="fas fa-headset mb-3 text-3xl text-gray-800"></i>
            <h4 class="font-black text-gray-950">Soporte directo</h4>
            <p class="mt-2 text-sm text-gray-600">Atencion por telefono para tus pedidos.</p>
        </div>
    </section>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush

@push('scripts')
<script>
    function aplicarFiltros() {
        const search = document.getElementById('searchInput').value;
        const categoria = document.getElementById('filterCategoria').value;
        const params = [];

        if (search) params.push(`search=${encodeURIComponent(search)}`);
        if (categoria) params.push(`tipo=${categoria}`);

        window.location.href = '{{ route("productos.catalogo") }}' + (params.length ? `?${params.join('&')}` : '');
    }

    function filtrarPorTipo(tipo) {
        document.getElementById('filterCategoria').value = tipo;
        aplicarFiltros();
    }

    function limpiarFiltros() {
        document.getElementById('searchInput').value = '';
        document.getElementById('filterCategoria').value = '';
        aplicarFiltros();
    }

    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') aplicarFiltros();
    });
</script>
@endpush
@endsection
