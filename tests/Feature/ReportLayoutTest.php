<?php

namespace Tests\Feature;

use Tests\TestCase;

class ReportLayoutTest extends TestCase
{
    public function test_report_uses_one_consolidated_summary_with_three_sections(): void
    {
        $view = file_get_contents(resource_path('views/estadisticas/index.blade.php'));

        $this->assertStringContainsString('class="content-card report-overview"', $view);
        $this->assertSame(3, substr_count($view, 'class="report-overview-item'));
        $this->assertStringContainsString('Actividad del periodo', $view);
        $this->assertStringContainsString('Estado de las citas', $view);
        $this->assertStringContainsString('Ingresos totales', $view);
        $this->assertStringNotContainsString('agenda-summary report-summary', $view);
    }

    public function test_report_tables_are_separated_and_keep_their_own_scroll_regions(): void
    {
        $view = file_get_contents(resource_path('views/estadisticas/index.blade.php'));
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));

        $this->assertStringContainsString('class="report-sections"', $view);
        $this->assertSame(2, substr_count($view, 'class="content-card report-preview"'));
        $this->assertSame(2, substr_count($view, 'class="table-responsive" tabindex="0" role="region"'));
        $this->assertMatchesRegularExpression('/\.report-sections\s*\{[^}]*display:grid;[^}]*gap:18px;[^}]*min-width:0;/s', $styles);
        $this->assertMatchesRegularExpression('/\.report-preview\s*\{[^}]*min-width:0;[^}]*overflow:hidden;/s', $styles);
    }
}
