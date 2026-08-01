<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Barberia;
use App\Models\Cliente;
use App\Models\HorarioAtencion;
use App\Models\PreguntaFrecuente;
use App\Models\Recompensa;
use App\Models\Servicio;
use Illuminate\Http\Request;

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

    public function consultarRecompensas(Request $request)
    {
        $request->validate([
            'telefono' => 'required|string|max:20',
        ]);

        $barberia = Barberia::first();
        $telefonoBuscado = preg_replace('/\D/', '', $request->input('telefono'));

        $cliente = $telefonoBuscado === '' ? null : Cliente::query()
            ->where('id_barberia', $barberia?->id_barberia)
            ->where('activo', 1)
            ->whereRaw("REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(telefono, ' ', ''), '-', ''), '(', ''), ')', ''), '+', '') = ?", [$telefonoBuscado])
            ->first(['id_cliente', 'puntos']);

        if (! $cliente) {
            return response()->json([
                'encontrado' => false,
                'mensaje' => 'No encontramos un cliente registrado con ese número de teléfono.',
            ]);
        }

        $recompensas = Recompensa::where('id_barberia', $barberia?->id_barberia)
            ->where('activo', 1)
            ->orderBy('puntos_requeridos')
            ->get()
            ->map(fn ($recompensa) => [
                'nombre' => $recompensa->nombre,
                'descripcion' => $recompensa->descripcion,
                'puntos_requeridos' => $recompensa->puntos_requeridos,
                'disponible' => $cliente->puntos >= $recompensa->puntos_requeridos,
            ]);

        return response()->json([
            'encontrado' => true,
            'cliente_verificado' => true,
            'recompensas' => $recompensas,
        ]);
    }
}
