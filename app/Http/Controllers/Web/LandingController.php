<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Barberia;
use App\Models\Servicio;

class LandingController extends Controller
{
    public function index()
    {
        $barberia = Barberia::first();

        $servicios = Servicio::where('activo', 1)
            ->orderBy('precio')
            ->take(4)
            ->get();

        $telefonoPlano = preg_replace('/\D/', '', $barberia->telefono ?? '523322284564');

        return view('landing', [
            'barberia' => $barberia,
            'servicios' => $servicios,
            'telefonoWhatsapp' => $telefonoPlano,
        ]);
    }
}