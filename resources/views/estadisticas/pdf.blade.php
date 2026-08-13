<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 28px; }
        body { font-family: DejaVu Sans, sans-serif; color: #1C1C1C; font-size: 9px; }
        h1 { font-size: 22px; margin: 0; }
        h2 { font-size: 13px; margin: 20px 0 8px; }
        .brand { color: #C9A227; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; }
        .meta { color: #6B6B6B; margin: 5px 0 18px; }
        .summary { width: 100%; margin-bottom: 16px; border-collapse: separate; border-spacing: 4px; }
        .summary td { background: #FAF8F2; border: 1px solid #E5E0D6; border-radius: 5px; padding: 7px; }
        .summary span { display: block; color: #6B6B6B; font-size: 7px; text-transform: uppercase; }
        .summary strong { font-size: 12px; color: #C9A227; }
        table.data { width: 100%; border-collapse: collapse; }
        .data th { background: #1C1C1C; color: #fff; text-align: left; padding: 6px; font-size: 7px; }
        .data td { padding: 6px; border-bottom: 1px solid #E5E0D6; vertical-align: top; }
        .footer { position: fixed; bottom: -16px; left: 0; right: 0; text-align: center; color: #6B6B6B; font-size: 8px; }
    </style>
</head>
<body>
    <div class="brand">BarberCore - {{ $barberia }}</div>
    <h1>Reporte de operacion</h1>
    <div class="meta">Periodo: {{ $desde->format('d/m/Y') }} al {{ $hasta->format('d/m/Y') }} - Estado de citas: {{ ucfirst($estado) }} - Generado: {{ now()->format('d/m/Y H:i') }}</div>

    <table class="summary"><tr>
        <td><span>Total citas</span><strong>{{ $totalCitas }}</strong></td>
        <td><span>Completadas</span><strong>{{ $completadas }}</strong></td>
        <td><span>Pendientes</span><strong>{{ $pendientes }}</strong></td>
        <td><span>Canceladas</span><strong>{{ $canceladas }}</strong></td>
        <td><span>Ventas productos</span><strong>{{ $totalVentas }}</strong></td>
        <td><span>Ingresos servicios</span><strong>${{ number_format($ingresosServicios, 2) }}</strong></td>
        <td><span>Ingresos productos</span><strong>${{ number_format($ingresosProductos, 2) }}</strong></td>
        <td><span>Ingresos totales</span><strong>${{ number_format($ingresos, 2) }}</strong></td>
    </tr></table>

    <h2>Citas</h2>
    <table class="data"><thead><tr><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Servicio</th><th>Barbero</th><th>Estado</th><th>Importe</th></tr></thead><tbody>
    @forelse($citas as $cita)
        <tr><td>{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</td><td>{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}</td><td>{{ $cita->cliente->nombre ?? 'Sin cliente' }} {{ $cita->cliente->apellido ?? '' }}</td><td>{{ $cita->servicio->nombre ?? 'Sin servicio' }}</td><td>{{ $cita->barbero->nombre ?? 'Sin asignar' }}</td><td>{{ ucfirst($cita->estado) }}</td><td>${{ number_format($cita->precio, 2) }}</td></tr>
    @empty
        <tr><td colspan="7">Sin registros.</td></tr>
    @endforelse
    </tbody></table>

    <h2>Ventas de productos</h2>
    <table class="data"><thead><tr><th>Fecha</th><th>Hora</th><th>Cliente</th><th>Registrada por</th><th>Productos</th><th>Importe</th></tr></thead><tbody>
    @forelse($ventas as $venta)
        <tr><td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('d/m/Y') }}</td><td>{{ \Carbon\Carbon::parse($venta->fecha_venta)->format('H:i') }}</td><td>{{ $venta->cliente->nombre ?? 'Cliente general' }} {{ $venta->cliente->apellido ?? '' }}</td><td>{{ $venta->vendedor_nombre }}</td><td>{{ $venta->detalles->map(fn ($detalle) => ($detalle->producto->nombre ?? 'Producto eliminado') . ' x' . $detalle->cantidad)->implode(', ') }}</td><td>${{ number_format($venta->total, 2) }}</td></tr>
    @empty
        <tr><td colspan="6">Sin ventas de productos.</td></tr>
    @endforelse
    </tbody></table>
    <div class="footer">Documento generado por BarberCore</div>
</body>
</html>
