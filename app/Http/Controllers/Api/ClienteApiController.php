<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteApiController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $clientes = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $clientes,
        ]);
    }

    public function store(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'cumpleanos' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $cliente = Cliente::create([
            'id_barberia' => $idBarberia,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'cumpleanos' => $request->cumpleanos,
            'observaciones' => $request->observaciones,
            'puntos' => 0,
            'ultima_visita' => null,
            'activo' => 1,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Cliente registrado correctamente.',
            'data' => $cliente,
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cliente = Cliente::with(['citas.servicio'])
            ->where('id_barberia', $idBarberia)
            ->where('id_cliente', $id)
            ->firstOrFail();

        return response()->json([
            'ok' => true,
            'data' => $cliente,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cliente = Cliente::where('id_barberia', $idBarberia)
            ->where('id_cliente', $id)
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'cumpleanos' => 'nullable|date',
            'observaciones' => 'nullable|string',
        ]);

        $cliente->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'cumpleanos' => $request->cumpleanos,
            'observaciones' => $request->observaciones,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Cliente actualizado correctamente.',
            'data' => $cliente,
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cliente = Cliente::where('id_barberia', $idBarberia)
            ->where('id_cliente', $id)
            ->firstOrFail();

        $cliente->update([
            'activo' => 0,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Cliente eliminado correctamente.',
        ]);
    }

    public function inactivos(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $clientes = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereNotNull('ultima_visita')
            ->whereDate('ultima_visita', '<=', now()->subDays(20)->toDateString())
            ->orderBy('ultima_visita')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $clientes,
        ]);
    }
}