<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    /** @use HasFactory<\Database\Factories\ProductoFactory> */
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre',
        'imagen',
        'tipo',
        'precio',
        'cantidad',
        'descripcion',
        'disponible',
    ];

    public function detallePedidos()
    {
        return $this->hasMany(Detalle_pedido::class, 'producto_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    public function scopeBebidas($query)
    {
        return $query->where('tipo', 'bebida');
    }
}
