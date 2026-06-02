<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    /** @use HasFactory<\Database\Factories\PedidoFactory> */
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'usuario_id',
        'delivery_id',
        'nombre_pedido',
        'direccion',
        'fecha_pedido',
        'estado',
        'monto_total',
    ];

    protected $casts = [
        'fecha_pedido' => 'datetime',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class, 'delivery_id');
    }

    public function detalles()
    {
        return $this->hasMany(Detalle_pedido::class, 'pedido_id');
    }

    public function venta()
    {
        return $this->hasOne(Venta::class, 'pedido_id');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function calcularTotal()
    {
        return $this->detallePedidos->sum('subtotal');
    }

}
