<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ServicioController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $buscar = $request->input('buscar');

        $servicios = Servicio::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'LIKE', "%{$buscar}%")
                        ->orWhere('descripcion', 'LIKE', "%{$buscar}%");
                });
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('servicios.index', compact('servicios', 'buscar'));
    }

    public function create()
    {
        return view('servicios.create');
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $request->validate([
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'precio' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
            'mostrar_landing' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'imagen.max' => 'La imagen no debe superar los 4 MB.',
            'precio.required' => 'El precio del servicio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'duracion_minutos.required' => 'La duración del servicio es obligatoria.',
            'duracion_minutos.integer' => 'La duración debe ser un número entero.',
        ]);

        $rutaImagen = null;

        if ($request->hasFile('imagen')) {
            $rutaImagen = $request->file('imagen')->store('servicios', 'public');
        }

        Servicio::create([
            'id_barberia' => $idBarberia,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'imagen' => $rutaImagen,
            'precio' => $request->precio,
            'duracion_minutos' => $request->duracion_minutos,
            'activo' => 1,
            'mostrar_landing' => $request->boolean('mostrar_landing'),
        ]);

        return redirect()
            ->route('servicios.index')
            ->with('success', 'Servicio registrado correctamente.');
    }

    public function edit(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $servicio = Servicio::where('id_barberia', $idBarberia)
            ->where('id_servicio', $id)
            ->firstOrFail();

        return view('servicios.edit', compact('servicio'));
    }

    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $servicio = Servicio::where('id_barberia', $idBarberia)
            ->where('id_servicio', $id)
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'imagen' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'precio' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
            'mostrar_landing' => 'nullable|boolean',
        ], [
            'nombre.required' => 'El nombre del servicio es obligatorio.',
            'imagen.image' => 'El archivo seleccionado debe ser una imagen válida.',
            'imagen.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'imagen.max' => 'La imagen no debe superar los 4 MB.',
            'precio.required' => 'El precio del servicio es obligatorio.',
            'precio.numeric' => 'El precio debe ser un número válido.',
            'duracion_minutos.required' => 'La duración del servicio es obligatoria.',
            'duracion_minutos.integer' => 'La duración debe ser un número entero.',
        ]);

        $datos = [
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'duracion_minutos' => $request->duracion_minutos,
            'mostrar_landing' => $request->boolean('mostrar_landing'),
        ];

        if ($request->hasFile('imagen')) {
            if ($servicio->imagen && Storage::disk('public')->exists($servicio->imagen)) {
                Storage::disk('public')->delete($servicio->imagen);
            }

            $datos['imagen'] = $request->file('imagen')->store('servicios', 'public');
        }

        $servicio->update($datos);

        return redirect()
            ->route('servicios.index')
            ->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $servicio = Servicio::where('id_barberia', $idBarberia)
            ->where('id_servicio', $id)
            ->firstOrFail();

        $servicio->update([
            'activo' => 0,
        ]);

        return redirect()
            ->route('servicios.index')
            ->with('success', 'Servicio eliminado correctamente.');
    }

    public function cambiarVisibilidadLanding(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $servicio = Servicio::where('id_barberia', $idBarberia)
            ->where('id_servicio', $id)
            ->where('activo', 1)
            ->firstOrFail();

        $servicio->update([
            'mostrar_landing' => ! $servicio->mostrar_landing,
        ]);

        $mensaje = $servicio->mostrar_landing
            ? 'El servicio ahora se muestra en la landing.'
            : 'El servicio se ocultó de la landing.';

        return back()->with('success', $mensaje);
    }
}
