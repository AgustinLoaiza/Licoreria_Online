<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function ventasDiarias()
    {
        return view('reportes.ventas_diarias');
    }

    public function inventario()
    {
        return view('reportes.inventario');
    }

    public function estadisticas()
    {
        return view('reportes.estadisticas');
    }
}
