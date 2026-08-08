<?php

namespace Tests\Feature;

use Tests\TestCase;

class ThemeToggleTest extends TestCase
{
    public function test_admin_layout_includes_an_accessible_theme_toggle_before_content_is_painted(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));

        $this->assertStringContainsString("localStorage.getItem('barbercore-theme')", $layout);
        $this->assertStringContainsString('prefers-color-scheme: dark', $layout);
        $this->assertStringContainsString('id="themeToggle"', $layout);
        $this->assertStringContainsString('aria-pressed="false"', $layout);
        $this->assertStringContainsString('<x-icon name="sun"', $layout);
        $this->assertStringContainsString('<x-icon name="moon"', $layout);
        $this->assertLessThan(
            strpos($layout, "asset('css/barbercore-admin.css')"),
            strpos($layout, "localStorage.getItem('barbercore-theme')"),
        );
    }

    public function test_theme_assets_persist_the_choice_and_are_refreshed_by_the_pwa_cache(): void
    {
        $script = file_get_contents(public_path('js/theme-toggle.js'));
        $dialog = file_get_contents(public_path('js/barber-dialog.js'));
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));
        $serviceWorker = file_get_contents(public_path('sw.js'));

        $this->assertStringContainsString('localStorage.setItem(storageKey, nextTheme)', $script);
        $this->assertStringContainsString('root.dataset.theme = theme', $script);
        $this->assertStringContainsString('html[data-theme="dark"]', $styles);
        $this->assertStringContainsString('.theme-toggle', $styles);
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $styles);
        $this->assertStringContainsString('background:var(--blanco,#fff)', $dialog);
        $this->assertStringContainsString('color:var(--texto,#1C1C1C)', $dialog);
        $this->assertStringContainsString('/js/theme-toggle.js', $serviceWorker);
    }
}
