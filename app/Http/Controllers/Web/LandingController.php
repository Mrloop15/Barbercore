<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Barberia;
use App\Models\HorarioAtencion;
use App\Models\PreguntaFrecuente;
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

        $horarios = HorarioAtencion::where('id_barberia', $barberia?->id_barberia)
            ->orderBy('dia_semana')
            ->get();

        $preguntasFrecuentes = PreguntaFrecuente::where('id_barberia', $barberia?->id_barberia)
            ->where('activo', true)
            ->orderBy('orden')
            ->orderBy('id_pregunta')
            ->get();

        $telefonoPlano = preg_replace('/\D/', '', $barberia->telefono ?? '523322284564');
        $googleMapsUrl = $barberia?->google_maps_url;

        if (! $googleMapsUrl && $barberia?->direccion) {
            $googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($barberia->direccion);
        }

        return view('landing', [
            'barberia' => $barberia,
            'servicios' => $servicios,
            'telefonoWhatsapp' => $telefonoPlano,
            'googleMapsUrl' => $googleMapsUrl,
            'horarios' => $horarios,
            'preguntasFrecuentes' => $preguntasFrecuentes,
            'diasSemana' => [
                0 => 'Lunes',
                1 => 'Martes',
                2 => 'Miércoles',
                3 => 'Jueves',
                4 => 'Viernes',
                5 => 'Sábado',
                6 => 'Domingo',
            ],
        ]);
    }
}
