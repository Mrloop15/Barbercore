<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResponsiveTablesTest extends TestCase
{
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
}
