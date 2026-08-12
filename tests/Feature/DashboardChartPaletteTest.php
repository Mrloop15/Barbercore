<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardChartPaletteTest extends TestCase
{
    public function test_dashboard_charts_use_the_requested_shared_palette(): void
    {
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));

        foreach (['#D09828', '#D4982C', '#282C30', '#98989C', '#B0B0B0', '#ECDCC4', '#E8C384', '#D8A038'] as $color) {
            $this->assertStringContainsString($color, $styles);
        }

        $this->assertStringContainsString('var(--chart-status-completed)', $styles);
        $this->assertStringContainsString('var(--chart-status-pending)', $styles);
        $this->assertStringContainsString('var(--chart-status-cancelled)', $styles);
    }

    public function test_only_product_sales_bars_receive_the_diagonal_pattern(): void
    {
        $view = file_get_contents(resource_path('views/dashboard/index.blade.php'));
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));

        $this->assertSame(1, substr_count($view, 'product-sales-bar'));
        $this->assertStringContainsString('repeating-linear-gradient(', $styles);
        $this->assertSame(2, substr_count($styles, '--chart-product-stripe: #282C30;'));
        $this->assertMatchesRegularExpression(
            '/\.product-sales-bar\s*\{[^}]*background-color:\s*var\(--chart-product-fill\);[^}]*background-image:\s*repeating-linear-gradient\([^}]*var\(--chart-product-stripe\)/s',
            $styles,
        );
    }
}
