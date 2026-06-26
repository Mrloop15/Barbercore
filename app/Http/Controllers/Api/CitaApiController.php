<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Servicio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\HistorialServicio;
use App\Models\MovimientoPunto;
use Illuminate\Support\Facades\DB;

class CitaApiController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $fecha = $request->input('fecha');

        $citas = Cita::with(['cliente', 'servicio', 'barbero'])
            ->where('id_barberia', $idBarberia)
            ->when($fecha, function ($query) use ($fecha) {
                $query->whereDate('fecha', $fecha);
            })
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $citas,
        ]);
    }

    public function store(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_servicio' => 'required|exists:servicios,id_servicio',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
        ]);

        $cliente = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->where('id_cliente', $request->id_cliente)
            ->firstOrFail();

        $servicio = Servicio::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->where('id_servicio', $request->id_servicio)
            ->firstOrFail();

        $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

        $horaInicioSql = $horaInicio->format('H:i:s');
        $horaFinSql = $horaFin->format('H:i:s');

        $existeEmpalme = Cita::where('id_barberia', $idBarberia)
            ->whereDate('fecha', $request->fecha)
            ->where('estado', '!=', 'cancelada')
            ->where(function ($query) use ($horaInicioSql, $horaFinSql) {
                $query->where('hora_inicio', '<', $horaFinSql)
                    ->where('hora_fin', '>', $horaInicioSql);
            })
            ->exists();

        if ($existeEmpalme) {
            return response()->json([
                'ok' => false,
                'message' => 'Ya existe una cita en ese horario.',
            ], 422);
        }

        $cita = Cita::create([
            'id_barberia' => $idBarberia,
            'id_cliente' => $cliente->id_cliente,
            'id_servicio' => $servicio->id_servicio,
            'id_barbero' => $usuario->id_usuario,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicioSql,
            'hora_fin' => $horaFinSql,
            'precio' => $servicio->precio,
            'estado' => 'pendiente',
            'observaciones' => $request->observaciones,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Cita registrada correctamente.',
            'data' => $cita,
        ], 201);
    }

    public function cancelar(Request $request, string $id)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cita = Cita::where('id_barberia', $idBarberia)
            ->where('id_cita', $id)
            ->firstOrFail();

        if ($cita->estado === 'completada') {
            return response()->json([
                'ok' => false,
                'message' => 'No se puede cancelar una cita completada.',
            ], 422);
        }

        $cita->update([
            'estado' => 'cancelada',
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Cita cancelada correctamente.',
        ]);
    }

    public function completar(Request $request, string $id)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $cita = Cita::with(['cliente', 'servicio'])
        ->where('id_barberia', $idBarberia)
        ->where('id_cita', $id)
        ->firstOrFail();

    if ($cita->estado !== 'pendiente') {
        return response()->json([
            'ok' => false,
            'message' => 'Solo se pueden completar citas pendientes.',
        ], 422);
    }

    DB::transaction(function () use ($cita, $idBarberia) {
        $puntosGanados = 10;

        $cita->update([
            'estado' => 'completada',
        ]);

        $cita->cliente->update([
            'ultima_visita' => $cita->fecha,
            'puntos' => DB::raw('puntos + ' . $puntosGanados),
        ]);

        HistorialServicio::create([
            'id_barberia' => $idBarberia,
            'id_cliente' => $cita->id_cliente,
            'id_cita' => $cita->id_cita,
            'id_servicio' => $cita->id_servicio,
            'precio' => $cita->precio,
            'fecha_servicio' => $cita->fecha,
            'observaciones' => $cita->observaciones,
        ]);

        MovimientoPunto::create([
            'id_barberia' => $idBarberia,
            'id_cliente' => $cita->id_cliente,
            'tipo' => 'suma',
            'puntos' => $puntosGanados,
            'motivo' => 'Puntos generados por cita completada',
            'referencia' => 'cita:' . $cita->id_cita,
            'created_at' => now(),
        ]);
    });

    $cita->refresh();

    return response()->json([
        'ok' => true,
        'message' => 'Cita completada correctamente. Se actualizó historial, última visita y puntos del cliente.',
        'data' => $cita,
    ]);
}
}