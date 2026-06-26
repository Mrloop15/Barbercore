<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use Illuminate\Http\Request;

class ServicioApiController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $servicios = Servicio::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $servicios,
        ]);
    }

    public function store(Request $request)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $datos = $request->validate([
        'nombre' => 'required|string|max:100',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric|min:0',
        'duracion_minutos' => 'required|integer|min:1',
        'activo' => 'nullable|boolean',
    ]);

    $datos['id_barberia'] = $idBarberia;
    $datos['activo'] = $datos['activo'] ?? 1;

    $servicio = Servicio::create($datos);

    return response()->json([
        'ok' => true,
        'message' => 'Servicio registrado correctamente.',
        'data' => $servicio,
    ], 201);
}

public function show(Request $request, string $id)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $servicio = Servicio::where('id_barberia', $idBarberia)
        ->where('id_servicio', $id)
        ->firstOrFail();

    return response()->json([
        'ok' => true,
        'data' => $servicio,
    ]);
}

public function update(Request $request, string $id)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $servicio = Servicio::where('id_barberia', $idBarberia)
        ->where('id_servicio', $id)
        ->firstOrFail();

    $datos = $request->validate([
        'nombre' => 'sometimes|required|string|max:100',
        'descripcion' => 'nullable|string',
        'precio' => 'sometimes|required|numeric|min:0',
        'duracion_minutos' => 'sometimes|required|integer|min:1',
        'activo' => 'sometimes|required|boolean',
    ]);

    $servicio->update($datos);

    return response()->json([
        'ok' => true,
        'message' => 'Servicio actualizado correctamente.',
        'data' => $servicio,
    ]);
}

public function destroy(Request $request, string $id)
{
    $usuario = $request->user();
    $idBarberia = $usuario->id_barberia ?? 1;

    $servicio = Servicio::where('id_barberia', $idBarberia)
        ->where('id_servicio', $id)
        ->firstOrFail();

    $servicio->update([
        'activo' => 0,
    ]);

    return response()->json([
        'ok' => true,
        'message' => 'Servicio desactivado correctamente.',
    ]);
}
}