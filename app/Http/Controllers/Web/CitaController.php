<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\HistorialServicio;
use App\Models\MovimientoPunto;
use App\Models\Servicio;
use App\Models\Usuario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CitaController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $fecha = $request->input('fecha', Carbon::today()->toDateString());
        $estado = $request->input('estado');

        $citas = Cita::with(['cliente', 'servicio', 'barbero'])
            ->where('id_barberia', $idBarberia)
            ->when($fecha, function ($query) use ($fecha) {
                $query->whereDate('fecha', $fecha);
            })
            ->when($estado, function ($query) use ($estado) {
                $query->where('estado', $estado);
            })
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->paginate(10)
            ->withQueryString();

        return view('citas.index', compact('citas', 'fecha', 'estado'));
    }

    public function create()
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $clientes = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $servicios = Servicio::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $barberos = Usuario::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereIn('rol', ['admin', 'barbero'])
            ->orderBy('nombre')
            ->get();

        return view('citas.create', compact('clientes', 'servicios', 'barberos'));
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_servicio' => 'required|exists:servicios,id_servicio',
            'id_barbero' => 'required|exists:usuarios,id_usuario',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'observaciones' => 'nullable|string',
        ], [
            'id_cliente.required' => 'Selecciona un cliente.',
            'id_servicio.required' => 'Selecciona un servicio.',
            'id_barbero.required' => 'Selecciona un barbero.',
            'fecha.required' => 'Selecciona la fecha de la cita.',
            'hora_inicio.required' => 'Selecciona la hora de inicio.',
        ]);

        $servicio = Servicio::where('id_barberia', $idBarberia)
            ->where('id_servicio', $request->id_servicio)
            ->where('activo', 1)
            ->firstOrFail();

        $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

        $horaInicioSql = $horaInicio->format('H:i:s');
        $horaFinSql = $horaFin->format('H:i:s');

        $existeEmpalme = Cita::where('id_barberia', $idBarberia)
            ->where('id_barbero', $request->id_barbero)
            ->whereDate('fecha', $request->fecha)
            ->where('estado', '!=', 'cancelada')
            ->where(function ($query) use ($horaInicioSql, $horaFinSql) {
                $query->where('hora_inicio', '<', $horaFinSql)
                    ->where('hora_fin', '>', $horaInicioSql);
            })
            ->exists();

        if ($existeEmpalme) {
            return back()
                ->withInput()
                ->with('error', 'Ya existe una cita en ese horario para el barbero seleccionado.');
        }

        Cita::create([
            'id_barberia' => $idBarberia,
            'id_cliente' => $request->id_cliente,
            'id_servicio' => $request->id_servicio,
            'id_barbero' => $request->id_barbero,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicioSql,
            'hora_fin' => $horaFinSql,
            'precio' => $servicio->precio,
            'estado' => 'pendiente',
            'observaciones' => $request->observaciones,
        ]);

        return redirect()
            ->route('citas.index')
            ->with('success', 'Cita registrada correctamente.');
    }

    public function edit(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cita = Cita::where('id_barberia', $idBarberia)
            ->where('id_cita', $id)
            ->firstOrFail();

        $clientes = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $servicios = Servicio::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $barberos = Usuario::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereIn('rol', ['admin', 'barbero'])
            ->orderBy('nombre')
            ->get();

        return view('citas.edit', compact('cita', 'clientes', 'servicios', 'barberos'));
    }

    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cita = Cita::where('id_barberia', $idBarberia)
            ->where('id_cita', $id)
            ->firstOrFail();

        if ($cita->estado !== 'pendiente') {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Solo se pueden editar citas pendientes.');
        }

        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_servicio' => 'required|exists:servicios,id_servicio',
            'id_barbero' => 'required|exists:usuarios,id_usuario',
            'fecha' => 'required|date',
            'hora_inicio' => 'required',
            'observaciones' => 'nullable|string',
        ]);

        $servicio = Servicio::where('id_barberia', $idBarberia)
            ->where('id_servicio', $request->id_servicio)
            ->where('activo', 1)
            ->firstOrFail();

        $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

        $horaInicioSql = $horaInicio->format('H:i:s');
        $horaFinSql = $horaFin->format('H:i:s');

        $existeEmpalme = Cita::where('id_barberia', $idBarberia)
            ->where('id_barbero', $request->id_barbero)
            ->whereDate('fecha', $request->fecha)
            ->where('estado', '!=', 'cancelada')
            ->where('id_cita', '!=', $cita->id_cita)
            ->where(function ($query) use ($horaInicioSql, $horaFinSql) {
                $query->where('hora_inicio', '<', $horaFinSql)
                    ->where('hora_fin', '>', $horaInicioSql);
            })
            ->exists();

        if ($existeEmpalme) {
            return back()
                ->withInput()
                ->with('error', 'Ya existe una cita en ese horario para el barbero seleccionado.');
        }

        $cita->update([
            'id_cliente' => $request->id_cliente,
            'id_servicio' => $request->id_servicio,
            'id_barbero' => $request->id_barbero,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicioSql,
            'hora_fin' => $horaFinSql,
            'precio' => $servicio->precio,
            'observaciones' => $request->observaciones,
        ]);

        return redirect()
            ->route('citas.index')
            ->with('success', 'Cita actualizada correctamente.');
    }

    public function cancelar(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cita = Cita::where('id_barberia', $idBarberia)
            ->where('id_cita', $id)
            ->firstOrFail();

        if ($cita->estado === 'completada') {
            return redirect()
                ->route('citas.index')
                ->with('error', 'No se puede cancelar una cita completada.');
        }

        $cita->update([
            'estado' => 'cancelada',
        ]);

        return redirect()
            ->route('citas.index')
            ->with('success', 'Cita cancelada correctamente.');
    }

    public function completar(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cita = Cita::with(['cliente', 'servicio'])
            ->where('id_barberia', $idBarberia)
            ->where('id_cita', $id)
            ->firstOrFail();

        if ($cita->estado !== 'pendiente') {
            return redirect()
                ->route('citas.index')
                ->with('error', 'Solo se pueden completar citas pendientes.');
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

        return redirect()
            ->route('citas.index')
            ->with('success', 'Cita completada correctamente. Se actualizó historial, última visita y puntos del cliente.');
    }
}