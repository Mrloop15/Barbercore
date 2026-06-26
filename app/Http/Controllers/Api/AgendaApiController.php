<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AgendaApiController extends Controller
{
    public function dia(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $fecha = $request->query('fecha')
            ? Carbon::parse($request->query('fecha'))->toDateString()
            : now()->toDateString();

        $citas = Cita::with(['cliente', 'servicio'])
            ->where('id_barberia', $idBarberia)
            ->whereDate('fecha', $fecha)
            ->orderBy('hora_inicio')
            ->get();

        return response()->json([
            'ok' => true,
            'tipo' => 'dia',
            'fecha' => $fecha,
            'total' => $citas->count(),
            'data' => $citas,
        ]);
    }

    public function semana(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $fechaBase = $request->query('fecha')
            ? Carbon::parse($request->query('fecha'))
            : now();

        $inicioSemana = $fechaBase->copy()->startOfWeek()->toDateString();
        $finSemana = $fechaBase->copy()->endOfWeek()->toDateString();

        $citas = Cita::with(['cliente', 'servicio'])
            ->where('id_barberia', $idBarberia)
            ->whereBetween('fecha', [$inicioSemana, $finSemana])
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();

        return response()->json([
            'ok' => true,
            'tipo' => 'semana',
            'inicio' => $inicioSemana,
            'fin' => $finSemana,
            'total' => $citas->count(),
            'data' => $citas,
        ]);
    }

    public function mes(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $fechaBase = $request->query('fecha')
            ? Carbon::parse($request->query('fecha'))
            : now();

        $inicioMes = $fechaBase->copy()->startOfMonth()->toDateString();
        $finMes = $fechaBase->copy()->endOfMonth()->toDateString();

        $citas = Cita::with(['cliente', 'servicio'])
            ->where('id_barberia', $idBarberia)
            ->whereBetween('fecha', [$inicioMes, $finMes])
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();

        return response()->json([
            'ok' => true,
            'tipo' => 'mes',
            'inicio' => $inicioMes,
            'fin' => $finMes,
            'total' => $citas->count(),
            'data' => $citas,
        ]);
    }
}