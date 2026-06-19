<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Ventas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Lista de Ventas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Pedido</th>
                <th>Metodo</th>
                <th>Monto</th>
                <th>Estado</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventas as $venta)
            <tr>
                <td>{{ $venta->id }}</td>
                <td>{{ $venta->pedido && $venta->pedido->usuario ? $venta->pedido->usuario->nombre : '-' }}</td>
                <td>{{ $venta->pedido ? $venta->pedido->id : '-' }}</td>
                <td>{{ ucfirst($venta->metodo_pago) }}</td>
                <td>
                    Bs {{ $venta->pedido ? number_format($venta->pedido->monto_total, 2) : '0.00' }}
                </td>
                <td>{{ ucfirst($venta->estado) }}</td>
                <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
