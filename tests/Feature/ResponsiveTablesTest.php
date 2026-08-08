<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResponsiveTablesTest extends TestCase
{
    public function test_table_scroll_is_isolated_from_the_page_viewport(): void
    {
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));

        $this->assertMatchesRegularExpression(
            '/\.table-responsive\s*\{[^}]*overflow-x:\s*auto;[^}]*contain:\s*paint;/s',
            $styles,
        );
        $this->assertStringContainsString('overscroll-behavior-x: contain;', $styles);
    }

    public function test_panel_tables_have_a_dedicated_scroll_region(): void
    {
        $views = [
            'dashboard/index.blade.php',
            'clientes/index.blade.php',
            'clientes/inactivos.blade.php',
            'clientes/show.blade.php',
            'servicios/index.blade.php',
            'productos/index.blade.php',
            'usuarios/index.blade.php',
            'ventas/index.blade.php',
            'ventas/show.blade.php',
            'recompensas/index.blade.php',
            'recompensas/canjear.blade.php',
            'estadisticas/index.blade.php',
        ];

        foreach ($views as $view) {
            $markup = file_get_contents(resource_path("views/{$view}"));

            $this->assertStringContainsString('class="table-responsive', $markup, "{$view} no contiene una región responsive.");
            $this->assertStringContainsString('tabindex="0"', $markup, "{$view} no permite desplazamiento con teclado.");
        }
    }

    public function test_product_identity_keeps_image_and_description_together(): void
    {
        $markup = file_get_contents(resource_path('views/productos/index.blade.php'));

        $this->assertStringContainsString('class="product-table-image"', $markup);
        $this->assertStringContainsString('class="product-description"', $markup);
        $this->assertStringNotContainsString('<th>Imagen</th>', $markup);
    }

    public function test_descriptive_modules_group_related_identity_data(): void
    {
        $expectations = [
            'servicios/index.blade.php' => ['service-table-image', 'service-description', '<th>Imagen</th>', '<th>Descripción</th>'],
            'clientes/index.blade.php' => ['client-table-image', 'client-identity-copy', '<th>Foto</th>', '<th>Nombre</th>'],
            'usuarios/index.blade.php' => ['user-avatar', 'user-identity-copy', '<th>Nombre</th>', '<th>Correo</th>'],
        ];

        foreach ($expectations as $view => [$visual, $copy, $oldVisualColumn, $oldCopyColumn]) {
            $markup = file_get_contents(resource_path("views/{$view}"));

            $this->assertStringContainsString($visual, $markup);
            $this->assertStringContainsString($copy, $markup);
            $this->assertStringNotContainsString($oldVisualColumn, $markup);
            $this->assertStringNotContainsString($oldCopyColumn, $markup);
        }

        $rewards = file_get_contents(resource_path('views/recompensas/index.blade.php'));
        $this->assertStringContainsString('reward-description', $rewards);
    }
}
