<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Producto::query()->count() > 0) {
            return;
        }

        Producto::create([
            'nombre' => 'Whisky Old Parr',
            'imagen' => null,
            'tipo' => 'bebida',
            'precio' => 280.00,
            'cantidad' => 36,
            'descripcion' => 'Whisky escocés blended reconocido mundialmente.',
        ]);

        Producto::create([
            'nombre' => 'Whisky Johnnie Walker Red Label',
            'imagen' => null,
            'tipo' => 'bebida',
            'precio' => 150.00,
            'cantidad' => 60,
            'descripcion' => 'Whisky escocés blended reconocido mundialmente.',
        ]);

        Producto::create([
            'nombre' => 'Whisky Johnnie Walker Black Label',
            'imagen' => null,
            'tipo' => 'bebida',
            'precio' => 300.00,
            'cantidad' => 48,
            'descripcion' => 'Whisky escocés blended reconocido mundialmente.',
        ]);

        Producto::create([
            'nombre' => 'Tequila José Cuervo',
            'imagen' => null,
            'tipo' => 'bebida',
            'precio' => 90.00,
            'cantidad' => 72,
            'descripcion' => 'Tequila mexicano tradicional de alta calidad.',
        ]);

        Producto::create([
            'nombre' => 'Coca-Cola',
            'imagen' => null,
            'tipo' => 'soda',
            'precio' => 20.00,
            'cantidad' => 120,
            'descripcion' => 'Bebida gaseosa refrescante y popular.',
        ]);

        Producto::create([
            'nombre' => 'Sprite',
            'imagen' => null,
            'tipo' => 'soda',
            'precio' => 18.00,
            'cantidad' => 100,
            'descripcion' => 'Bebida gaseosa refrescante y popular.',
        ]);

        Producto::create([
            'nombre' => 'Papas Fritas',
            'imagen' => null,
            'tipo' => 'papas',
            'precio' => 6.00,
            'cantidad' => 200,
            'descripcion' => 'Papas fritas crujientes y sabrosas.',
        ]);

        Producto::create([
            'nombre' => 'Cigarros Marlboro',
            'imagen' => null,
            'tipo' => 'cigarros',
            'precio' => 50.00,
            'cantidad' => 150,
            'descripcion' => 'Cigarros de alta calidad y sabor distintivo.',
        ]);

        Producto::create([
            'nombre' => 'Hielos Bolsa 2kg',
            'imagen' => null,
            'tipo' => 'hielos',
            'precio' => 15.00,
            'cantidad' => 80,
            'descripcion' => 'Bolsa de hielo para mantener tus bebidas frías.',
        ]);

        Producto::create([
            'nombre' => 'Agua Tonica',
            'imagen' => null,
            'tipo' => 'otros',
            'precio' => 10.00,
            'cantidad' => 90,
            'descripcion' => 'Agua tónica para acompañar tus bebidas.',
        ]);
    }
}
