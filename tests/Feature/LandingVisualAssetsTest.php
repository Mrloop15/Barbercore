<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class LandingVisualAssetsTest extends TestCase
{
    public function test_landing_uses_google_symbols_for_every_generic_icon(): void
    {
        $landing = file_get_contents(resource_path('views/landing.blade.php'));
        $styles = file_get_contents(public_path('css/barbercore-landing.css'));
        $offline = file_get_contents(public_path('offline.html'));
        $errorLayout = file_get_contents(resource_path('views/errors/layout.blade.php'));
        $serviceWorker = file_get_contents(public_path('sw.js'));

        foreach ([
            'add',
            'calendar_month',
            'call',
            'check',
            'chevron_left',
            'chevron_right',
            'close',
            'event_available',
            'image',
            'location_on',
            'login',
            'schedule',
            'send',
            'tune',
            'verified_user',
        ] as $symbol) {
            $this->assertStringContainsString($symbol, $landing);
        }

        $this->assertSame(3, substr_count($landing, '<svg'));
        $this->assertStringNotContainsString('<svg class="icon"', $landing);
        $this->assertStringContainsString('aria-label="Facebook"', $landing);
        $this->assertStringContainsString('aria-label="Instagram"', $landing);
        $this->assertStringContainsString('aria-label="TikTok"', $landing);
        $this->assertStringNotContainsString('fonts.googleapis.com', $landing);
        $this->assertStringContainsString('barbercore-web-v20', $serviceWorker);

        foreach ([$styles, $offline, $errorLayout, $serviceWorker] as $asset) {
            $this->assertStringContainsString('/fonts/material-symbols-barbercore.woff2?v=3', $asset);
        }
    }

    public function test_service_cards_use_local_google_material_symbols(): void
    {
        $landing = file_get_contents(resource_path('views/landing.blade.php'));
        $styles = file_get_contents(public_path('css/barbercore-landing.css'));
        $serviceIconMarkup = substr(
            $landing,
            strpos($landing, '<span class="service-icon"'),
            strpos($landing, '<span class="service-index"') - strpos($landing, '<span class="service-icon"'),
        );

        $this->assertStringContainsString('<span class="material-symbol">{{ $iconoServicio }}</span>', $serviceIconMarkup);
        $this->assertStringNotContainsString('<svg', $serviceIconMarkup);
        $this->assertStringContainsString("=> 'face_retouching_natural'", $landing);
        $this->assertStringContainsString("default => 'health_and_beauty'", $landing);
        $this->assertStringContainsString('/fonts/material-symbols-barbercore.woff2?v=3', $styles);
        $this->assertMatchesRegularExpression(
            '/\.service-icon \.material-symbol\s*\{[^}]*font-size:\s*24px;/s',
            $styles,
        );
    }

    public function test_whatsapp_icons_use_the_official_square_glyph_without_transforms(): void
    {
        $landing = file_get_contents(resource_path('views/landing.blade.php'));
        $icon = file_get_contents(resource_path('views/components/icon.blade.php'));
        $styles = file_get_contents(public_path('css/barbercore-landing.css'));
        $renderedIcon = Blade::render('<x-icon name="whatsapp" class="whatsapp-icon" />');

        $this->assertSame(2, substr_count($landing, '<x-icon name="whatsapp" class="whatsapp-icon"'));
        $this->assertStringContainsString("\$name === 'whatsapp' ? '0 0 720 720'", $icon);
        $this->assertStringContainsString("'preserveAspectRatio' => 'xMidYMid meet'", $icon);
        $this->assertStringContainsString('WhatsApp Digital Glyph White RGB 2026', $icon);
        $this->assertStringContainsString('M360,0C161.18,0,0,161.18,0,360', $icon);
        $this->assertStringContainsString('viewBox="0 0 720 720"', $renderedIcon);
        $this->assertStringContainsString('preserveAspectRatio="xMidYMid meet"', $renderedIcon);
        $this->assertSame(2, substr_count($renderedIcon, '<path'));
        $this->assertMatchesRegularExpression(
            '/\.whatsapp-toggle\s*\{[^}]*display:\s*grid;[^}]*place-items:\s*center;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.whatsapp-toggle > \.whatsapp-icon\s*\{[^}]*width:\s*28px;[^}]*height:\s*28px;[^}]*aspect-ratio:\s*1;[^}]*overflow:\s*hidden;[^}]*transform:\s*none;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.chat-action \.whatsapp-icon\s*\{[^}]*width:\s*18px;[^}]*height:\s*18px;[^}]*aspect-ratio:\s*1;[^}]*overflow:\s*hidden;[^}]*flex:\s*0 0 18px;[^}]*transform:\s*none;/s',
            $styles,
        );
    }
}
