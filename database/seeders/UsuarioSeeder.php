<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'administrador',
        ]);

        Usuario::create([
            'nombre' => 'Cliente1',
            'email' => 'cliente1@example.com',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'cliente',
        ]);

        Usuario::create([
            'nombre' => 'Cliente2',
            'email' => 'cliente2@example.com',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'cliente',
        ]);

        Usuario::create([
            'nombre' => 'Cliente3',
            'email' => 'cliente3@example.com',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'cliente',
        ]);

        Usuario::create([
            'nombre' => 'Repartidor1',
            'email' => 'repartidor1@example.com',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'repartidor',
        ]);

        Usuario::create([
            'nombre' => 'Repartidor2',
            'email' => 'repartidor2@example.com',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'repartidor',
        ]);

        Usuario::create([
            'nombre' => 'Repartidor3',
            'email' => 'repartidor3@example.com',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'repartidor',
        ]);
    }
}
