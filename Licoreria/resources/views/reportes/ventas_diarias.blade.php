@extends('layouts.admin')

@section('title', 'Ventas Diarias - Reportes')
@section('page-title', 'Ventas Diarias')
@section('page-description', 'Resumen de ventas por día')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">
        <h2 class="text-xl font-bold mb-6">Ventas Diarias</h2>
        <div class="mb-6">
            <label for="fecha" class="block text-sm font-medium text-gray-700 mb-2">Selecciona una fecha:</label>
            <input type="date" id="fecha" name="fecha"
                   class="border rounded-lg px-4 py-2 w-full max-w-xs focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-gray-600">Fecha</th>
                        <th class="px-4 py-2 text-left text-gray-600">Total Ventas</th>
                        <th class="px-4 py-2 text-left text-gray-600">Cantidad de Pedidos</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Ejemplo de datos -->
                    <tr>
                        <td class="px-4 py-2">23/01/2026</td>
                        <td class="px-4 py-2">S/ 1,200.00</td>
                        <td class="px-4 py-2">15</td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">22/01/2026</td>
                        <td class="px-4 py-2">S/ 950.00</td>
                        <td class="px-4 py-2">10</td>
                    </tr>
                    <!-- ... -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
