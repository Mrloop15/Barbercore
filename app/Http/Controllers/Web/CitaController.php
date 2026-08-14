<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\HistorialServicio;
use App\Models\HorarioAtencion;
use App\Models\MovimientoPunto;
use App\Models\Servicio;
use App\Models\Usuario;
use App\Support\BusinessClock;
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

        $request->validate([
            'periodo' => ['nullable', 'in:rango,hoy'],
            'desde' => ['nullable', 'date'],
            'hasta' => ['nullable', 'date'],
            'estado' => ['nullable', 'in:pendiente,completada,cancelada'],
        ]);

        $periodo = $request->input('periodo', 'rango');
        $periodo = in_array($periodo, ['rango', 'hoy'], true) ? $periodo : 'rango';
        $desde = $periodo === 'hoy'
            ? BusinessClock::today()
            : BusinessClock::localDate($request->input('desde', BusinessClock::today()->toDateString()));
        $hasta = $periodo === 'hoy'
            ? BusinessClock::today()
            : BusinessClock::localDate($request->input('hasta', BusinessClock::today()->addDays(14)->toDateString()));

        if ($hasta->lt($desde)) {
            return back()->withInput()->with('error', 'La fecha final no puede ser anterior a la fecha inicial.');
        }

        $estado = $request->input('estado');

        $citas = Cita::with(['cliente', 'servicio', 'barbero'])
            ->where('id_barberia', $idBarberia)
            ->whereBetween('fecha', [$desde->toDateString(), $hasta->toDateString()])
            ->when($estado, function ($query) use ($estado) {
                $query->where('estado', $estado);
            })
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->paginate(10)
            ->withQueryString();

        return view('citas.index', compact('citas', 'desde', 'hasta', 'periodo', 'estado'));
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

        $horarios = HorarioAtencion::where('id_barberia', $idBarberia)
            ->get()
            ->keyBy('dia_semana');

        $fechaInicial = BusinessClock::today();
        foreach (range(0, 13) as $diasAdelante) {
            $candidata = BusinessClock::today()->addDays($diasAdelante);
            $diaSemana = $candidata->dayOfWeekIso - 1;

            if ($horarios->get($diaSemana)?->abierto) {
                $fechaInicial = $candidata;
                break;
            }
        }

        return view('citas.create', compact('clientes', 'servicios', 'barberos', 'horarios', 'fechaInicial'));
    }

    public function disponibilidad(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $datos = $request->validate([
            'fecha' => ['required', 'date'],
            'id_barbero' => ['required', 'integer'],
            'id_servicio' => ['required', 'integer'],
        ]);

        $servicio = Servicio::where('id_barberia', $idBarberia)
            ->where('id_servicio', $datos['id_servicio'])
            ->where('activo', 1)
            ->firstOrFail();

        Usuario::where('id_barberia', $idBarberia)
            ->where('id_usuario', $datos['id_barbero'])
            ->where('activo', 1)
            ->whereIn('rol', ['admin', 'barbero'])
            ->firstOrFail();

        $fecha = BusinessClock::localDate($datos['fecha']);
        if ($fecha->lt(BusinessClock::today())) {
            return response()->json(['horarios' => [], 'message' => 'La fecha seleccionada ya pasó.'], 422);
        }
        $diaSemana = $fecha->dayOfWeekIso - 1;
        $horario = HorarioAtencion::where('id_barberia', $idBarberia)
            ->where('dia_semana', $diaSemana)
            ->first();

        if (! $horario?->abierto || ! $horario->hora_apertura || ! $horario->hora_cierre) {
            return response()->json([
                'horarios' => [],
                'message' => 'La barbería no brinda atención en el día seleccionado.',
            ]);
        }

        $inicioJornada = $fecha->setTimeFromTimeString($horario->hora_apertura);
        $finJornada = $fecha->setTimeFromTimeString($horario->hora_cierre);
        $ahora = BusinessClock::now();
        $citasOcupadas = Cita::where('id_barberia', $idBarberia)
            ->where('id_barbero', $datos['id_barbero'])
            ->whereDate('fecha', $fecha->toDateString())
            ->where('estado', '!=', 'cancelada')
            ->get(['hora_inicio', 'hora_fin']);

        $horariosDisponibles = [];
        for ($inicio = $inicioJornada->copy(); $inicio->copy()->addMinutes($servicio->duracion_minutos)->lte($finJornada); $inicio = $inicio->addMinutes(15)) {
            $fin = $inicio->copy()->addMinutes($servicio->duracion_minutos);

            if ($fecha->isToday() && $inicio->lte($ahora)) {
                continue;
            }

            $ocupado = $citasOcupadas->contains(function ($cita) use ($fecha, $inicio, $fin) {
                $inicioOcupado = $fecha->setTimeFromTimeString($cita->hora_inicio);
                $finOcupado = $fecha->setTimeFromTimeString($cita->hora_fin);

                return $inicio->lt($finOcupado) && $fin->gt($inicioOcupado);
            });

            if (! $ocupado) {
                $horariosDisponibles[] = $inicio->format('H:i');
            }
        }

        return response()->json([
            'horarios' => $horariosDisponibles,
            'apertura' => substr($horario->hora_apertura, 0, 5),
            'cierre' => substr($horario->hora_cierre, 0, 5),
            'message' => empty($horariosDisponibles)
                ? 'No quedan horarios disponibles para este barbero y servicio.'
                : null,
        ]);
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
            'hora_inicio' => 'required|date_format:H:i',
            'observaciones' => 'nullable|string',
        ], [
            'id_cliente.required' => 'Selecciona un cliente.',
            'id_servicio.required' => 'Selecciona un servicio.',
            'id_barbero.required' => 'Selecciona un barbero.',
            'fecha.required' => 'Selecciona la fecha de la cita.',
            'fecha.after_or_equal' => 'La fecha de la cita no puede estar en el pasado.',
            'hora_inicio.required' => 'Selecciona la hora de inicio.',
            'hora_inicio.date_format' => 'Selecciona una hora válida.',
        ]);

        $fechaLocal = BusinessClock::localDate($request->fecha);
        if ($fechaLocal->lt(BusinessClock::today())) {
            return back()->withInput()->with('error', 'La fecha de la cita no puede estar en el pasado.');
        }

        $servicio = Servicio::where('id_barberia', $idBarberia)
            ->where('id_servicio', $request->id_servicio)
            ->where('activo', 1)
            ->firstOrFail();

        $clienteValido = Cliente::where('id_barberia', $idBarberia)
            ->where('id_cliente', $request->id_cliente)
            ->where('activo', 1)
            ->exists();
        $barberoValido = Usuario::where('id_barberia', $idBarberia)
            ->where('id_usuario', $request->id_barbero)
            ->where('activo', 1)
            ->whereIn('rol', ['admin', 'barbero'])
            ->exists();

        if (! $clienteValido || ! $barberoValido) {
            return back()
                ->withInput()
                ->with('error', 'El cliente o barbero seleccionado no está disponible en esta barbería.');
        }

        $horaInicio = Carbon::createFromFormat('H:i', $request->hora_inicio);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

        if ($horaInicio->minute % 15 !== 0) {
            return back()
                ->withInput()
                ->with('error', 'Selecciona uno de los horarios disponibles en intervalos de 15 minutos.');
        }

        $inicioCita = $fechaLocal->setTimeFromTimeString($request->hora_inicio);
        if ($inicioCita->lte(BusinessClock::now())) {
            return back()
                ->withInput()
                ->with('error', 'La cita debe programarse en una fecha y hora futuras.');
        }

        $horaInicioSql = $horaInicio->format('H:i:s');
        $horaFinSql = $horaFin->format('H:i:s');

        $diaSemana = $fechaLocal->dayOfWeekIso - 1;
        $horario = HorarioAtencion::where('id_barberia', $idBarberia)
            ->where('dia_semana', $diaSemana)
            ->first();

        if (! $horario?->abierto || ! $horario->hora_apertura || ! $horario->hora_cierre) {
            return back()
                ->withInput()
                ->with('error', 'La barbería no brinda atención en el día seleccionado.');
        }

        $horaApertura = Carbon::createFromFormat('H:i:s', $horario->hora_apertura);
        $horaCierre = Carbon::createFromFormat('H:i:s', $horario->hora_cierre);

        if ($horaInicio->lt($horaApertura) || $horaFin->gt($horaCierre)) {
            return back()
                ->withInput()
                ->with('error', 'El horario seleccionado está fuera del horario de atención o el servicio terminaría después del cierre.');
        }

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
            return back()->with('error', 'No se puede cancelar una cita completada.');
        }

        $cita->update([
            'estado' => 'cancelada',
        ]);

        return back()->with('success', 'Cita cancelada correctamente.');
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
            return back()->with('error', 'Solo se pueden completar citas pendientes.');
        }

        DB::transaction(function () use ($cita, $idBarberia) {
            $puntosGanados = 10;

            $cita->update([
                'estado' => 'completada',
            ]);

            $cita->cliente->update(['ultima_visita' => $cita->fecha]);
            $cita->cliente->increment('puntos', $puntosGanados);

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
                'referencia' => 'cita:'.$cita->id_cita,
                'created_at' => now(),
            ]);
        });

        return back()->with('success', 'Cita completada correctamente. Se actualizó historial, última visita y puntos del cliente.');
    }
}
