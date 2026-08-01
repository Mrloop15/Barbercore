<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantAssigned
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->id_barberia, 403, 'El usuario no tiene una barbería asignada.');

        return $next($request);
    }
}
