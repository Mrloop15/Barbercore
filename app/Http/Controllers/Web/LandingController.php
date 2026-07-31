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

        $servicios = Servicio::where('id_barberia', $barberia?->id_barberia)
            ->where('activo', 1)
            ->where('mostrar_landing', 1)
            ->orderBy('precio')
            ->get();

        $telefonoPlano = preg_replace('/\D/', '', $barberia->telefono ?? '523322284564');

        return view('landing', [
            'barberia' => $barberia,
            'servicios' => $servicios,
            'telefonoWhatsapp' => $telefonoPlano,
        ]);
    }
}
