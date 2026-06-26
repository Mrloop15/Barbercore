<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CanjeRecompensa;
use App\Models\Cliente;
use App\Models\MovimientoPunto;
use App\Models\Recompensa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecompensaController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $buscar = $request->input('buscar');

        $recompensas = Recompensa::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'LIKE', "%{$buscar}%")
                        ->orWhere('descripcion', 'LIKE', "%{$buscar}%")
                        ->orWhere('tipo', 'LIKE', "%{$buscar}%");
                });
            })
            ->orderBy('puntos_requeridos')
            ->paginate(10)
            ->withQueryString();

        $totalRecompensas = Recompensa::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->count();

        $totalCanjes = CanjeRecompensa::where('id_barberia', $idBarberia)
            ->count();

        $clientesConPuntos = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->where('puntos', '>', 0)
            ->count();

        return view('recompensas.index', compact(
            'recompensas',
            'buscar',
            'totalRecompensas',
            'totalCanjes',
            'clientesConPuntos'
        ));
    }

    public function create()
    {
        return view('recompensas.create');
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $request->validate([
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'puntos_requeridos' => 'required|integer|min:1',
            'tipo' => 'required|in:descuento,servicio,producto,premium',
            'valor' => 'required|numeric|min:0',
        ], [
            'nombre.required' => 'El nombre de la recompensa es obligatorio.',
            'puntos_requeridos.required' => 'Los puntos requeridos son obligatorios.',
            'tipo.required' => 'Selecciona el tipo de recompensa.',
            'valor.required' => 'El valor de la recompensa es obligatorio.',
        ]);

        Recompensa::create([
            'id_barberia' => $idBarberia,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'puntos_requeridos' => $request->puntos_requeridos,
            'tipo' => $request->tipo,
            'valor' => $request->valor,
            'activo' => 1,
        ]);

        return redirect()
            ->route('recompensas.index')
            ->with('success', 'Recompensa registrada correctamente.');
    }

    public function edit(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $recompensa = Recompensa::where('id_barberia', $idBarberia)
            ->where('id_recompensa', $id)
            ->firstOrFail();

        return view('recompensas.edit', compact('recompensa'));
    }

    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $recompensa = Recompensa::where('id_barberia', $idBarberia)
            ->where('id_recompensa', $id)
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:120',
            'descripcion' => 'nullable|string',
            'puntos_requeridos' => 'required|integer|min:1',
            'tipo' => 'required|in:descuento,servicio,producto,premium',
            'valor' => 'required|numeric|min:0',
        ], [
            'nombre.required' => 'El nombre de la recompensa es obligatorio.',
            'puntos_requeridos.required' => 'Los puntos requeridos son obligatorios.',
            'tipo.required' => 'Selecciona el tipo de recompensa.',
            'valor.required' => 'El valor de la recompensa es obligatorio.',
        ]);

        $recompensa->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'puntos_requeridos' => $request->puntos_requeridos,
            'tipo' => $request->tipo,
            'valor' => $request->valor,
        ]);

        return redirect()
            ->route('recompensas.index')
            ->with('success', 'Recompensa actualizada correctamente.');
    }

    public function destroy(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $recompensa = Recompensa::where('id_barberia', $idBarberia)
            ->where('id_recompensa', $id)
            ->firstOrFail();

        $recompensa->update([
            'activo' => 0,
        ]);

        return redirect()
            ->route('recompensas.index')
            ->with('success', 'Recompensa eliminada correctamente.');
    }

    public function formCanjear()
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $clientes = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('nombre')
            ->get();

        $recompensas = Recompensa::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->orderBy('puntos_requeridos')
            ->get();

        $ultimosCanjes = CanjeRecompensa::with(['cliente', 'recompensa'])
            ->where('id_barberia', $idBarberia)
            ->orderByDesc('fecha_canje')
            ->limit(10)
            ->get();

        return view('recompensas.canjear', compact(
            'clientes',
            'recompensas',
            'ultimosCanjes'
        ));
    }

    public function canjear(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $request->validate([
            'id_cliente' => 'required|exists:clientes,id_cliente',
            'id_recompensa' => 'required|exists:recompensas,id_recompensa',
        ], [
            'id_cliente.required' => 'Selecciona un cliente.',
            'id_recompensa.required' => 'Selecciona una recompensa.',
        ]);

        $cliente = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->where('id_cliente', $request->id_cliente)
            ->firstOrFail();

        $recompensa = Recompensa::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->where('id_recompensa', $request->id_recompensa)
            ->firstOrFail();

        if ($cliente->puntos < $recompensa->puntos_requeridos) {
            return back()
                ->withInput()
                ->with('error', 'El cliente no tiene puntos suficientes para canjear esta recompensa.');
        }

        DB::transaction(function () use ($cliente, $recompensa, $idBarberia) {
            CanjeRecompensa::create([
                'id_barberia' => $idBarberia,
                'id_cliente' => $cliente->id_cliente,
                'id_recompensa' => $recompensa->id_recompensa,
                'puntos_usados' => $recompensa->puntos_requeridos,
                'fecha_canje' => now(),
            ]);

            $cliente->update([
                'puntos' => DB::raw('puntos - ' . $recompensa->puntos_requeridos),
            ]);

            MovimientoPunto::create([
                'id_barberia' => $idBarberia,
                'id_cliente' => $cliente->id_cliente,
                'tipo' => 'resta',
                'puntos' => $recompensa->puntos_requeridos,
                'motivo' => 'Canje de recompensa: ' . $recompensa->nombre,
                'referencia' => 'recompensa:' . $recompensa->id_recompensa,
                'created_at' => now(),
            ]);
        });

        return redirect()
            ->route('recompensas.formCanjear')
            ->with('success', 'Recompensa canjeada correctamente. Los puntos del cliente fueron actualizados.');
    }
}