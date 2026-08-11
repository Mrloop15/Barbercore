<?php

namespace Tests\Feature;

use Tests\TestCase;

class ErrorPagesTest extends TestCase
{
    public function test_not_found_responses_use_the_custom_error_page(): void
    {
        $this->get('/pagina-que-no-existe')
            ->assertNotFound()
            ->assertSee('Esta silla está vacía')
            ->assertSee('BarberCore');
    }

    public function test_common_error_views_render_their_status_and_action(): void
    {
        $expectations = [
            403 => ['Esta sección está reservada', 'Ir al inicio'],
            419 => ['Tu acceso perdió vigencia', 'Iniciar sesión'],
            429 => ['Vamos con un poco de calma', 'Intentar de nuevo'],
            500 => ['Necesitamos ajustar algo', 'Intentar de nuevo'],
            503 => ['Estamos preparando todo', 'Comprobar de nuevo'],
        ];

        foreach ($expectations as $status => [$title, $action]) {
            $this->view("errors.{$status}")
                ->assertSee((string) $status)
                ->assertSee($title)
                ->assertSee($action);
        }
    }

    public function test_google_material_symbols_are_self_hosted_and_cached_for_offline_use(): void
    {
        $layout = file_get_contents(resource_path('views/errors/layout.blade.php'));
        $offline = file_get_contents(public_path('offline.html'));
        $serviceWorker = file_get_contents(public_path('sw.js'));

        $this->assertFileExists(public_path('fonts/material-symbols-barbercore.woff2'));
        $this->assertStringContainsString('Material Symbols Outlined', $layout);
        $this->assertStringContainsString('Material Symbols Outlined', $offline);
        $this->assertStringContainsString('/fonts/material-symbols-barbercore.woff2?v=3', $serviceWorker);
        $this->assertStringContainsString('wifi_off', $offline);
    }
}
