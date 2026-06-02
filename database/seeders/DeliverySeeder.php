<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Delivery;
use App\Models\Usuario;

class DeliverySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Usuario::query()->where('rol', 'repartidor')->get()->each(function (Usuario $repartidor): void {
            Delivery::updateOrCreate([
                'usuario_id' => $repartidor->id,
            ], [
                'estado' => 'disponible',
            ]);
        });
    }
}
