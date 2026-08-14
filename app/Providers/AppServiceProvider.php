<?php

namespace App\Providers;

use App\Models\Cita;
use App\Support\BusinessClock;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('components.pagination');
        Paginator::defaultSimpleView('components.pagination-simple');

        View::composer('layouts.app', function ($view) {
            $usuario = Auth::user();

            if (! $usuario) {
                return;
            }

            $ahoraLocal = BusinessClock::now();
            $hoy = $ahoraLocal->toDateString();
            $horaActual = $ahoraLocal->format('H:i:s');
            $proximaCitaBarbero = Cita::with(['cliente', 'servicio'])
                ->where('id_barberia', $usuario->id_barberia)
                ->where('id_barbero', $usuario->id_usuario)
                ->where('estado', 'pendiente')
                ->where(function ($query) use ($hoy, $horaActual) {
                    $query->whereDate('fecha', '>', $hoy)
                        ->orWhere(function ($mismoDia) use ($hoy, $horaActual) {
                            $mismoDia->whereDate('fecha', $hoy)
                                ->where('hora_inicio', '>', $horaActual);
                        });
                })
                ->orderBy('fecha')
                ->orderBy('hora_inicio')
                ->first();

            $proximaCitaInicio = $proximaCitaBarbero
                ? BusinessClock::localDate($proximaCitaBarbero->fecha)->setTimeFromTimeString($proximaCitaBarbero->hora_inicio)
                : null;
            $inicialesBarbero = collect(preg_split('/\s+/', trim($usuario->nombre ?? 'Usuario')))
                ->filter()
                ->take(2)
                ->map(fn ($parte) => mb_strtoupper(mb_substr($parte, 0, 1)))
                ->implode('');

            $view->with(compact('proximaCitaBarbero', 'proximaCitaInicio', 'inicialesBarbero'));
        });
    }
}
