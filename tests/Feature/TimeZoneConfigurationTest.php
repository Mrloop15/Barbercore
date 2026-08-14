<?php

namespace Tests\Feature;

use Tests\TestCase;

class TimeZoneConfigurationTest extends TestCase
{
    public function test_application_and_database_use_utc_with_guadalajara_as_display_timezone(): void
    {
        $this->assertSame('UTC', config('app.timezone'));
        $this->assertSame('America/Mexico_City', config('app.display_timezone'));
        $this->assertSame('+00:00', config('database.connections.mysql.timezone'));
        $this->assertSame('+00:00', config('database.connections.mariadb.timezone'));
    }

    public function test_configuration_hours_allow_exact_minutes_without_the_slot_picker(): void
    {
        $configuration = file_get_contents(resource_path('views/configuracion/index.blade.php'));
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));
        $controller = file_get_contents(app_path('Http/Controllers/Web/ConfiguracionController.php'));

        $this->assertSame(2, substr_count($configuration, 'step="60" data-exact-time'));
        $this->assertStringContainsString("input.hasAttribute('data-exact-time')", $layout);
        $this->assertStringContainsString('function enhanceExactTimeInput(input)', $layout);
        $this->assertStringContainsString('class="custom-time-panel exact-time-panel"', $layout);
        $this->assertStringContainsString('data-exact-hour', $layout);
        $this->assertStringContainsString('data-exact-minute', $layout);
        $this->assertStringContainsString("'horarios.*.hora_apertura' => 'nullable|date_format:H:i'", $controller);
        $this->assertStringContainsString("'horarios.*.hora_cierre' => 'nullable|date_format:H:i'", $controller);
    }

    public function test_exact_time_picker_is_contained_and_uses_scoped_configuration_styles(): void
    {
        $adminStyles = file_get_contents(public_path('css/barbercore-admin.css'));
        $configurationStyles = file_get_contents(public_path('css/modules/configuration.css'));

        $this->assertStringContainsString('.exact-time-fields {', $adminStyles);
        $this->assertStringContainsString('.exact-time-actions {', $adminStyles);
        $this->assertStringContainsString('#horarios.content-card { overflow: visible; }', $configurationStyles);
        $this->assertStringContainsString('.hours-field:last-child .custom-time-panel { right: 0; left: auto; }', $configurationStyles);
        $this->assertStringContainsString('.hours-field > label {', $configurationStyles);
        $this->assertStringNotContainsString('.hours-field span {', $configurationStyles);
    }

    public function test_frontend_receives_the_business_timezone_and_date(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));
        $landing = file_get_contents(resource_path('views/landing.blade.php'));

        foreach ([$layout, $landing] as $view) {
            $this->assertStringContainsString('meta name="business-timezone"', $view);
            $this->assertStringContainsString('meta name="business-today"', $view);
        }

        $this->assertStringNotContainsString('datetime-local', $layout.$landing);
    }
}
