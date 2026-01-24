@extends('layouts.admin')

@section('title', 'Estadísticas - Reportes')
@section('page-title', 'Estadísticas')
@section('page-description', 'Gráficos y tendencias del negocio')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow p-8">
        <h2 class="text-xl font-bold mb-6">Estadísticas del Negocio</h2>
        <div class="mb-8">
            <canvas id="ventasChart" height="120"></canvas>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 rounded-lg p-6 text-center">
                <p class="text-lg font-semibold text-blue-700 mb-2">Total Ventas</p>
                <p class="text-3xl font-bold text-blue-900">S/ 12,500.00</p>
            </div>
            <div class="bg-green-50 rounded-lg p-6 text-center">
                <p class="text-lg font-semibold text-green-700 mb-2">Pedidos Entregados</p>
                <p class="text-3xl font-bold text-green-900">120</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('ventasChart').getContext('2d');
    const ventasChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul'],
            datasets: [{
                label: 'Ventas Mensuales',
                data: [1200, 1500, 1100, 1800, 2200, 2100, 1600],
                backgroundColor: 'rgba(59,130,246,0.1)',
                borderColor: 'rgba(59,130,246,1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endpush
@endsection
