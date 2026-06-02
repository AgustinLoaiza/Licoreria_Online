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
        $repartidor = Usuario::where('email', 'repartidor1@example.com')->first();

        if (!$repartidor) {
            return;
        }

        Delivery::updateOrCreate([
            'usuario_id' => $repartidor->id,
        ], [
            'estado' => 'disponible',
        ]);
    }
}
