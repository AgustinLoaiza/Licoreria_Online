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
        Usuario::updateOrCreate(['email' => 'admin@example.com'], [
            'nombre' => 'Administrador',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'administrador',
        ]);

        Usuario::updateOrCreate(['email' => 'cliente1@example.com'], [
            'nombre' => 'Cliente1',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'cliente',
        ]);

        Usuario::updateOrCreate(['email' => 'cliente2@example.com'], [
            'nombre' => 'Cliente2',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'cliente',
        ]);

        Usuario::updateOrCreate(['email' => 'cliente3@example.com'], [
            'nombre' => 'Cliente3',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'cliente',
        ]);

        Usuario::updateOrCreate(['email' => 'repartidor1@example.com'], [
            'nombre' => 'Repartidor1',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'repartidor',
        ]);

        Usuario::updateOrCreate(['email' => 'repartidor2@example.com'], [
            'nombre' => 'Repartidor2',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'repartidor',
        ]);

        Usuario::updateOrCreate(['email' => 'repartidor3@example.com'], [
            'nombre' => 'Repartidor3',
            'password' => bcrypt('password'),
            'telefono' => '1234567890',
            'rol' => 'repartidor',
        ]);
    }
}
