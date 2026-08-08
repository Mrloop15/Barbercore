<?php

namespace Tests\Feature;

use Tests\TestCase;

class MobileDatePickerTest extends TestCase
{
    public function test_every_admin_date_field_uses_the_shared_date_picker(): void
    {
        $views = [
            'clientes/create.blade.php' => 1,
            'clientes/edit.blade.php' => 1,
            'citas/index.blade.php' => 2,
            'citas/create.blade.php' => 1,
            'citas/edit.blade.php' => 1,
            'agenda/index.blade.php' => 1,
            'ventas/index.blade.php' => 1,
            'estadisticas/index.blade.php' => 2,
        ];

        foreach ($views as $view => $expectedFields) {
            $markup = file_get_contents(resource_path("views/{$view}"));

            $this->assertSame(
                $expectedFields,
                substr_count($markup, 'type="date"'),
                "{$view} no contiene los campos de fecha esperados.",
            );
        }

        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringContainsString('document.querySelectorAll(\'input[type="date"]\')', $layout);
    }

    public function test_manual_date_entry_does_not_trigger_ios_focus_zoom(): void
    {
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));

        $this->assertMatchesRegularExpression(
            '/\.calendar-manual-input\s*\{[^}]*font-size:\s*16px;[^}]*touch-action:\s*manipulation;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*900px\),\s*\(hover:\s*none\)\s*and\s*\(pointer:\s*coarse\)\s*\{\s*input,\s*textarea,\s*select\s*\{\s*font-size:\s*16px;/s',
            $styles,
        );
    }

    public function test_keyboard_viewport_changes_keep_the_active_calendar_open(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringContainsString('function calendarHasFocus()', $layout);
        $this->assertStringContainsString("if (calendarHasFocus()) return;\n            closeOpenCalendars();", $layout);
        $this->assertStringContainsString("event.target.closest('.custom-calendar')) || calendarHasFocus()", $layout);
    }
}
