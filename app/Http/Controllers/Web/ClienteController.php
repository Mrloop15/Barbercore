<?php

namespace App\Http\Controllers\Web;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $buscar = $request->input('buscar');

        $clientes = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'LIKE', "%{$buscar}%")
                        ->orWhere('apellido', 'LIKE', "%{$buscar}%")
                        ->orWhere('telefono', 'LIKE', "%{$buscar}%")
                        ->orWhereRaw("CONCAT(nombre, ' ', IFNULL(apellido, '')) LIKE ?", ["%{$buscar}%"]);
                });
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('clientes.index', compact('clientes', 'buscar'));
    }

        public function inactivos(Request $request)
        {
            $usuario = Auth::user();
            $idBarberia = $usuario->id_barberia ?? 1;

            $hoy = Carbon::today();
            $diasLimite = 20;
            $fechaLimite = $hoy->copy()->subDays($diasLimite);

            $filtro = $request->input('filtro', 'todos');

            $clientes = Cliente::with(['citas' => function ($query) use ($hoy) {
                    $query->where('estado', 'pendiente')
                        ->whereDate('fecha', '>=', $hoy)
                        ->orderBy('fecha')
                        ->orderBy('hora_inicio');
                }])
                ->where('id_barberia', $idBarberia)
                ->where('activo', 1)
                ->whereNotNull('ultima_visita')
                ->whereDate('ultima_visita', '<=', $fechaLimite)
                ->get()
                ->map(function ($cliente) use ($hoy) {
                    $cliente->dias_sin_venir = Carbon::parse($cliente->ultima_visita)->diffInDays($hoy);
                    $cliente->tiene_cita_pendiente = $cliente->citas->isNotEmpty();

                    return $cliente;
                });

            if ($filtro === 'con_cita') {
                $clientes = $clientes->filter(function ($cliente) {
                    return $cliente->tiene_cita_pendiente;
                });
            }

            if ($filtro === 'sin_cita') {
                $clientes = $clientes->filter(function ($cliente) {
                    return !$cliente->tiene_cita_pendiente;
                });
            }

            $clientes = $clientes
                ->sortByDesc('dias_sin_venir')
                ->values();

            $totalInactivos = $clientes->count();

            return view('clientes.inactivos', compact(
                'clientes',
                'filtro',
                'diasLimite',
                'totalInactivos'
            ));
        }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'cumpleanos' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'observaciones' => 'nullable|string',
            'puntos' => 'nullable|integer|min:0',
            'ultima_visita' => 'nullable|date',
        ], [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'foto.max' => 'La imagen no debe pesar más de 2 MB.',
        ]);

        $rutaFoto = null;

        if ($request->hasFile('foto')) {
            $rutaFoto = $request->file('foto')->store('clientes', 'public');
        }

        Cliente::create([
            'id_barberia' => $idBarberia,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'cumpleanos' => $request->cumpleanos,
            'foto' => $rutaFoto,
            'observaciones' => $request->observaciones,
            'puntos' => $request->puntos ?? 0,
            'ultima_visita' => $request->ultima_visita,
            'activo' => 1,
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cliente = Cliente::with(['citas.servicio'])
            ->where('id_barberia', $idBarberia)
            ->where('id_cliente', $id)
            ->firstOrFail();

        return view('clientes.show', compact('cliente'));
    }

    public function edit(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cliente = Cliente::where('id_barberia', $idBarberia)
            ->where('id_cliente', $id)
            ->firstOrFail();

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cliente = Cliente::where('id_barberia', $idBarberia)
            ->where('id_cliente', $id)
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'nullable|string|max:100',
            'telefono' => 'nullable|string|max:20',
            'cumpleanos' => 'nullable|date',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'observaciones' => 'nullable|string',
            'puntos' => 'nullable|integer|min:0',
            'ultima_visita' => 'nullable|date',
        ], [
            'nombre.required' => 'El nombre del cliente es obligatorio.',
            'foto.image' => 'El archivo debe ser una imagen.',
            'foto.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'foto.max' => 'La imagen no debe pesar más de 2 MB.',
        ]);

        $rutaFoto = $cliente->foto;

        if ($request->hasFile('foto')) {
            if ($cliente->foto) {
                Storage::disk('public')->delete($cliente->foto);
            }

            $rutaFoto = $request->file('foto')->store('clientes', 'public');
        }

        $cliente->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'cumpleanos' => $request->cumpleanos,
            'foto' => $rutaFoto,
            'observaciones' => $request->observaciones,
            'puntos' => $request->puntos ?? 0,
            'ultima_visita' => $request->ultima_visita,
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $cliente = Cliente::where('id_barberia', $idBarberia)
            ->where('id_cliente', $id)
            ->firstOrFail();

        $cliente->update([
            'activo' => 0,
        ]);

        return redirect()
            ->route('clientes.index')
            ->with('success', 'Cliente eliminado correctamente.');
    }
}