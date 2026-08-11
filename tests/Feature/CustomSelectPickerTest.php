<?php

namespace Tests\Feature;

use Tests\TestCase;

class CustomSelectPickerTest extends TestCase
{
    public function test_every_panel_select_uses_the_shared_picker(): void
    {
        $views = [
            'citas/index.blade.php' => 1,
            'citas/create.blade.php' => 3,
            'citas/edit.blade.php' => 3,
            'ventas/create.blade.php' => 2,
            'recompensas/canjear.blade.php' => 2,
            'recompensas/create.blade.php' => 1,
            'recompensas/edit.blade.php' => 1,
            'agenda/index.blade.php' => 1,
            'usuarios/index.blade.php' => 2,
            'usuarios/create.blade.php' => 1,
            'usuarios/edit.blade.php' => 1,
            'productos/index.blade.php' => 1,
            'estadisticas/index.blade.php' => 1,
        ];

        foreach ($views as $view => $expectedFields) {
            $markup = file_get_contents(resource_path("views/{$view}"));

            $this->assertSame(
                $expectedFields,
                substr_count($markup, '<select'),
                "{$view} no contiene los selectores esperados.",
            );
        }

        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringContainsString("document.querySelectorAll('select').forEach(enhance);", $layout);
        $this->assertStringContainsString("node.matches('select')", $layout);
        $this->assertStringContainsString("node.querySelectorAll('select').forEach(enhance);", $layout);
    }

    public function test_internal_menu_scroll_keeps_the_picker_open(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));

        $this->assertStringContainsString(
            'event.target instanceof Node && opened.menu.contains(event.target)',
            $layout,
        );
        $this->assertMatchesRegularExpression(
            '/\.bc-select-menu\s*\{[^}]*overflow-y:\s*auto;[^}]*overscroll-behavior-y:\s*contain;[^}]*-webkit-overflow-scrolling:\s*touch;[^}]*touch-action:\s*pan-y;/s',
            $styles,
        );
    }

    public function test_picker_keeps_expected_close_and_accessibility_behaviour(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringContainsString("trigger.setAttribute('aria-haspopup', 'listbox');", $layout);
        $this->assertStringContainsString("menu.setAttribute('role', 'listbox');", $layout);
        $this->assertStringContainsString("item.setAttribute('role', 'option');", $layout);
        $this->assertStringContainsString("if (event.key === 'Escape' || event.key === 'Tab')", $layout);
        $this->assertStringContainsString('!opened.wrapper.contains(event.target) && !opened.menu.contains(event.target)', $layout);
    }
}
