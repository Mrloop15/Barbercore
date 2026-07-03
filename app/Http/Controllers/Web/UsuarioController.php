<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    private function verificarAdmin(): void
    {
        if (Auth::user()->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a este módulo.');
        }
    }

    private function baseQuery()
    {
        $usuario = Auth::user();

        return Usuario::where('id_barberia', $usuario->id_barberia);
    }

    public function index(Request $request)
    {
        $this->verificarAdmin();

        $buscar = $request->input('buscar');
        $filtroRol = $request->input('rol', 'todos');
        $filtroEstado = $request->input('estado', 'todos');

        $usuarios = $this->baseQuery()
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'LIKE', "%{$buscar}%")
                      ->orWhere('correo', 'LIKE', "%{$buscar}%");
                });
            })
            ->when($filtroRol !== 'todos', function ($query) use ($filtroRol) {
                $query->where('rol', $filtroRol);
            })
            ->when($filtroEstado !== 'todos', function ($query) use ($filtroEstado) {
                $query->where('activo', $filtroEstado === 'activos' ? 1 : 0);
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $totalUsuarios = $this->baseQuery()->count();
        $usuariosActivos = $this->baseQuery()->where('activo', 1)->count();
        $usuariosInactivos = $this->baseQuery()->where('activo', 0)->count();

        return view('usuarios.index', compact(
            'usuarios',
            'buscar',
            'filtroRol',
            'filtroEstado',
            'totalUsuarios',
            'usuariosActivos',
            'usuariosInactivos'
        ));
    }

    public function create()
    {
        $this->verificarAdmin();

        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        $this->verificarAdmin();

        $request->validate([
            'nombre' => 'required|string|max:120',
            'correo' => 'required|email|max:120|unique:usuarios,correo',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:admin,barbero',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'Ingresa un correo válido.',
            'correo.unique' => 'Ese correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'rol.required' => 'Debes seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);

        Usuario::create([
            'id_barberia' => Auth::user()->id_barberia,
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'activo' => 1,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    public function edit(string $id)
    {
        $this->verificarAdmin();

        $usuario = $this->baseQuery()
            ->where('id_usuario', $id)
            ->firstOrFail();

        return view('usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, string $id)
    {
        $this->verificarAdmin();

        $usuarioEditar = $this->baseQuery()
            ->where('id_usuario', $id)
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:120',
            'correo' => 'required|email|max:120|unique:usuarios,correo,' . $usuarioEditar->id_usuario . ',id_usuario',
            'password' => 'nullable|string|min:6|confirmed',
            'rol' => 'required|in:admin,barbero',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'correo.required' => 'El correo es obligatorio.',
            'correo.email' => 'Ingresa un correo válido.',
            'correo.unique' => 'Ese correo ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'rol.required' => 'Debes seleccionar un rol.',
            'rol.in' => 'El rol seleccionado no es válido.',
        ]);

        $datos = [
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'rol' => $request->rol,
        ];

        if ($request->filled('password')) {
            $datos['password'] = Hash::make($request->password);
        }

        $usuarioEditar->update($datos);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function cambiarEstado(string $id)
    {
        $this->verificarAdmin();

        $usuarioEditar = $this->baseQuery()
            ->where('id_usuario', $id)
            ->firstOrFail();

        if ($usuarioEditar->id_usuario == Auth::id()) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puedes desactivar tu propio usuario.');
        }

        $usuarioEditar->update([
            'activo' => $usuarioEditar->activo ? 0 : 1,
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Estado del usuario actualizado correctamente.');
    }
}