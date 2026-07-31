<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioApiController extends Controller
{
    private function verificarAdmin(Request $request)
    {
        if (!$request->user() || $request->user()->rol !== 'admin') {
            return response()->json([
                'ok' => false,
                'message' => 'No tienes permiso para acceder a este módulo.',
            ], 403);
        }

        return null;
    }

    private function baseQuery(Request $request)
    {
        $usuario = $request->user();

        return Usuario::where('id_barberia', $usuario->id_barberia);
    }

    private function formatearUsuario(Usuario $usuario): array
    {
        return [
            'id_usuario' => $usuario->id_usuario,
            'id_barberia' => $usuario->id_barberia,
            'nombre' => $usuario->nombre,
            'correo' => $usuario->correo,
            'rol' => $usuario->rol,
            'activo' => (int) $usuario->activo,
            'created_at' => $usuario->created_at,
            'updated_at' => $usuario->updated_at,
        ];
    }

    public function index(Request $request)
    {
        if ($respuesta = $this->verificarAdmin($request)) {
            return $respuesta;
        }

        $buscar = $request->input('buscar');
        $rol = $request->input('rol');
        $estado = $request->input('estado');

        $usuarios = $this->baseQuery($request)
            ->when($buscar, function ($query) use ($buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('nombre', 'LIKE', "%{$buscar}%")
                      ->orWhere('correo', 'LIKE', "%{$buscar}%");
                });
            })
            ->when($rol, function ($query) use ($rol) {
                $query->where('rol', $rol);
            })
            ->when($estado !== null && $estado !== '', function ($query) use ($estado) {
                $query->where('activo', (int) $estado);
            })
            ->orderBy('nombre')
            ->get()
            ->map(fn ($usuario) => $this->formatearUsuario($usuario));

        return response()->json([
            'ok' => true,
            'data' => $usuarios,
        ]);
    }

    public function show(Request $request, string $id)
    {
        if ($respuesta = $this->verificarAdmin($request)) {
            return $respuesta;
        }

        $usuario = $this->baseQuery($request)
            ->where('id_usuario', $id)
            ->firstOrFail();

        return response()->json([
            'ok' => true,
            'data' => $this->formatearUsuario($usuario),
        ]);
    }

    public function store(Request $request)
    {
        if ($respuesta = $this->verificarAdmin($request)) {
            return $respuesta;
        }

        $datos = $request->validate([
            'nombre' => 'required|string|max:120',
            'correo' => 'required|email|max:150|unique:usuarios,correo',
            'password' => 'required|string|min:6|confirmed',
            'rol' => 'required|in:admin,barbero',
        ]);

        $usuario = Usuario::create([
            'id_barberia' => $request->user()->id_barberia,
            'nombre' => $datos['nombre'],
            'correo' => $datos['correo'],
            'password' => Hash::make($datos['password']),
            'rol' => $datos['rol'],
            'activo' => 1,
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Usuario registrado correctamente.',
            'data' => $this->formatearUsuario($usuario),
        ], 201);
    }

    public function update(Request $request, string $id)
    {
        if ($respuesta = $this->verificarAdmin($request)) {
            return $respuesta;
        }

        $usuarioEditar = $this->baseQuery($request)
            ->where('id_usuario', $id)
            ->firstOrFail();

        $datos = $request->validate([
            'nombre' => 'sometimes|required|string|max:120',
            'correo' => 'sometimes|required|email|max:150|unique:usuarios,correo,' . $usuarioEditar->id_usuario . ',id_usuario',
            'password' => 'nullable|string|min:6|confirmed',
            'rol' => 'sometimes|required|in:admin,barbero',
        ]);

        $actualizar = [];

        if (array_key_exists('nombre', $datos)) {
            $actualizar['nombre'] = $datos['nombre'];
        }

        if (array_key_exists('correo', $datos)) {
            $actualizar['correo'] = $datos['correo'];
        }

        if (array_key_exists('rol', $datos)) {
            $actualizar['rol'] = $datos['rol'];
        }

        if (!empty($datos['password'])) {
            $actualizar['password'] = Hash::make($datos['password']);
        }

        $usuarioEditar->update($actualizar);
        $usuarioEditar->refresh();

        return response()->json([
            'ok' => true,
            'message' => 'Usuario actualizado correctamente.',
            'data' => $this->formatearUsuario($usuarioEditar),
        ]);
    }

    public function cambiarEstado(Request $request, string $id)
    {
        if ($respuesta = $this->verificarAdmin($request)) {
            return $respuesta;
        }

        $usuarioEditar = $this->baseQuery($request)
            ->where('id_usuario', $id)
            ->firstOrFail();

        if ((int) $usuarioEditar->id_usuario === (int) $request->user()->id_usuario) {
            return response()->json([
                'ok' => false,
                'message' => 'No puedes desactivar tu propio usuario.',
            ], 422);
        }

        $usuarioEditar->update([
            'activo' => $usuarioEditar->activo ? 0 : 1,
        ]);

        $usuarioEditar->refresh();

        return response()->json([
            'ok' => true,
            'message' => 'Estado del usuario actualizado correctamente.',
            'data' => $this->formatearUsuario($usuarioEditar),
        ]);
    }
}