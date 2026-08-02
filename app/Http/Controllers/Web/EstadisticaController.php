<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenSpout\Common\Entity\Row;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Common\Entity\Style\Style;
use OpenSpout\Writer\XLSX\Writer;

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
        $filename = 'reporte-barbercore-' . $data['desde']->format('Ymd') . '-' . $data['hasta']->format('Ymd') . '.xlsx';

        return response()->streamDownload(function () use ($data) {
            $writer = new Writer();
            $writer->openToFile('php://output');

            $titleStyle = (new Style())->setFontBold()->setFontSize(18)->setFontColor('C9A227');
            $sectionStyle = (new Style())->setFontBold()->setFontSize(12)
                ->setFontColor(Color::WHITE)->setBackgroundColor('1C1C1C');
            $labelStyle = (new Style())->setFontBold()->setBackgroundColor('FAF8F2');
            $moneyStyle = (new Style())->setFormat('$#,##0.00');

            $summarySheet = $writer->getCurrentSheet();
            $summarySheet->setName('Resumen');
            $summarySheet->setColumnWidth(25, 1);
            $summarySheet->setColumnWidthForRange(20, 2, 5);

            $writer->addRow(Row::fromValues(['Reporte BarberCore'], $titleStyle));
            $writer->addRow(Row::fromValues([$data['barberia']]));
            $writer->addRow(Row::fromValues([]));
            $writer->addRow(Row::fromValues(['Periodo', $data['desde']->format('d/m/Y') . ' al ' . $data['hasta']->format('d/m/Y')], $labelStyle));
            $writer->addRow(Row::fromValues(['Estado', ucfirst($data['estado'])], $labelStyle));
            $writer->addRow(Row::fromValues([]));
            $writer->addRow(Row::fromValues(['Total de citas', 'Completadas', 'Pendientes', 'Canceladas', 'Ingresos confirmados'], $sectionStyle));
            $writer->addRow(Row::fromValuesWithStyles([
                $data['totalCitas'], $data['completadas'], $data['pendientes'],
                $data['canceladas'], (float) $data['ingresos'],
            ], columnStyles: [4 => $moneyStyle]));

            $detailSheet = $writer->addNewSheetAndMakeItCurrent();
            $detailSheet->setName('Detalle de citas');
            $detailSheet->setColumnWidth(13, 1);
            $detailSheet->setColumnWidthForRange(12, 2, 3);
            $detailSheet->setColumnWidth(28, 4);
            $detailSheet->setColumnWidth(26, 5);
            $detailSheet->setColumnWidth(22, 6);
            $detailSheet->setColumnWidthForRange(15, 7, 8);
            $detailSheet->setColumnWidth(40, 9);

            $writer->addRow(Row::fromValues([
                'Fecha', 'Hora inicio', 'Hora fin', 'Cliente', 'Servicio',
                'Barbero', 'Estado', 'Importe', 'Observaciones',
            ], $sectionStyle));

            foreach ($data['citas'] as $cita) {
                $writer->addRow(Row::fromValuesWithStyles([
                    Carbon::parse($cita->fecha)->format('d/m/Y'),
                    Carbon::parse($cita->hora_inicio)->format('H:i'),
                    Carbon::parse($cita->hora_fin)->format('H:i'),
                    trim(($cita->cliente->nombre ?? 'Sin cliente') . ' ' . ($cita->cliente->apellido ?? '')),
                    $cita->servicio->nombre ?? 'Sin servicio',
                    $cita->barbero->nombre ?? 'Sin asignar',
                    ucfirst($cita->estado),
                    (float) $cita->precio,
                    $cita->observaciones ?? '',
                ], columnStyles: [7 => $moneyStyle]));
            }

            $writer->close();
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
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
