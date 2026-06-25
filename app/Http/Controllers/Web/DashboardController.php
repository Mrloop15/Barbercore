<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use App\Models\Cita;
use App\Models\Producto;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $idBarberia = $usuario->id_barberia ?? 1;

        $hoy = Carbon::today();

        $totalClientes = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->count();

        $citasHoy = Cita::where('id_barberia', $idBarberia)
            ->whereDate('fecha', $hoy)
            ->count();

        $citasPendientes = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'pendiente')
            ->count();

        $citasCompletadas = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'completada')
            ->count();

        $ingresosDia = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'completada')
            ->whereDate('fecha', $hoy)
            ->sum('precio');

        $ingresosMes = Cita::where('id_barberia', $idBarberia)
            ->where('estado', 'completada')
            ->whereMonth('fecha', $hoy->month)
            ->whereYear('fecha', $hoy->year)
            ->sum('precio');

        $clientesInactivos = Cliente::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereNotNull('ultima_visita')
            ->whereDate('ultima_visita', '<=', $hoy->copy()->subDays(20))
            ->count();

        $productosBajoStock = Producto::where('id_barberia', $idBarberia)
            ->where('activo', 1)
            ->whereColumn('stock', '<=', 'stock_minimo')
            ->count();

        $proximasCitas = Cita::with(['cliente', 'servicio'])
            ->where('id_barberia', $idBarberia)
            ->where('estado', 'pendiente')
            ->whereDate('fecha', '>=', $hoy)
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalClientes',
            'citasHoy',
            'citasPendientes',
            'citasCompletadas',
            'ingresosDia',
            'ingresosMes',
            'clientesInactivos',
            'productosBajoStock',
            'proximasCitas'
        ));
    }
}