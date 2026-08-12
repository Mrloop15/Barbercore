<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardChartAnimationTest extends TestCase
{
    public function test_dashboard_charts_reveal_progressively_with_real_geometry(): void
    {
        $view = file_get_contents(resource_path('views/dashboard/index.blade.php'));
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));

        $this->assertSame(5, substr_count($view, 'data-chart-reveal'));
        $this->assertStringContainsString("asset('js/dashboard-charts.js') }}?v=1", $view);
        $this->assertStringContainsString('@property --donut-reveal', $styles);
        $this->assertMatchesRegularExpression('/@property --donut-reveal\s*\{[^}]*inherits:\s*true;/s', $styles);
        $this->assertStringContainsString('transition: --donut-reveal 2.4s', $styles);
        $this->assertStringContainsString('transition: clip-path 2.2s', $styles);
        $this->assertMatchesRegularExpression(
            '/\.dashboard-motion-ready \.dashboard-chart-reveal\.is-visible \.income-area-line\s*\{[^}]*clip-path:\s*inset\(0\);[^}]*transition:\s*clip-path 2\.4s/s',
            $styles,
        );
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $styles);
    }

    public function test_animation_script_has_mobile_safe_fallbacks_and_is_versioned_in_the_pwa(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));
        $script = file_get_contents(public_path('js/dashboard-charts.js'));
        $worker = file_get_contents(public_path('sw.js'));

        $this->assertStringContainsString("@stack('scripts')", $layout);
        $this->assertStringContainsString("matchMedia('(prefers-reduced-motion: reduce)')", $script);
        $this->assertStringContainsString("'IntersectionObserver' in window", $script);
        $this->assertStringContainsString('requestAnimationFrame', $script);
        $this->assertStringContainsString('observer.unobserve(entry.target)', $script);
        $this->assertStringContainsString('"/js/dashboard-charts.js?v=1"', $worker);
        $this->assertStringContainsString('barbercore-web-v16', $worker);
    }
}
