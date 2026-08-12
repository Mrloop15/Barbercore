<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardIncomeChartTest extends TestCase
{
    public function test_income_history_uses_an_accessible_native_area_chart(): void
    {
        $view = file_get_contents(resource_path('views/dashboard/index.blade.php'));

        $this->assertStringContainsString('class="income-area-chart"', $view);
        $this->assertStringContainsString('class="income-area-fill"', $view);
        $this->assertStringContainsString('class="income-area-line"', $view);
        $this->assertStringContainsString('class="income-area-point"', $view);
        $this->assertStringContainsString('preserveAspectRatio="none"', $view);
        $this->assertStringContainsString('role="img"', $view);
        $this->assertStringContainsString('class="sr-only"', $view);
        $this->assertStringNotContainsString('class="vertical-chart"', $view);
        $this->assertStringNotContainsString('<canvas', $view);
    }

    public function test_area_chart_keeps_the_previous_height_and_responsive_width(): void
    {
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));

        $this->assertMatchesRegularExpression(
            '/\.income-area-chart\s*\{[^}]*width:\s*100%;[^}]*height:\s*190px;[^}]*min-width:\s*0;[^}]*overflow:\s*hidden;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.income-area-line\s*\{[^}]*stroke:\s*var\(--dorado\);[^}]*vector-effect:\s*non-scaling-stroke;/s',
            $styles,
        );
        $this->assertStringContainsString('fill: url(#incomeAreaGold);', $styles);
        $this->assertStringContainsString('grid-template-columns: repeat(7, minmax(0, 1fr));', $styles);
    }
}
