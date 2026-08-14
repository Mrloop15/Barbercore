<?php

namespace Tests\Feature;

use Tests\TestCase;

class SalesReportingTest extends TestCase
{
    public function test_sales_are_included_in_dashboard_income_and_client_interactions(): void
    {
        $service = file_get_contents(app_path('Services/BusinessMetricsService.php'));

        $this->assertStringContainsString('use App\\Models\\VentaProducto;', $service);
        $this->assertStringContainsString('$dailyProductIncome', $service);
        $this->assertStringContainsString("VentaProducto::where('id_barberia', \$idBarberia)", $service);
        $this->assertStringContainsString('$appointmentClients->concat($saleClients)', $service);
        $this->assertStringContainsString('$completedClientDates', $service);
        $this->assertStringContainsString('BusinessClock::fromUtc($venta->fecha_venta)->toDateString()', $service);
        $this->assertStringContainsString('->reject(fn ($venta)', $service);
        $this->assertStringContainsString("'foto' => \$client->foto", $service);
        $this->assertStringContainsString("'ingresosMes'", $service);
    }

    public function test_sales_traceability_is_safe_before_and_after_the_schema_update(): void
    {
        $migration = file_get_contents(database_path('migrations/2026_08_13_000000_add_vendedor_to_ventas_productos_table.php'));
        $model = file_get_contents(app_path('Models/VentaProducto.php'));
        $webController = file_get_contents(app_path('Http/Controllers/Web/VentaProductoController.php'));
        $apiController = file_get_contents(app_path('Http/Controllers/Api/VentaProductoApiController.php'));
        $command = file_get_contents(base_path('routes/console.php'));

        $this->assertStringContainsString("unsignedBigInteger('id_usuario')->nullable()", $migration);
        $this->assertStringContainsString("Schema::hasColumn('ventas_productos', 'id_usuario')", $migration);
        $this->assertStringContainsString('function supportsVendedor()', $model);
        $this->assertStringContainsString('function scopeWithVendedor', $model);
        $this->assertStringContainsString('if (VentaProducto::supportsVendedor())', $webController);
        $this->assertStringContainsString('if (VentaProducto::supportsVendedor())', $apiController);
        $this->assertStringContainsString('barbercore:ensure-sales-audit', $command);
    }

    public function test_reports_include_sales_income_and_a_detailed_sales_audit_trail(): void
    {
        $controller = file_get_contents(app_path('Http/Controllers/Web/EstadisticaController.php'));
        $view = file_get_contents(resource_path('views/estadisticas/index.blade.php'));
        $pdf = file_get_contents(resource_path('views/estadisticas/pdf.blade.php'));

        $this->assertStringContainsString("VentaProducto::with(['cliente', 'detalles.producto'])", $controller);
        $this->assertStringContainsString('->withVendedor()', $controller);
        $this->assertStringContainsString("'ingresosProductos'", $controller);
        $this->assertStringContainsString("'Ingresos por productos'", $controller);
        $this->assertStringContainsString("'Ventas de productos'", $controller);
        $this->assertStringContainsString('Ventas de productos', $view);
        $this->assertStringContainsString('vendedor_nombre', $view);
        $this->assertStringContainsString('Ventas de productos', $pdf);
        $this->assertStringContainsString('vendedor_nombre', $pdf);
    }
}
