<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'password' => 'required',
        ]);

        $usuario = Usuario::where('correo', $request->correo)
            ->where('activo', 1)
            ->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            return response()->json([
                'ok' => false,
                'message' => 'Credenciales incorrectas.',
            ], 401);
        }

        $token = $usuario->createToken('barbercore_mobile')->plainTextToken;

        return response()->json([
            'ok' => true,
            'message' => 'Inicio de sesión correcto.',
            'token' => $token,
            'usuario' => [
                'id_usuario' => $usuario->id_usuario,
                'id_barberia' => $usuario->id_barberia,
                'nombre' => $usuario->nombre,
                'correo' => $usuario->correo,
                'rol' => $usuario->rol,
            ],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json([
            'ok' => true,
            'usuario' => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}