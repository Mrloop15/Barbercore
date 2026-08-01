<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EstadisticaController extends Controller
{
    public function index(Request $request)
    {
        return view('estadisticas.index', $this->reportData($request));
    }

    public function download(Request $request)
    {
        $data = $this->reportData($request);
        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml(view('estadisticas.pdf', $data)->render(), 'UTF-8');
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="reporte-barbercore-' . $data['desde']->format('Ymd') . '-' . $data['hasta']->format('Ymd') . '.pdf"',
        ]);
    }

    public function downloadExcel(Request $request)
    {
        $data = $this->reportData($request);
        $filename = 'reporte-barbercore-' . $data['desde']->format('Ymd') . '-' . $data['hasta']->format('Ymd') . '.xml';

        return response(view('estadisticas.excel', $data)->render(), 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0, no-cache, no-store, must-revalidate',
        ]);
    }

    private function reportData(Request $request): array
    {
        $validated = $request->validate([
            'desde' => ['nullable', 'date'], 'hasta' => ['nullable', 'date', 'after_or_equal:desde'],
            'estado' => ['nullable', 'in:todos,pendiente,completada,cancelada'],
        ]);
        $desde = Carbon::parse($validated['desde'] ?? now()->startOfMonth()->toDateString())->startOfDay();
        $hasta = Carbon::parse($validated['hasta'] ?? now()->endOfMonth()->toDateString())->endOfDay();
        $estado = $validated['estado'] ?? 'todos';
        $idBarberia = Auth::user()->id_barberia ?? 1;
        $query = Cita::with(['cliente', 'servicio', 'barbero'])->where('id_barberia', $idBarberia)->whereBetween('fecha', [$desde->toDateString(), $hasta->toDateString()]);
        if ($estado !== 'todos') $query->where('estado', $estado);
        $citas = $query->orderBy('fecha')->orderBy('hora_inicio')->get();

        return [
            'desde' => $desde, 'hasta' => $hasta, 'estado' => $estado, 'citas' => $citas,
            'totalCitas' => $citas->count(), 'completadas' => $citas->where('estado', 'completada')->count(),
            'canceladas' => $citas->where('estado', 'cancelada')->count(), 'pendientes' => $citas->where('estado', 'pendiente')->count(),
            'ingresos' => $citas->where('estado', 'completada')->sum('precio'),
            'barberia' => Auth::user()->barberia->nombre ?? 'BarberCore',
        ];
    }
}
