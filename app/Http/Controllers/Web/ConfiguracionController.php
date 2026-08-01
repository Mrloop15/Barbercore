<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Barberia;
use App\Models\HorarioAtencion;
use App\Models\PreguntaFrecuente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $barberia = Barberia::where('id_barberia', $usuario->id_barberia ?? 1)->first();

        $horarios = HorarioAtencion::where('id_barberia', $barberia?->id_barberia)
            ->get()
            ->keyBy('dia_semana');

        $totalPreguntas = PreguntaFrecuente::where('id_barberia', $barberia?->id_barberia)->count();

        $diasSemana = [
            0 => 'Lunes',
            1 => 'Martes',
            2 => 'Miércoles',
            3 => 'Jueves',
            4 => 'Viernes',
            5 => 'Sábado',
            6 => 'Domingo',
        ];

        return view('configuracion.index', compact(
            'usuario',
            'barberia',
            'horarios',
            'totalPreguntas',
            'diasSemana'
        ));
    }

    public function preguntasFrecuentes()
    {
        $idBarberia = Auth::user()->id_barberia ?? 1;
        $preguntasFrecuentes = PreguntaFrecuente::where('id_barberia', $idBarberia)
            ->orderBy('orden')
            ->orderBy('id_pregunta')
            ->get();

        return view('configuracion.preguntas-frecuentes', compact('preguntasFrecuentes'));
    }

    public function actualizarBarberia(Request $request)
    {
        $usuario = Auth::user();
        $barberia = Barberia::where('id_barberia', $usuario->id_barberia ?? 1)->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'google_maps_url' => 'nullable|url:http,https|max:500',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nombre.required' => 'El nombre de la barbería es obligatorio.',
            'google_maps_url.url' => 'Ingresa un enlace válido de Google Maps.',
            'logo.image' => 'El archivo debe ser una imagen.',
            'logo.mimes' => 'El logo debe ser JPG, JPEG, PNG o WEBP.',
            'logo.max' => 'El logo no debe pesar más de 2 MB.',
        ]);

        $rutaLogo = $barberia->logo;

        if ($request->hasFile('logo')) {
            if ($barberia->logo) {
                Storage::disk('public')->delete($barberia->logo);
            }

            $rutaLogo = $request->file('logo')->store('barberias', 'public');
        }

        $barberia->update([
            'nombre' => $request->nombre,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'google_maps_url' => $request->google_maps_url,
            'logo' => $rutaLogo,
        ]);

        return redirect()->route('configuracion.index', ['seccion' => 'landing'])
            ->with('success', 'Información de la barbería actualizada correctamente.');
    }

    public function actualizarHorarios(Request $request)
    {
        $idBarberia = Auth::user()->id_barberia ?? 1;

        $datos = $request->validate([
            'horarios' => 'required|array|size:7',
            'horarios.*.dia_semana' => 'required|integer|between:0,6|distinct',
            'horarios.*.abierto' => 'nullable|boolean',
            'horarios.*.hora_apertura' => 'nullable|date_format:H:i',
            'horarios.*.hora_cierre' => 'nullable|date_format:H:i',
        ]);

        DB::transaction(function () use ($datos, $idBarberia) {
            foreach ($datos['horarios'] as $horario) {
                $abierto = filter_var($horario['abierto'] ?? false, FILTER_VALIDATE_BOOLEAN);

                if ($abierto && (empty($horario['hora_apertura']) || empty($horario['hora_cierre']))) {
                    throw ValidationException::withMessages([
                        'horarios' => 'Indica la hora de apertura y cierre para todos los días abiertos.',
                    ]);
                }

                if ($abierto && $horario['hora_cierre'] <= $horario['hora_apertura']) {
                    throw ValidationException::withMessages([
                        'horarios' => 'La hora de cierre debe ser posterior a la hora de apertura.',
                    ]);
                }

                HorarioAtencion::updateOrCreate(
                    ['id_barberia' => $idBarberia, 'dia_semana' => $horario['dia_semana']],
                    [
                        'abierto' => $abierto,
                        'hora_apertura' => $abierto ? $horario['hora_apertura'] : null,
                        'hora_cierre' => $abierto ? $horario['hora_cierre'] : null,
                    ]
                );
            }
        });

        return redirect()->route('configuracion.index', ['seccion' => 'landing'])
            ->with('success', 'Horarios de atención actualizados correctamente.');
    }

    public function actualizarPreguntasFrecuentes(Request $request)
    {
        $idBarberia = Auth::user()->id_barberia ?? 1;

        $datos = $request->validate([
            'preguntas' => 'nullable|array|max:20',
            'preguntas.*.id_pregunta' => 'nullable|integer',
            'preguntas.*.pregunta' => 'required|string|max:255',
            'preguntas.*.respuesta' => 'required|string|max:2000',
            'preguntas.*.activo' => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($datos, $idBarberia) {
            $idsConservados = [];

            foreach (array_values($datos['preguntas'] ?? []) as $orden => $pregunta) {
                $modelo = null;

                if (! empty($pregunta['id_pregunta'])) {
                    $modelo = PreguntaFrecuente::where('id_barberia', $idBarberia)
                        ->where('id_pregunta', $pregunta['id_pregunta'])
                        ->firstOrFail();
                }

                $modelo ??= new PreguntaFrecuente(['id_barberia' => $idBarberia]);
                $modelo->fill([
                    'pregunta' => $pregunta['pregunta'],
                    'respuesta' => $pregunta['respuesta'],
                    'orden' => $orden + 1,
                    'activo' => filter_var($pregunta['activo'] ?? false, FILTER_VALIDATE_BOOLEAN),
                ]);
                $modelo->save();
                $idsConservados[] = $modelo->id_pregunta;
            }

            PreguntaFrecuente::where('id_barberia', $idBarberia)
                ->when($idsConservados, fn ($query) => $query->whereNotIn('id_pregunta', $idsConservados))
                ->delete();
        });

        return redirect()->route('configuracion.preguntas.index')
            ->with('success', 'Preguntas frecuentes actualizadas correctamente.');
    }

    public function actualizarUsuario(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre' => 'required|string|max:150',
            'correo' => [
                'required',
                'email',
                'max:150',
                Rule::unique('usuarios', 'correo')->ignore($usuario->id_usuario, 'id_usuario'),
            ],
        ], [
            'nombre.required' => 'El nombre del usuario es obligatorio.',
            'correo.required' => 'El correo electrónico es obligatorio.',
            'correo.email' => 'Ingresa un correo electrónico válido.',
            'correo.unique' => 'Este correo ya está registrado por otro usuario.',
        ]);

        $usuario->update(['nombre' => $request->nombre, 'correo' => $request->correo]);

        return redirect()->route('configuracion.index', ['seccion' => 'cuenta'])
            ->with('success', 'Información del usuario actualizada correctamente.');
    }

    public function actualizarPassword(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'password_actual' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password_actual.required' => 'Ingresa tu contraseña actual.',
            'password.required' => 'Ingresa la nueva contraseña.',
            'password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        if (! Hash::check($request->password_actual, $usuario->password)) {
            return redirect()->route('configuracion.index', ['seccion' => 'cuenta'])
                ->with('error', 'La contraseña actual no es correcta.');
        }

        $usuario->update(['password' => Hash::make($request->password)]);

        return redirect()->route('configuracion.index', ['seccion' => 'cuenta'])
            ->with('success', 'Contraseña actualizada correctamente.');
    }
}
