@extends('layouts.cliente')

@section('title', $producto->nombre . ' - Detalle')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-xl shadow p-6 mt-8">
    <div class="flex flex-col md:flex-row items-center">
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
            @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-full rounded-lg shadow">
            @else
                <div class="w-full h-48 flex items-center justify-center bg-gray-100 rounded-lg">
                    <i class="fas fa-wine-bottle text-gray-300 text-6xl"></i>
                </div>
            @endif
        </div>
        <div class="w-full md:w-1/2 md:pl-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $producto->nombre }}</h2>
            <p class="text-gray-600 mb-4">{{ $producto->descripcion }}</p>
            <p class="text-lg font-bold text-red-600 mb-2">Bs {{ number_format($producto->precio, 2) }}</p>
            <p class="text-sm text-gray-500 mb-2">Stock: {{ $producto->cantidad }} unidades</p>
            <div class="flex space-x-3 mt-6">
                <button onclick="addToCart({{ $producto->id }}, '{{ addslashes($producto->nombre) }}', {{ $producto->precio }})"
                        class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 flex items-center"
                        {{ $producto->cantidad == 0 ? 'disabled' : '' }}>
                    <i class="fas fa-cart-plus mr-2"></i> Agregar al carrito
                </button>
                <a href="{{ route('productos.catalogo') }}"
                   class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Volver al catálogo
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
