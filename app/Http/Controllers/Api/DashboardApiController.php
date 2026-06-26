<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Cliente;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    public function resumen(Request $request)
    {
        $usuario = $request->user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $hoy = Carbon::today();

        return response()->json([
            'ok' => true,
            'data' => [
                'total_clientes' => Cliente::where('id_barberia', $idBarberia)
                    ->where('activo', 1)
                    ->count(),

                'citas_hoy' => Cita::where('id_barberia', $idBarberia)
                    ->whereDate('fecha', $hoy)
                    ->count(),

                'citas_pendientes' => Cita::where('id_barberia', $idBarberia)
                    ->where('estado', 'pendiente')
                    ->count(),

                'citas_completadas' => Cita::where('id_barberia', $idBarberia)
                    ->where('estado', 'completada')
                    ->count(),

                'ingresos_dia' => Cita::where('id_barberia', $idBarberia)
                    ->where('estado', 'completada')
                    ->whereDate('fecha', $hoy)
                    ->sum('precio'),

                'ingresos_mes' => Cita::where('id_barberia', $idBarberia)
                    ->where('estado', 'completada')
                    ->whereMonth('fecha', $hoy->month)
                    ->whereYear('fecha', $hoy->year)
                    ->sum('precio'),

                'clientes_inactivos' => Cliente::where('id_barberia', $idBarberia)
                    ->where('activo', 1)
                    ->whereNotNull('ultima_visita')
                    ->whereDate('ultima_visita', '<=', $hoy->copy()->subDays(20))
                    ->count(),

                'productos_bajo_stock' => Producto::where('id_barberia', $idBarberia)
                    ->where('activo', 1)
                    ->whereColumn('stock', '<=', 'stock_minimo')
                    ->count(),
            ],
        ]);
    }
}