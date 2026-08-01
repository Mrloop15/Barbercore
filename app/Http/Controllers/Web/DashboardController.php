<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Services\BusinessMetricsService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(BusinessMetricsService $metrics)
    {
        $idBarberia = Auth::user()->id_barberia ?? 1;
        $month = Carbon::today()->format('Y-m');

        $data = $metrics->monthly($idBarberia, $month);
        $data['ingresosDia'] = $data['ingresosHoy'];
        $data['proximasCitas'] = Cita::with(['cliente', 'servicio'])->where('id_barberia', $idBarberia)
            ->where('estado', 'pendiente')->whereDate('fecha', '>=', Carbon::today())->orderBy('fecha')->orderBy('hora_inicio')->limit(5)->get();

        return view('dashboard.index', $data);
    }
}
