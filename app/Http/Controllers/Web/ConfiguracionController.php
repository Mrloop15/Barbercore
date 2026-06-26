<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Barberia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();

        $barberia = Barberia::where('id_barberia', $usuario->id_barberia ?? 1)
            ->first();

        return view('configuracion.index', compact('usuario', 'barberia'));
    }

    public function actualizarBarberia(Request $request)
    {
        $usuario = Auth::user();

        $barberia = Barberia::where('id_barberia', $usuario->id_barberia ?? 1)
            ->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:150',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nombre.required' => 'El nombre de la barbería es obligatorio.',
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
            'logo' => $rutaLogo,
        ]);

        return redirect()
            ->route('configuracion.index')
            ->with('success', 'Información de la barbería actualizada correctamente.');
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

        $usuario->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
        ]);

        return redirect()
            ->route('configuracion.index')
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

        if (!Hash::check($request->password_actual, $usuario->password)) {
            return redirect()
                ->route('configuracion.index')
                ->with('error', 'La contraseña actual no es correcta.');
        }

        $usuario->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('configuracion.index')
            ->with('success', 'Contraseña actualizada correctamente.');
    }
}