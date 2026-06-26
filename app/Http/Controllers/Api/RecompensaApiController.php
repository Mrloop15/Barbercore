<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CanjeRecompensa;
use App\Models\Cliente;
use App\Models\MovimientoPunto;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecompensaApiController extends Controller
{
    public function index(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $recompensas = Recompensa::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('puntos_requeridos')
            ->get();

        return response()->json([
            'ok' => true,
            'data' => $recompensas,
        ]);
    }

    public function store(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $datos = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'puntos_requeridos' => 'required|integer|min:1',
            'tipo' => 'required|string|max:50',
            'valor' => 'nullable|numeric|min:0',
            'activo' => 'nullable|boolean',
        ]);

        $datos['id_barberia'] = $idBarberia;
        $datos['activo'] = $datos['activo'] ?? 1;

        $recompensa = Recompensa::create($datos);

        return response()->json([
            'ok' => true,
            'message' => 'Recompensa registrada correctamente.',
            'data' => $recompensa,
        ], 201);
    }

    public function show(Request $request, string $id)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $recompensa = Recompensa::where('id_barberia', $idBarberia)
            ->where('id_recompensa', $id)
            ->firstOrFail();

        return response()->json([
            'ok' => true,
            'data' => $recompensa,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $recompensa = Recompensa::where('id_barberia', $idBarberia)
            ->where('id_recompensa', $id)
            ->firstOrFail();

        $datos = $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'descripcion' => 'nullable|string',
            'puntos_requeridos' => 'sometimes|required|integer|min:1',
            'tipo' => 'sometimes|required|string|max:50',
            'valor' => 'nullable|numeric|min:0',
            'activo' => 'sometimes|required|boolean',
        ]);

        $recompensa->update($datos);

        return response()->json([
            'ok' => true,
            'message' => 'Recompensa actualizada correctamente.',
            'data' => $recompensa,
        ]);
    }

    public function destroy(Request $request, string $id)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $recompensa = Recompensa::where('id_barberia', $idBarberia)
            ->where('id_recompensa', $id)
            ->firstOrFail();

        $recompensa->update([
            'activo' => 0,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Recompensa desactivada correctamente.',
        ]);
    }

    public function canjear(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $datos = $request->validate([
            'id_cliente' => 'required|integer|exists:clientes,id_cliente',
            'id_recompensa' => 'required|integer|exists:recompensas,id_recompensa',
        ]);

        $canje = DB::transaction(function () use ($datos, $idBarberia) {
            $cliente = Cliente::where('id_barberia', $idBarberia)
                ->where('id_cliente', $datos['id_cliente'])
                ->where('activo', 1)
                ->lockForUpdate()
                ->firstOrFail();

            $recompensa = Recompensa::where('id_barberia', $idBarberia)
                ->where('id_recompensa', $datos['id_recompensa'])
                ->where('activo', 1)
                ->firstOrFail();

            if ($cliente->puntos < $recompensa->puntos_requeridos) {
                abort(response()->json([
                    'ok' => false,
                    'message' => 'El cliente no tiene puntos suficientes para canjear esta recompensa.',
                    'puntos_cliente' => $cliente->puntos,
                    'puntos_requeridos' => $recompensa->puntos_requeridos,
                ], 422));
            }

            $cliente->decrement('puntos', $recompensa->puntos_requeridos);

            $canje = CanjeRecompensa::create([
                'id_barberia' => $idBarberia,
                'id_cliente' => $cliente->id_cliente,
                'id_recompensa' => $recompensa->id_recompensa,
                'puntos_usados' => $recompensa->puntos_requeridos,
                'fecha_canje' => now(),
            ]);

            MovimientoPunto::create([
                'id_barberia' => $idBarberia,
                'id_cliente' => $cliente->id_cliente,
                'tipo' => 'resta',
                'puntos' => $recompensa->puntos_requeridos,
                'motivo' => 'Canje de recompensa: ' . $recompensa->nombre,
                'referencia' => 'canje:' . $canje->id_canje,
                'created_at' => now(),
            ]);

            return $canje->load(['cliente', 'recompensa']);
        });

        return response()->json([
            'ok' => true,
            'message' => 'Recompensa canjeada correctamente.',
            'data' => $canje,
        ], 201);
    }
}