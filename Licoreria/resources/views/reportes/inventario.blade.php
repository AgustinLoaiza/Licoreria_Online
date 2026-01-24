@extends('layouts.admin')

@section('title', 'Inventario - Reportes')
@section('page-title', 'Inventario')
@section('page-description', 'Estado actual del inventario de productos')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">
        <h2 class="text-xl font-bold mb-6">Inventario de Productos</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-600">Producto</th>
                        <th class="px-4 py-2 text-left text-gray-600">Tipo</th>
                        <th class="px-4 py-2 text-left text-gray-600">Cantidad</th>
                        <th class="px-4 py-2 text-left text-gray-600">Stock Bajo</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ejemplo de datos -->
                    <tr>
                        <td class="px-4 py-2">Whisky Johnnie Walker</td>
                        <td class="px-4 py-2">Bebida</td>
                        <td class="px-4 py-2">8</td>
                        <td class="px-4 py-2">
                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs">¡Reabastecer!</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">Coca Cola 2L</td>
                        <td class="px-4 py-2">Soda</td>
                        <td class="px-4 py-2">25</td>
                        <td class="px-4 py-2"></td>
                    </tr>
                    <!-- ... -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
