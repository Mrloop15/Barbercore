<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $vista = $request->input('vista', 'dia');

        if (!in_array($vista, ['dia', 'semana', 'mes'])) {
            $vista = 'dia';
        }

        $fechaBase = Carbon::parse($request->input('fecha', Carbon::today()->toDateString()));

        if ($vista === 'dia') {
            $inicio = $fechaBase->copy()->startOfDay();
            $fin = $fechaBase->copy()->endOfDay();
            $tituloPeriodo = 'Agenda del día ' . $fechaBase->format('d/m/Y');
        } elseif ($vista === 'semana') {
            $inicio = $fechaBase->copy()->startOfWeek(Carbon::MONDAY);
            $fin = $fechaBase->copy()->endOfWeek(Carbon::SUNDAY);
            $tituloPeriodo = 'Agenda semanal del ' . $inicio->format('d/m/Y') . ' al ' . $fin->format('d/m/Y');
        } else {
            $inicio = $fechaBase->copy()->startOfMonth();
            $fin = $fechaBase->copy()->endOfMonth();
            $tituloPeriodo = 'Agenda mensual de ' . $fechaBase->translatedFormat('F Y');
        }

        $citas = Cita::with(['cliente', 'servicio', 'barbero'])
            ->where('id_barberia', $idBarberia)
            ->whereBetween('fecha', [
                $inicio->toDateString(),
                $fin->toDateString()
            ])
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();

        $citasAgrupadas = $citas->groupBy('fecha');

        $totalCitas = $citas->count();
        $pendientes = $citas->where('estado', 'pendiente')->count();
        $completadas = $citas->where('estado', 'completada')->count();
        $canceladas = $citas->where('estado', 'cancelada')->count();

        $ingresosEstimados = $citas->where('estado', 'pendiente')->sum('precio');
        $ingresosGenerados = $citas->where('estado', 'completada')->sum('precio');

        return view('agenda.index', compact(
            'vista',
            'fechaBase',
            'tituloPeriodo',
            'citas',
            'citasAgrupadas',
            'totalCitas',
            'pendientes',
            'completadas',
            'canceladas',
            'ingresosEstimados',
            'ingresosGenerados'
        ));
    }
}