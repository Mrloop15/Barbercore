<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    private const TIMEOUT_SECONDS = 600;

    public function handle(Request $request, Closure $next): Response
    {
        $lastActivity = (int) $request->session()->get('auth_last_activity', now()->timestamp);

        if ((now()->timestamp - $lastActivity) >= self::TIMEOUT_SECONDS) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'La sesión terminó por inactividad.',
                    'redirect' => route('login'),
                ], 401);
            }

            return redirect()->route('login')->withErrors([
                'correo' => 'Tu sesión terminó después de 10 minutos de inactividad.',
            ]);
        }

        $request->session()->put('auth_last_activity', now()->timestamp);

        return $next($request);
    }
}
